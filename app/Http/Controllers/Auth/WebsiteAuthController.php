<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationOtpMail;
use App\Mail\ForgotPasswordOtpMail;
use Carbon\Carbon;

class WebsiteAuthController extends Controller
{
    // Login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();
            
            if (!$user->email_verified_at) {
                Auth::logout();
                return response()->json([
                    'status' => false,
                    'message' => 'Please verify your email address before logging in.',
                    'redirect' => route('registration.verify_otp', ['email' => $user->email])
                ], 403);
            }

            $request->session()->regenerate();
            
            return response()->json([
                'status' => true,
                'message' => 'Login successful! Redirecting...',
                'redirect' => $user->role == 'admin' ? route('admin.dashboard') : route('user.dashboard')
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'The provided credentials do not match our records.',
            'errors' => ['email' => ['Invalid email or password.']]
        ], 422);
    }

    // Register
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Generate and Send OTP
        $otp = rand(100000, 999999);
        
        DB::table('otp_codes')->updateOrInsert(
            ['email' => $user->email],
            [
                'code' => $otp,
                'is_verified' => false,
                'expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        try {
            Mail::to($user->email)->send(new RegistrationOtpMail($otp));
            $message = 'Registration successful! Please verify your email with the OTP sent to ' . $user->email;
        } catch (\Exception $e) {
            Log::error("SMTP Error: " . $e->getMessage());
            $message = 'Registration successful! (OTP logged to laravel.log due to SMTP error)';
        }

        Log::info("Registration OTP for {$user->email}: {$otp}");

        return response()->json([
            'status' => true,
            'message' => $message,
            'redirect' => route('registration.verify_otp', ['email' => $user->email])
        ]);
    }

    // Show Registration OTP Verification Page
    public function showRegistrationVerifyOtp(Request $request)
    {
        $email = $request->email;
        return view('auth.verify-otp', [
            'email' => $email,
            'type' => 'registration'
        ]);
    }

    // Verify Registration OTP
    public function verifyRegistrationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);

        $otpRecord = DB::table('otp_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP',
                'errors' => ['code' => ['The OTP is incorrect or has expired.']]
            ], 422);
        }

        // Mark user as verified
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update(['email_verified_at' => now()]);
            Auth::login($user);
        }

        // Clean up OTP
        DB::table('otp_codes')->where('id', $otpRecord->id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully! Welcome to your dashboard.',
            'redirect' => route('user.dashboard')
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // Forgot Password - Send OTP
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);
        
        DB::table('otp_codes')->updateOrInsert(
            ['email' => $request->email],
            [
                'code' => $otp,
                'is_verified' => false,
                'expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Log::info("OTP for {$request->email}: {$otp}");

        try {
            Mail::to($request->email)->send(new ForgotPasswordOtpMail($otp));
            $message = 'OTP sent to your email.';
        } catch (\Exception $e) {
            Log::error("SMTP Error (Forgot Password): " . $e->getMessage());
            $message = 'OTP generated! (Check laravel.log due to SMTP error)';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'redirect' => route('password.verify_otp', ['email' => $request->email])
        ]);
    }

    // Verify OTP
    public function showVerifyOtp(Request $request)
    {
        $email = $request->email;
        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);

        $otpRecord = DB::table('otp_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP',
                'errors' => ['code' => ['The OTP is incorrect or has expired.']]
            ], 422);
        }

        DB::table('otp_codes')->where('id', $otpRecord->id)->update(['is_verified' => true]);

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully!',
            'redirect' => route('password.reset', ['email' => $request->email, 'token' => bin2hex(random_bytes(16))])
        ]);
    }

    // Reset Password
    public function showResetPassword(Request $request)
    {
        $email = $request->email;
        // Basic check if OTP was verified
        $otpVerified = DB::table('otp_codes')
            ->where('email', $email)
            ->where('is_verified', true)
            ->exists();

        if (!$otpVerified) {
            return redirect()->route('password.request')->with('error', 'Please verify your OTP first.');
        }

        return view('auth.reset-password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Double check verification
        $otpRecord = DB::table('otp_codes')
            ->where('email', $request->email)
            ->where('is_verified', true)
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized password reset attempt.',
            ], 403);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Clean up OTP record
        DB::table('otp_codes')->where('email', $request->email)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully! You can now login.',
            'redirect' => route('login')
        ]);
    }
}
