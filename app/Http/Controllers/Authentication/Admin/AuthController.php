<?php

namespace App\Http\Controllers\Authentication\Admin;

use Carbon\Carbon;
use App\Models\User;
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
    public function loginForm()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        try {

            $validated = $validated->validated();
            $user = User::where('email', $validated['email'])->where('role', 'admin')->first();

            if (!$user) {
                return back()->withErrors(['email' => "User does not exist!"]);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                return back()->withErrors(['password' => "Invalid Password!"]);
            }

            if (!Auth::attempt($validated)) {
                return back()->withErrors(['email' => "Authentication failed!"]);
            }

            Auth::login($user);
            return redirect()->route('admin.index')->with('success', "Login successful.");
        } catch (\Exception $e) {
            return back()->withErrors(['email' => "Something went wrong. Please try again later."]);
        }
    }

    public function forgotPasswordForm()
    {
        return view('auth.admin.forgot-password');
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
        $resetLink    = url('/admin/reset-password/' . $token . '?email=' . $request->email);
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
            'email' => 'required|email|exists:users,email'
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
        $resetLink = url('/admin/reset-password/' . $token . '?email=' . $request->email);
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
        return view('auth.admin.reset-password');
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
        return redirect()->route('admin.login')->with('success', 'Password updated successfully. Please login.');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
