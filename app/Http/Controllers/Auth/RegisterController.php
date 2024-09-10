<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate and register the user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a 6-digit OTP
        $otpCode = rand(100000, 999999);

        // Store OTP in the database
        OTP::create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(1), // 1 minute expiry time
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new OTPMail($otpCode)); // Create a mailable for this

        return view('auth.verify-email', ['email' => $user->email]);
    }
}
