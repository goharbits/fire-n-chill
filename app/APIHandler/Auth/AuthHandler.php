<?php

namespace App\APIHandler\Auth;

use App\Models\Config;
use App\Models\User;
use App\Models\ClientContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\APIHandler\Base;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Firenchill\CentralHelper;
use Illuminate\Support\Str;

class AuthHandler extends Base
{

    public static function generateAuthToken()
    {
        $username = '';
        $password = '';
        try {

            $getEnvKey = CentralHelper::getEnvironmentKeys();

            if ($getEnvKey == 'staging_credentials') {
                $username    = config('app.sandbox_username');
                $password    = config('app.sandbox_password');
            } else if ($getEnvKey == 'production_credentials') {
                $username    = config('app.production_username');
                $password    = config('app.production_password');
            } else {
                return self::errorResponse(self::BAD_REQUEST, 'Keys not available to generate Token');
            }


            $response = Http::withHeaders(CentralHelper::generateApiHeaders())->post(CentralHelper::getApiUrl() . 'usertoken/issue', [
                "Username" => $username,
                "Password" => $password
            ]);

            Log::Info('Response: MindBody Token: ' . $response);
            if ($response->successful()) {
                $data = $response->json();
                return self::response(self::OK, $data);
            } else {
                $data = $response->json();
                return self::errorResponse(self::BAD_REQUEST, 'Token generation failed ' . @$data['Error']['Message']);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error: Token Generation Error: ' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'Some Error Occurred');
        }
    }

    public static function addClient($request, $tokenObj)
    {
        $isGuest = $request->input('guest', false);
        try {
            // Define the API token and other headers
            $token = '';
            if ($tokenObj && isset($tokenObj->data) && isset($tokenObj->data->AccessToken)) {
                $token = $tokenObj->data->AccessToken;
            }
            // Simplified client data

            $clientData = [
                'FirstName' => $request->input('first_name') ?? 'Guest',
                'LastName' => $request->input('last_name') ?? 'User',
                'Email' => $request->input('email'),
                'BirthDate' =>  $request->input('birth_date') ??  config('app.dob'),
                "SendScheduleEmails"=> false,
                "SendAccountEmails"=> false,
                "SendPromotionalEmails"=> false,
                "SendScheduleTexts"=>false,
                'SendAccountTexts'=>false,
                "SendPromotionalTexts"=>false,
                "SendEmail"=> false
            ];
            // Make the POST request using the Http facade
            Log::Notice('Payload Add Client: ' . json_encode($clientData));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($token))
                ->post(CentralHelper::getApiUrl() . 'client/addclient', $clientData);
            Log::Info('Response Add Client: ' . $response);
            // Handle the response
            if ($response->successful()) {
                $res = $response->json();
                $id = $res['Client']['UniqueId'] ?? '';
                $data =  ['id' => $id];
                return self::response(self::OK, $data);
            } else {
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Unable to add client ', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error Add Client: ' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while registering client ' . $e->getMessage());
        }
    }

    public static function checkIfGuestExists($request)
    {
        return User::where('email', $request['email'])
            ->where('guest_user', 1)
            ->first();
    }
    public static function checkUserExists($request)
    {
        return User::where('email', $request['email'])
            ->first();
    }
    public static function checkGuestExists($request)
    {
        return User::where('email', $request['email'])
            // ->where('guest_user',1)
            ->first();
    }
    public static function checkClientContract($user_id)
    {
        $today = Carbon::now()->toDateTimeString();
        return ClientContract::where('user_id', $user_id)
            ->where('end_date', '>', $today)
            ->where('status', 1)
            ->first();
    }


    public static function updateClientCard($request)
    {
        try {
            // Define the API token and other headers
            $user = Auth::user() ?? Auth::guard('api')->user();
            $mainbody_token =  $user->mainbody_token;
            $client_id =  $user->mainbody_id;
            // Simplified client data
            $payload = [
                "Client" => [
                    "Id" => $client_id,
                    "ClientCreditCard" => [
                        // "CardType" => $request['CardType'],
                        "CardNumber" => $request['CreditCardNumber'],
                        "ExpMonth" => $request['ExpMonth'],
                        "ExpYear" => $request['ExpYear'],
                        "CardHolder" => $request['CardHolder'],
                        "CVV" => $request['CVV']
                    ]
                ],
                "CrossRegionalUpdate" => false
            ];
            Log::Notice('Payload updateClientCard : ' . json_encode($payload));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->post(CentralHelper::getApiUrl() . 'client/updateclient', $payload);
            Log::Info('Reposnse updateClientCard : ' . $response);

            // Handle the response
            if ($response->successful()) {
                $responseData = $response->json();
                User::where('mainbody_id', $client_id)->update([
                    'card_details' => 1,
                    'last_four' => $responseData['Client']['ClientCreditCard']['LastFour'],
                    'card_type' => $responseData['Client']['ClientCreditCard']['CardType']
                ]);
                return self::response(self::OK, $responseData);
            } else {
                    $data = $response->json();
                    return self::errorResponse(self::BAD_REQUEST, 'Unfortunately! Unable to update card details ' . @$data['Error']['Message']);
                // return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Unable to update card details', 'details' => $data[]]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error updateClientCard : ' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while updating card detials' . $e->getMessage());
        }
    }


    public static function  deactiveClient($user)
    {
        try {
            // Define the API token and other headers

            $user = Auth::user() ?? Auth::guard('api')->user();
            $mainbody_token =  $user->mainbody_token;
            $client_id =  $user->mainbody_id;
            $email = Str::random(10) . '@deactive.com';
            // Simplified client data
            $payload = [
                "Client" => [
                    "Id" => $client_id,
                    "Email" => $email,
                    "BirthDate"=> "2018-08-20T07:01:40.826Z",
                    "FirstName" => "deactive",
                    "LastName" => "deactive",
                    "Active"=>false
                ],
                 "CrossRegionalUpdate" => false
            ];

            Log::Notice('Payload deactiveClient : ' . json_encode($payload));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->post(CentralHelper::getApiUrl() . 'client/updateclient', $payload);
            Log::Info('Reposnse deactiveClient : ' . $response);

            // Handle the response
            if ($response->successful()) {
                    $responseData = $response->json();

                    $user->update(['status' => 0,
                        'mainbody_id'=> null,
                        'mainbody_token'=>null,
                        'card_details'=>0,
                        'last_four'=>null,
                        'card_type'=>null,
                        'email_verified_at'=>null,
                        'device_token'=>null,
                        'birth_date'=>null,
                        'first_name' => 'Deactive',
                        'last_name' => 'Deactive',
                        'email' => $email,
                        'remember_mainbody_token' => null
                    ]);
                    return self::successResponse(self::OK, 'Client Deactivated Successfully!');
            } else {
                    $data = $response->json();
                    return self::errorResponse(self::BAD_REQUEST, 'Unfortunately! Unable to Deactivate client ' . @$data['Error']['Message']);
                // return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Unable to update card details', 'details' => $data[]]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error Deactivate client : ' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while Deactivating client' . $e->getMessage());
        }
    }

    public static function updateClientProfile($request)
    {
        try {
            // Define the API token and other headers
            $user = Auth::user() ?? Auth::guard('api')->user();
            $mainbody_token =  $user->mainbody_token;
            $client_id =  $user->mainbody_id;
            // Simplified client data

            $payload = [
                "Client" => [
                    "Id" => $client_id,
                    // "BirthDate" => $request['birth_date'],
                    "FirstName" => $request['first_name'],
                    "LastName" => $request['last_name']
                ],
                "Test" => config('app.test_api'),
                "CrossRegionalUpdate" => false
            ];

            Log::Notice('Payload updateClientProfile : ' . json_encode($payload));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->post(CentralHelper::getApiUrl() . 'client/updateclient', $payload);
            // Handle the response
            Log::Info('Response updateClientProfile : ' . $response);

            if ($response->successful()) {
                $responseData = $response->json();
                User::where('mainbody_id', $client_id)->update([
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    // 'birth_date' => $request['birth_date']
                ]);
                return self::response(self::OK, $responseData);
            } else {
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Unable to update User. ', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error updateClientProfile : ' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while updating card detials' . $e->getMessage());
        }
    }
}
