<?php

namespace App\Http\Controllers\Authentication\Librarian;

use Carbon\Carbon;
use App\Models\{User,Librarian,State,Address,District};
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function registerForm()
    {
        $states = State::all();
        return view('auth.librarian.register',compact('states'));
    }

    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'first_name'         => ['required', 'string', 'max:50'],
            'last_name'          => ['nullable', 'string', 'max:50'],
            'contact_no'         => ['required', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('librarians', 'contact_no'), Rule::unique('users', 'contact_no')],
            'alt_contact_no'     => ['nullable', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('librarians', 'alt_contact_no')],
            'email'              => ['required', 'email', 'max:100', Rule::unique('librarians', 'email'), Rule::unique('users', 'email')],
            'gender'             => ['required', 'in:male,female,other'],
            'date_of_birth'      => ['required', 'date', 'before_or_equal:today'],
            'joining_date'       => ['required', 'date', 'after_or_equal:today'],
            'address_line1'      => ['required', 'string'],
            'address_line2'      => ['nullable', 'string'],
            'city'               => ['nullable', 'string', 'max:100'],
            'state_id'           => ['required', 'exists:states,id'],
            'district_id'        => ['required', 'exists:districts,id'],
            'pincode'            => ['required', 'digits:6', 'regex:/^[0-9]{6}$/'],
            'user_id'            => ['nullable', 'exists:users,id'],
            'address_id'         => ['nullable', 'exists:addresses,id'],
            'password'           => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'check_term'         => ['accepted']
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {

            $lastId = 0;
            $getId = Librarian::orderBy('id', 'DESC')->first();
            if (!empty($getId)) {
                $lastId = intval(explode('-', $getId->employee_code)[1]);
            }
            $presentId = $lastId + 1;
            $empCode = "LIBR-" . str_pad($presentId, 5, "0", STR_PAD_LEFT);
            //LIBR-00001
            /**
             * LIBR->0
             * 00001->1
             * intval(00001)->1
             */

            $validated = $validated->validated();

            $user = User::create([
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'password'       => $validated['password'],
                'role'           => 'librarian'
            ]);

            $address = Address::create([
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city'          => $validated['city']          ?? null,
                'state_id'      => $validated['state_id'],
                'district_id'   => $validated['district_id'],
                'pincode'       => $validated['pincode']
            ]);

            Librarian::create([
                'user_id'        => $user->id,
                'address_id'     => $address->id,
                'employee_code'  => $empCode,
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'gender'         => $validated['gender'],
                'date_of_birth'  => $validated['date_of_birth'],
                'joining_date'   => $validated['joining_date'],
                'check_term'     => true
            ]);

            $token = Str::random(64);
            $verifyLink = url('/librarian/verify-email/' . $token . '?email=' . $validated['email']);
            $data         = [];
            $email_sent   = '';
            $data['link'] = $verifyLink;
            $data['name'] = $user->full_name;
            if (filter_var($validated['email'], FILTER_VALIDATE_EMAIL)) {
                $email_sent = Mail::to($validated['email'])->send(new VerifyEmail($data));
            }
            if ($email_sent) {
                $user->update([
                    'email_verification_token'   => $token,
                    'email_verification_sent_at' => Carbon::now()
                ]);
            }
            Session::put('email', $validated['email']);

            DB::commit();
            return redirect()->route('librarian.verify-email')->with('success', 'Librarian created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('librarian.register-librarian')->with('error', 'Something went wrong. Please try again later.' . $e->getMessage())->withInput();
        }
    }

    public function getDistrict($state_id)
    {
        return District::where('state_id', $state_id)->get();
    }

    public function verifyEmailForm()
    {
        return view('auth.librarian.verify-email');
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();
        if (!$user) {
            return redirect()->route('librarian.login')->with('error', 'Invalid or expired verification link.');
        }

        if (Carbon::parse($user->email_verification_sent_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'link has been expired.']);
        }

        User::where('id', $user->id)->update([
            'is_verified'                => 1,
            'email_verification_token'   => null,
            'email_verified_at'          => now(),
            'email_verification_sent_at' => null
        ]);
        Session::forget('email');
        return redirect()->route('librarian.login')->with('success', 'Email verified successfully. You can now login.');
    }

    public function resendEmailLink(Request $request)
    {
        $email = Session::get('email');
        $user  = User::where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'Invalid or expired verification link.');
        }

        if ($user->is_verified == 1) {
            return back()->with('success', 'Your email is already verified!');
        }
        $lastRequest = User::where('email', $email)->orderBy('email_verification_sent_at', 'desc')->first();
        if ($lastRequest && Carbon::parse($lastRequest->email_verification_sent_at)->addMinutes(1)->isFuture()) {
            return back()->with('error', 'Please wait for 1 minutes to requesting another verification link.');
        }
        $token = Str::random(64);
        $verifyLink = url('/librarian/verify-email/' . $token . '?email=' . $request->email);
        $data         = [];
        $email_sent   = '';
        $data['link'] = $verifyLink;
        $data['name'] = $user->full_name;
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $email_sent = Mail::to($request->email)->send(new VerifyEmail($data));
        }
        if ($email_sent) {
            $user->update([
                'email_verification_token'    => $token,
                'email_verification_sent_at'  => Carbon::now()
            ]);
        }
        Session::put('email', $request->email);
        return back()->with('success', 'Link has been resent successfully.');
    }


    public function loginForm()
    {
        return view('auth.librarian.login');
    }

    public function login(Request $request)
    {
        $validated     = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        try {

            $validated = $validated->validated();
            $user = User::where('email', $request->email)->where('role', 'librarian')->first();

            if (!$user) {
                return back()->withErrors(['email' => "User does not exist!"]);
            }

            if ($user->is_block == 1) {
                return back()->withErrors(['email' => "Contact to admin!"]);
            }

            if ($user->is_verified == 0) {
                return back()->withErrors(['email' => "Please verify your account to login!"]);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                return back()->withErrors(['password' => "Invalid password!"]);
            }

            if (!Auth::attempt($validated)) {
                return back()->withErrors(['email' => "Authentication failed!"]);
            }

            Auth::login($user);
            return redirect()->route('home')->with('success', "Login successful.");
        } catch (\Exception $e) {
            return back()->withErrors(['email' => "Something went wrong. Please try again later."]);
        }
    }

    public function forgotPasswordForm()
    {
        return view('auth.librarian.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $token = Str::random(64);
        PasswordResetToken::where('email', $request->email)->delete();
        PasswordResetToken::create([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now()
        ]);
        $resetLink    = url('/librarian/reset-password/' . $token . '?email=' . $request->email);
        $data         = [];
        $data['link'] = $resetLink;
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($request->email)->send(new ForgotPassword($data));
        }
        Session::put('email', $request->email);
        return back()->with('success', 'Password reset link sent to your registered email.');
    }

    public function resendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email       = Session::get('email');
        $lastRequest = PasswordResetToken::where('email', $email)->orderBy('created_at', 'desc')->first();
        if ($lastRequest && Carbon::parse($lastRequest->created_at)->addMinutes(1)->isFuture()) {
            return back()->with('error', 'Please wait for 1 minutes to requesting another reset link.');
        }
        PasswordResetToken::where('email', $request->email)->delete();
        $token = Str::random(64);
        PasswordResetToken::create([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now()
        ]);
        $resetLink = url('/librarian/reset-password/' . $token . '?email=' . $request->email);
        $data         = [];
        $data['link'] = $resetLink;
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($request->email)->send(new ForgotPassword($data));
        }
        Session::put('email', $email);
        return back()->with('success', 'Password reset link has been resent successfully.');
    }

    public function resetPasswordForm()
    {
        return view('auth.librarian.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'       => 'required|email',
            'password'    => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);

        $reset = PasswordResetToken::where('email', $request->email)->first();
        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid or expired reset request.']);
        }
        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            PasswordResetToken::where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Reset link has been expired.']);
        }
        User::where('email', $request->email)->update([
            'password'   => Hash::make($request->password),
            'updated_at' => now()
        ]);
        PasswordResetToken::where('email', $request->email)->delete();
        Session::forget('email');
        return redirect()->route('librarian.login')->with('success', 'Password updated successfully. Please login.');
    }

    public function editProfilerForm()
    {
        return view('auth.librarian.edit-profile');
    }

    public function editProfileForm()
    {
        $user = Auth::user()->load('librarians.address');
        return view('auth.librarian.edit-profile',compact('user'));
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home');
    }
}
