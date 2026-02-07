<?php

namespace App\Http\Controllers\Authentication\Member;

use Carbon\Carbon;
use App\FileUpload;
use App\Models\User;
use App\Models\Member;
use App\Models\State;
use App\Models\Address;
use App\Models\District;
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
    use FileUpload;

    public function registerForm()
    {
        $states = State::all();
        return view('auth.member.register', compact('states'));
    }

    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'first_name'         => ['required', 'string', 'max:50'],
            'last_name'          => ['nullable', 'string', 'max:50'],
            'contact_no'         => ['required', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('members', 'contact_no'), Rule::unique('users', 'contact_no')],
            'alt_contact_no'     => ['nullable', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('members', 'alt_contact_no')],
            'email'              => ['required', 'email', 'max:100', Rule::unique('members', 'email'), Rule::unique('users', 'email')],
            'gender'             => ['required', 'in:male,female,other'],
            'date_of_birth'      => ['required', 'date', 'before_or_equal:today'],
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
            $getId = Member::orderBy('id', 'DESC')->first();
            if (!empty($getId)) {
                $lastId = intval(explode('-', $getId->member_code)[1]);
            }
            $presentId = $lastId + 1;
            $memberCode = "MEM-" . str_pad($presentId, 5, "0", STR_PAD_LEFT);

            //MEM-00001 // Actual generated ID.

            /**
             * After breaking using explode it is look like it breaks in to two array index.
             * MEM  ->[0]
             * 00001->[1]
             * intval(00001)-> 1 // intval removes all zeros before any numbers only extract the actual number
             */

            $validated = $validated->validated();

            $user = User::create([
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'password'       => $validated['password'],
                'role'           => 'member'
            ]);

            $address = Address::create([
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city'          => $validated['city']          ?? null,
                'state_id'      => $validated['state_id'],
                'district_id'   => $validated['district_id'],
                'pincode'       => $validated['pincode']
            ]);

            Member::create([
                'user_id'        => $user->id,
                'address_id'     => $address->id,
                'member_code'    => $memberCode,
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'gender'         => $validated['gender'],
                'date_of_birth'  => $validated['date_of_birth'],
                'check_term'     => true
            ]);

            $token = Str::random(64);
            $verifyLink = url('/verify-email/' . $token . '?email=' . $validated['email']);
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
            return redirect()->route('verify-email')->with('success', 'Member created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('register-member')->with('error', 'Something went wrong. Please try again later.' . $e->getMessage())->withInput();
        }
    }

    public function getDistrictByState($state_id)
    {
        return District::where('state_id', $state_id)->get();
    }

    public function getDistrict($state_id)
    {
        return District::where('state_id', $state_id)->get();
    }

    public function verifyEmailForm()
    {
        return view('auth.member.verify-email');
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification link.');
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
        return redirect()->route('login')->with('success', 'Email verified successfully. You can now login.');
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
        $verifyLink = url('/verify-email/' . $token . '?email=' . $request->email);
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
        return view('auth.member.login');
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
            $user = User::where('email', $request->email)->where('role', 'member')->first();

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
        return view('auth.member.forgot-password');
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
        $resetLink    = url('/reset-password/' . $token . '?email=' . $request->email);
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
        $resetLink = url('/reset-password/' . $token . '?email=' . $request->email);
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
        return view('auth.member.reset-password');
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
        return redirect()->route('login')->with('success', 'Password updated successfully. Please login.');
    }

    public function editProfileForm()
    {
        $states = State::all();
        $user = Auth::user()->load(['members.address.state', 'members.address.district']);
        return view('auth.member.edit-profile', compact('user', 'states'));
    }

    public function updateProfile(Request $request)
    {
        $user    = Auth::user();
        $member  = $user->members()->first();
        $address = $member?->address;

        $validator = Validator::make($request->all(), [

            'first_name'     => ['required', 'string', 'max:50'],
            'last_name'      => ['nullable', 'string', 'max:50'],
            'email'          => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'contact_no'     => ['required', 'digits:10', Rule::unique('users', 'contact_no')->ignore($user->id)],
            'alt_contact_no' => ['nullable', 'digits:10', Rule::unique('members', 'alt_contact_no')->ignore($member?->id)],
            'gender'         => ['required', 'in:Male,Female,Other'],
            'date_of_birth'  => ['required', 'date', 'before_or_equal:today'],
            'address_line1'  => ['required', 'string'],
            'address_line2'  => ['nullable', 'string'],
            'city'           => ['nullable', 'string', 'max:100'],
            'state_id'       => ['required', 'exists:states,id'],
            'district_id'    => ['required', 'exists:districts,id'],
            'pincode'        => ['required', 'digits:6'],
            'profile_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            $validated    = $validator->validated();
            $profileImage = '';
             if ($request->hasFile('profile_image')) {
                if ($user->profile_image && file_exists($user->profile_image)) {
                    @unlink($user->profile_image);
                }
               $profileImage =  $this->customSaveImage($request->file('profile_image'), 'users/profile_images');
            }else {
                $profileImage = $user->profile_image;
            }

            $user->update([
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'profile_image'  => $profileImage
            ]);

            $member->address()->update([
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city'          => $validated['city']          ?? null,
                'state_id'      => $validated['state_id'],
                'district_id'   => $validated['district_id'],
                'pincode'       => $validated['pincode']
            ]);

            $member = $user->members()->update([
                'user_id'        => $user->id,
                'address_id'     => $address->id,
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'gender'         => $validated['gender'],
                'date_of_birth'  => $validated['date_of_birth']
            ]);

            DB::commit();
            return redirect()->route('edit-profile')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.'.$e)->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home');
    }
}
