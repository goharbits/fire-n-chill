<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User;
use App\Repositories\AuthRepository\AuthRepository;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        private AuthRepository $authRepository,
    ) {
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {

            $request->validate([
                'email' => ['required', 'email'],
            ]);
            
            $userRecover = $this->authRepository->forgotPassword($request->all());
            if (isset($userRecover['error'])) {
                return back()->withInput($request->only('email'))->withErrors(['email' => __($userRecover['message'])]);
            }

            return redirect()->route('password.reset')->with('status', __($userRecover['message']));

        } catch (\Exception $error) {
            return back()->withInput($request->only('email'))->withErrors(['email' => __('Something went wrong')]);
        }
    }
}
