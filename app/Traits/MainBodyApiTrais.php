<?php

namespace App\Traits;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

trait MainBodyApiTrais
{

    public function generateMainBodyToken(){

    try {
        $findKey =  Config::where('key','token_credentials')->first();
            if($findKey && $findKey->value){
                $credentials = json_decode($findKey->value);
                $username    = $credentials->username;
                $password    = $credentials->password;
                  throw new HttpResponseException(
                    new JsonResponse([
                        'status' => 422,
                        'message' => 'Token Generation failed',
                        'errors' => 'Keys not Available',
                    ], 422)
                );

            }else{
                throw new HttpResponseException(
                    new JsonResponse([
                        'status' => 422,
                        'message' => 'Token Generation failed',
                        'errors' => 'Keys not Available',
                    ], 422)
                );
            }

             $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'siteId' => env('SITE_ID'),
            'API-Key' => env('API_KEY'),
        ])->post('https://api.mindbodyonline.com/public/v6/usertoken/issue', [
            "Username" => $username,
            "Password" => $password
        ]);

        if ($response->successful()) {
            $data = $response->json();
        } else {
            return response()->json(['error' => 'Unable to issue user token'], $response->status());
        }
        } catch (HttpResponseException $e) {
    // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            // Handle any other exceptions
            throw new HttpResponseException(
                new JsonResponse([
                    'status' => 500,
                    'message' => 'An unexpected error occurred',
                    'errors' => $e->getMessage(),
                ], 500)
            );
        }


        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'siteId' => $request->siteId,
            'API-Key' => $request->api_key,
        ])->post('https://api.mindbodyonline.com/public/v6/usertoken/issue', [
            "Username" => $request->Username,
            "Password" => $request->Password
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Unable to issue user token'], $response->status());
        }
    }

    public function addClient($request){
            $this->generateMainBodyToken($request);
    }



}