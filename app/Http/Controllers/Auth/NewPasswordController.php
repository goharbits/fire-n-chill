<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Repositories\AuthRepository\AuthRepository;

class NewPasswordController extends Controller
{
    public function __construct(
        private AuthRepository $authRepository,
    ) {
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $userData = $this->authRepository->verifyOTP($request->all());
            if (isset($userData['error'])) {
                return back()->withInput($request->only('email','otp'))->withErrors(['otp' => __($userData['message'])]);
            }

            $request->merge(['user_id' => $userData['user']->id]);

            $userRecover = $this->authRepository->resetPassword($request->all());
            if (isset($userRecover['error'])) {
                return back()->withInput($request->only('email','otp'))->withErrors(['status' => __($userRecover['message'])]);
            }
            return redirect()->route('login')->with(['status' => __('Password reset successfully!')]);
        } catch (\Exception $error) {
            return back()->withInput($request->only('email','otp'))->withErrors(['status' => __('Something went wrong')]);
        }
    }
}
