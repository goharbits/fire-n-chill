<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Auth\AuthController;

class VerifyOtpController extends Controller
{
    public function __construct(
        private AuthController $authController,
    )
    {
    }
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function index(Request $request)
    {
        return view('auth.verify-otp');
    }

    public function resendView(Request $request)
    {
        return view('auth.resend-otp');
    }

    public function verify(Request $request)
    {

        $response = $this->authController->emailVerification($request);

        $response = json_decode($response->getContent());
        if($response->status != self::CREATED){
            if($request->expectsJson()){
                return response()->json([
                    'status' => $response->status,
                    'message' => $response->message
                ]);
            }
            return back()->withErrors(['otp' => $response->message]);
        }

        $success_message = 'You are all set! Please login to your account.';
        if($request->expectsJson()){
            return response()->json([
                'status' => $response->status,
                'message' => $success_message,
                'redirect_url' => route('login', absolute: false).'?verified=1'
            ]);
        }

        return redirect()->intended(route('login', absolute: false).'?verified=1')->with('status', $success_message);
    }

    public function resend(Request $request)
    {
        $response = $this->authController->resendLoginOTP($request);

        $response = json_decode($response->getContent());

        if($response->status != self::CREATED){
            return $request->expectsJson() ? $response : back()->withErrors(['otp' => $response->message]);
        }

        if($request->expectsJson()){
            return response()->json([
                'status' => $response->status,
                'message' => 'A new OTP has been sent to the email you provided. Please check your inbox.'
            ]);
        }

        return redirect()->intended(route('verification.otp').'?resend=1')->with('status', 'A new OTP has been sent to the email you provided. Please check your inbox.');
    }
}
