<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Controllers\API\Auth\AuthController;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{

    public function __construct(
        private AuthController $authController
    ) {
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): mixed
    {
        try {
            // Validate the request using the validation class
            $validatedData = app(LoginRequest::class);
            // Proceed with the login logic
            $response = $this->authController->login($validatedData);
            $response = json_decode($response->getContent());

            if (!in_array($response->status, [self::OK, self::CREATED])) {

                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => $response->status,
                        'message' => $response->message,
                    ]);
                }

                return back()->withErrors(['message' => $response->message, 'error_code' => $response->status])->withInput();
            }

            // Authenticate the user
            $user = $response->data->user;
            $user = User::find($user->id);
            Auth::login($user);

            // Return JSON response or redirect
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => $response->status,
                    'message' => $response->message,
                    'redirect_url' => route('dashboard'),
                ]);
            }

            return redirect()->route('dashboard');
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            // Extract validation errors from the exception
            $errors = json_decode($e->getResponse()->getContent(), true)['errors'];

            // Redirect back with validation errors for HTML requests
            if (!$request->expectsJson()) {
                return back()->withErrors($errors)->withInput();
            }

            // Return JSON response for API calls
            return response()->json([
                'status' => false,
                'errors' => $errors,
            ], 422);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
