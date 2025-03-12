<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {


        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA.',
        ]);

        if ($validator->fails()) {
           if ($request->expectsJson()) {
                return response()->json([
                        'status' => 422,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors(),
                    ], 422);
            }else{
                 return back()->withErrors(['captcha' => 'Please complete the reCAPTCHA']);

            }
        }


        $recaptchaResponse = $request->input('g-recaptcha-response');


        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $recaptchaResponse,
        ]);

        $responseBody = $response->json();

        if (!$responseBody['success']) {

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'reCAPTCHA validation failed.',
                ], 422);
            } else {
                 return back()->withErrors(['captcha' => 'reCAPTCHA validation failed.']);
            }

        }

        return $next($request);
    }
}
