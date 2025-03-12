<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\AuthRequests\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Controllers\API\Auth\AuthController;
use Illuminate\Support\Facades\Validator;


class RegisteredUserController extends Controller
{

    public function __construct(
        private AuthController $authController
    ) {
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): mixed
    {
        try {
            // Validate the request using the validation class
            $validatedData = app(RegisterRequest::class);

            // Proceed with the registration logic
            $response = $this->authController->register($validatedData);
            $response = json_decode($response->getContent());

            if (!in_array($response->status, [self::OK, self::CREATED])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => $response->status,
                        'message' => $response->message,
                    ]);
                }

                return back()->withErrors(['message' => $response->message])->withInput();
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => $response->status,
                    'message' => $response->message,
                ]);
            }

            return redirect()->route('verification.otp');
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
}
