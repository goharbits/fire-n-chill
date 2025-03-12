<?php

namespace App\APIHandler\Client;

use App\Models\Config;
use App\Models\Contract;
use App\Models\Service;
use App\Models\User;
use App\Models\Appointment;
use App\Models\ClientContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\APIHandler\Base;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Firenchill\CentralHelper;
use App\Mail\CancelAppointmentMail;

class ClientHandler extends Base
{

    public static function getClientCompleteInfo($request)
    {
        try {
            $mainbody_token =  Auth::user()->mainbody_token;
            $client_id =  Auth::user()->mainbody_id;

            $query = [
                'request.clientId' => $client_id,
                // 'request.clientAssociatedSitesOffset' => '146',
                // 'request.crossRegionalLookup' => 'false',
                // 'request.endDate' => '2016-03-13T12:52:32.123Z',
                // 'request.excludeInactiveSites' => 'false',
                // 'request.showActiveOnly' => 'false',
                // 'request.startDate' => '2016-03-13T12:52:32.123Z',
                // 'request.useActivateDate' => 'false'
            ];
            Log::Notice('Payload getClientCompleteInfo : ' . json_encode($query));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->get(CentralHelper::getApiUrl() . 'client/clientcompleteinfo', $query);
            Log::Info('Response getClientCompleteInfo : Not showing due to security reason');

            if ($response->successful()) {
                $res = $response->json();
                return self::response(self::OK, $res);
            } else {
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Failed to fetch client details', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error getClientCompleteInfo Exception: ' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while fetching available dates');
        }
    }

    public static function getClientschedule($request)
    {
        try {
            $mainbody_token =  Auth::user()->mainbody_token;
            $client_id =  Auth::user()->mainbody_id;
            $user_id =  Auth::user()->id;
            $today = Carbon::today();
            $startDate = clone $today;

            $startDateFormatted = $startDate->format('Y-m-d');
            $endDate = $today->addMonths(12);
            $endDateFormatted = $endDate->format('Y-m-d');

            $query = [
                'request.clientId' => $client_id,
                'request.startDate' => $startDateFormatted,
                'request.endDate' => $endDateFormatted,
            ];
            Log::Notice('Payload getClientschedule : ' . json_encode($query));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))
                ->get(CentralHelper::getApiUrl() . 'client/clientschedule', $query);

            if ($response->successful()) {
                $res = $response->json();
                $services = collect($res['Visits']);
                $statusMap = $services->mapWithKeys(function ($item) {
                    return [
                        $item['AppointmentId'] => [
                            'AppointmentStatus' => $item['AppointmentStatus'],
                            'StartDateTime' => $item['StartDateTime'],
                            'EndDateTime' => $item['EndDateTime'],
                        ],
                    ];
                });

                //  remove the appointment if appointment removed from mindbody
                $appointments =  Appointment::where('user_id', $user_id)->get();
                foreach ($appointments as $appointment) {
                    // Check if the current appointment exists in the status map
                    if ($statusMap->has($appointment->appointment_id)) {
                        // Get the corresponding data from the map
                        $appointmentData = $statusMap[$appointment->appointment_id];
                        // Update the appointment status in the database
                        $appointment->status = $appointmentData['AppointmentStatus'];
                        $appointment->save();
                    } else {
                        try {
                            // self::sendCancelAppointmentEmail($appointment, $appointment->user_id);
                        } catch (\Exception $e) {
                            Log::Error('Error at sendCancelAppointmentEmail ' . $e->getMessage());
                        }
                        $appointment->delete();
                    }
                }

                $filteredSchedules = $services->filter(function ($service) {
                    return $service['AppointmentStatus'] === 'Booked' || $service['AppointmentStatus'] === 'Confirmed';
                })->map(function ($service) {
                    return [
                        'id' => $service['AppointmentId'],
                        'start_date_time' => $service['StartDateTime'],
                        'end_date_time' => $service['EndDateTime'],
                        'appointmentStatus' => $service['AppointmentStatus'],
                        'name' => $service['Name'],
                        'serviceName' => $service['ServiceName'],
                    ];
                })->values();
                Log::Info('Response getClientschedule: ' . $filteredSchedules);
                return self::response(self::OK, $filteredSchedules);
            } else {
                Log::Error('Error getClientschedule : ' . $response->json());
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Failed to fetch client details', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error getClientschedule Exception: ' . $e->getMessage());

            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while fetching available dates');
        }
    }

    public static function sendCancelAppointmentEmail($appointment, $user_id)
    {
        if ($appointment) {
            try {
                $user =  User::where('id', $user_id)->first();
                $data =
                    [
                        'full_name' => $user->first_name . " " . $user->last_name,
                        'email'     => $user->email,
                        'start_date_time' => $appointment->start_date_time,
                        'end_date_time' => $appointment->end_date_time
                    ];

                Mail::to($user->email)->send(new CancelAppointmentMail($data));
            } catch (\Exception $e) {
                Log::Error($e->getMessage());
            }
        }
    }

    public static function getClientVisits($months = false)
    {
        try {
            $user = Auth::check() ? Auth::user() : Auth::guard('api')->user();
            $mainbody_token =  $user->mainbody_token;
            $client_id =  $user->mainbody_id;

            $months = $months ? $months : 3;
            $today = Carbon::today();
            $startDate = (clone $today)->subMonths($months);
            $startDateFormatted = $startDate->format('Y-m-d');
            $endDate = clone $today;
            $endDateFormatted = $endDate->format('Y-m-d');
            $query = [
                'request.clientId' => $client_id,
                'request.startDate' => '2024-01-01',
                // 'request.endDate' => $endDateFormatted,
                'request.endDate' => '2025-09-26',
            ];
            Log::Notice('Payload getClientVisits : ' . json_encode($query));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->get(CentralHelper::getApiUrl() . 'client/clientvisits', $query);

            if ($response->successful()) {
                $res = $response->json();

                $services = collect($res['Visits']);

                $filteredVisits = $services->filter(function ($service) {
                    return $service['AppointmentStatus'] === 'Arrived' || $service['AppointmentStatus'] === 'Completed';
                })->map(function ($service) {
                    return [
                        'id' => $service['AppointmentId'],
                        'start_date_time' => $service['StartDateTime'],
                        'end_date_time' => $service['EndDateTime'],
                        'appointmentStatus' => $service['AppointmentStatus'],
                        'name' => $service['Name'],
                        'serviceName' => $service['ServiceName'],
                    ];
                })->values();

                Log::Info('Response getClientVisits : ' . $filteredVisits);
                return self::response(self::OK, $filteredVisits);
            } else {
                Log::Error('Error getClientVisits 33: ' . @$response->json());
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Failed to fetch client details', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error getClientVisits 55: ' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while fetching available dates');
        }
    }

    //  public static function terminateContract($request){
    //     try {
    //         $mainbody_token =  Auth::user()->mainbody_token;
    //         $client_id =  Auth::user()->mainbody_id;
    //         $apiKey  = env('API_KEY');
    //         $siteId  = env('SITE_ID');
    //         $api_url = env('API_URL');
    //          // header with data
    //         $headers = [
    //             'Accept' => 'application/json',
    //             'siteId' => $siteId,
    //             'authorization' => $mainbody_token,
    //             'API-Key' => $apiKey
    //         ];

    //         $payload = [
    //             'ClientId' => $client_id,
    //             'ClientContractId' => $request['ClientContractId'],
    //             'TerminationDate' => $request['TerminationDate'],
    //             'TerminationComments' => $request['TerminationComments']
    //             // 'TerminationDate' => '2024-07-20T12:52:32Z',
    //         ];
    //         $response = Http::withHeaders($headers)->post($api_url.'client/terminatecontract',$payload);

    //         if ($response->successful()) {
    //              $res = $response->json();
    //             return self::response(self::OK, $res);

    //          } else {
    //             return self::errorResponse(self::BAD_REQUEST, ['error'=>'Unfortunately! Failed to fetch client details','details' => $response->json()]);
    //         }
    //    } catch (HttpResponseException $e) {
    //             // Re-throw the HttpResponseException to be handled elsewhere
    //         throw $e;
    //     } catch (\Exception $e) {
    //         Log::error($e->getMessage());
    //         // Handle any other exceptions
    //         return self::errorResponse(self::INTERNAL_SERVER_ERROR,'An unexpected error occurred while fetching available dates' );
    //     }
    // }

    public static function getClientServices($request)
    {

        try {

            $matchContract = '';
            $client_id = '';

            if (Auth::check() || Auth::guard('api')->check()) {


                $user = Auth::check() ? Auth::user() : Auth::guard('api')->user();
                $mainbody_token =  $user->mainbody_token;
                $client_id =  $user->mainbody_id;
                $user_id =  $user->id;
            } elseif ($request['guest'] == 'true') {


                if (!isset($request['clientId'])) {
                    $data =  self::getStaticContractsService($user_id = false);
                    return self::response(self::OK, $data);
                }

                $client_id = $request['clientId'];
                $mainbody_token = $request['token'];
                $user_id =  $request['id'];
            }

            $data = [
                "ClientId" => $client_id,
                'showActiveOnly' => true,
                'useActivateDate' => true
            ];

            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->get(CentralHelper::getApiUrl() . 'client/clientservices', $data);

            if ($response->successful()) {
                $res = $response->json();

                $servicesArray = self::checkServicesStatus($res);

                if (count($servicesArray) > 0) {
                    switch ($servicesArray['Name']) {
                        case config('app.peak_vitality_plan'):
                            // Handle 'Peak' case
                            $findContract = self::getContractByName($servicesArray['Name'], $user_id);
                            break;
                        case config('app.super_vitality_plan'):
                            // Handle 'Peak' case
                            $findContract = self::getContractByName($servicesArray['Name'], $user_id);
                            break;
                    }

                    if ($findContract) {
                        $carbonDate = Carbon::parse($request['StartDateTime']);
                        try {
                            if (isset($findContract->clientContracts) && $findContract->clientContracts !== null) {
                                //    start contract again
                                // $findContract->clientContracts->status = 1;
                                // $findContract->clientContracts->save();
                            }
                        } catch (\Exception $e) {
                            Log::Error('Error ' . $e);
                        }

                        $booking_msg = '';
                        //  check if the coming date is smaller and greater then the contract
                        // date that is continue. show another screen with msg
                        $activeDateTime =  date('Y-m-d', strtotime($servicesArray['ActiveDate']));
                        $expDateTime =  date('Y-m-d', strtotime($servicesArray['ExpirationDate']));

                        if ($activeDateTime > $carbonDate || $expDateTime < $carbonDate) {

                            $data = [
                                'show_payment_screen' => false,
                                'contract_id'=> $findContract->id,
                                'contract'=> $findContract,
                                'contract_service_id' => $servicesArray['Id'],
                                'max_week_sessions'=> 0,
                                'booked_current_week_session'=> 0,
                                'message'=>'You already have an active Contract from '. $activeDateTime .' to '. $expDateTime. '.',
                            ];
                        } else {

                            if (
                                isset($findContract->clientContracts) && $findContract->clientContracts !== null &&
                                $findContract->clientContracts->end_date < $carbonDate
                            ) {

                                $data = [
                                    'show_payment_screen' => false,
                                    'contract_id' => $findContract->id,
                                    'contract' => $findContract,
                                    'contract_service_id' => $servicesArray['Id'],
                                    'max_week_sessions' => $findContract->weekly_sessions,
                                    'booked_current_week_session' => $findContract->weekly_sessions,
                                    'message'=>''
                                ];
                            } else {

                                $startOfWeek = (clone $carbonDate)->startOfWeek(Carbon::SUNDAY); // Start of this week
                                $endOfWeek = (clone $carbonDate)->endOfWeek(Carbon::SATURDAY); // End of this week
                                $appointmentCount = Appointment::where('contract_id', $findContract->id)
                                    ->where('user_id', $user_id)
                                    ->where('client_contract_id', $findContract->clientContracts->client_contract_id)
                                    ->whereBetween('start_date_time', [$startOfWeek, $endOfWeek])
                                    ->count();

                                if($findContract->id == 2 ){
                                    $booking_msg = 'You have unlimited sessions with this plan.';
                                }else{
                                    $booking_msg = '';
                                }
                                $data = [
                                    'show_payment_screen' => false,
                                    'contract_id' => $findContract->id,
                                    'contract' => $findContract,
                                    'contract_service_id' => $servicesArray['Id'],
                                    'max_week_sessions' => $findContract->weekly_sessions,
                                    'booked_current_week_session' => $appointmentCount,
                                    'message' => $booking_msg
                                ];
                            }
                        }
                    } else {
                        return self::errorResponse(self::BAD_REQUEST, 'Unfortunately! No Data Found ');
                    }
                } else {
                    $data =  self::getStaticContractsService($user_id);
                }
                return self::response(self::OK, $data);
            } else {
                return self::errorResponse(
                    self::BAD_REQUEST,
                    'Unfortunately! Failed to fetch client details. ' . $response->json()['Error']['Message']
                );
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred Client Status ' . $e->getMessage());
        }
    }

    public static function getContractByName($name, $user_id)
    {
        if (config('app.environment') == 'production') {

            $peakVitalityPlan = strtolower(config('app.peak_vitality_plan'));
            $superVitalityPlan = strtolower(config('app.super_vitality_plan'));
            // Perform a case-insensitive partial match
            if (str_contains($peakVitalityPlan, strtolower($name))) {
                return self::getUserClientContractByCID($cid = 1, $name, $user_id);
            } elseif (str_contains($superVitalityPlan, strtolower($name))) {
                return self::getUserClientContractByCID($cid = 2, $name, $user_id);
            } else {
                return null;
            }
        } else {

            if (strpos($name, 'Peak Super') != false) {
                // Remove "peak " from the string
                $name = str_replace('Peak ', '', $name);
            }
            return self::getUserClientContract($name, $user_id);
        }
    }

    public static function getUserClientContractByCID($cid, $name, $user_id)
    {
        return  Contract::where('id', $cid)
            ->with(['clientContracts' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            }])
            ->first();
    }

    public static function getUserClientContract($name, $user_id)
    {
        return Contract::where('name', 'like', '%' . $name . '%')
            ->with(['clientContracts' => function ($query) use ($user_id) {
                // Apply a condition to filter by user_id
                $query->where('user_id', $user_id);
            }])
            ->first();
    }

    public static function getStaticContractsService($user_id)
    {
        if ($user_id) {
            ClientContract::where('user_id', $user_id)->update(['status' => 0]);
        }
        $service =  Service::where('id',2)->where('status', 1)->first();
        $free_service =  Service::where('id',1)->where('status', 1)->first();
        $contracts =  Contract::where('status', 1)->take(2)->get();
        // Map the contracts to the desired structure
        $contractsArray = $contracts->map(function ($contract) {
            $per_session_price = $contract->price / $contract->sessions;
            $description = '';
            if($contract->id == 1){
                $description = $contract->weekly_sessions . ' Sessions / week,$' . @$per_session_price . ' per session';
            }else{
                $description = 'Unlimited Sessions ,<$10 per session';
            }
            return [
                'id' => $contract->id,
                'contract_id' => $contract->contract_id,
                'sessions' => $contract->sessions,
                'weekly_sessions' => $contract->weekly_sessions,
                // 'per_session_price' => round($per_session_price),
                'per_session_price' => $per_session_price,
                'name' => $contract->name,
                'price' => $contract->price,
                'description' => $description,
            ];
        })->toArray();

        // Prepare the response data
        $data = [
            'free_service' => [
                'name' => $free_service->name,
                'price' => $free_service->price,
                'description' => $free_service->description,
                'service_id' => $free_service->id,
                'description_part'=>'30 minute Sauna + Cold Plunge'
            ],
            'show_payment_screen' => true,
            'contracts' => $contractsArray,
            'service' => [
                'name' => $service->name,
                'price' => $service->price,
                'description' => $service->description,
                'service_id' => $service->id,
                'description_part'=>'30 minute Sauna + Cold Plunge'
            ]
        ];
        return $data;
    }

    public static function checkServicesStatus($res)
    {
        $services = $res['ClientServices'];

        $today = Carbon::now()->format('Y-m-d\TH:i:s');

        // check only name

        $filteredServices = array_filter($services, function ($service) use ($today) {
            $name = $service['Name'];
            $remaining = $service['Remaining'];
            $isCurrent = $service['Current'];
            return $isCurrent &&
                ($name == config('app.peak_vitality_plan') || $name == config('app.super_vitality_plan')) &&
                Carbon::parse($service['ExpirationDate'])->greaterThan($today) && $remaining > 0;
        });


        $result = array_map(function ($service) {
            return [
                'Id' => $service['Id'],
                'Name' => $service['Name'],
                'Remaining' => $service['Remaining'],
                'ActiveDate' => $service['ActiveDate'],
                'ExpirationDate' => $service['ExpirationDate'],
            ];
        }, $filteredServices);


        $result = array_values($result);
        return $result[0] ?? [];
    }

    public static function getClientUpgradeableService($request)
    {
        Log::info('Client UpgradeableService');
        $user_id =  Auth::user()->id;
        $contract = '';
        $per_session_price = 0;
        $contractData = null;
        $carbonDate = $request['StartDateTime'];
        $clientContract =  ClientContract::where('user_id', $user_id)
            ->where('status', 1)->first();
        if ($clientContract) {
            if ($clientContract->contract_id == 1) {
                $contractEndDate = $clientContract->end_date;
                $contractstartDate = $clientContract->start_date;
                if ($contractEndDate > $carbonDate && $contractstartDate < $carbonDate) {
                    $contract =  Contract::where('id', 2)->first();
                    $per_session_price = $contract->price / $contract->sessions;
                    $contractData = [
                        'id' => $contract->id,
                        'contract_id' => $contract->contract_id,
                        'sessions' => $contract->sessions,
                        'weekly_sessions' => $contract->weekly_sessions,
                        'per_session_price' => round($per_session_price),
                        'name' => $contract->name,
                        'price' => $contract->price,
                        'description' => @$contract->weekly_sessions . ' Sessions/week,$' . round(@$per_session_price) . ' per seesion',
                    ];
                }
            }
        }
        $service = Service::where('id',2)->where('status', 1)->first();

        $data = [
            'contract' => $contractData,
            'service' => [
                'name' => $service->name,
                'price' => $service->price,
                'description' => $service->description,
                'service_id' => $service->id
            ]
        ];

        return self::response(self::OK, $data);
    }

    // public static function activeSubscriptionWebhook($request){

    //             $query =  [
    //                 'eventIds' => [
    //                     'clientContract.created',
    //                 ],
    //                 'eventSchemaVersion'=> 1,
    //                 'webhookUrl' => config('app.url') . '/webhook-response',

    //             ];
    //             $response = Http::withHeaders(CentralHelper::generateApiHeaders())->post('https://push-api.mindbodyonline.com/api/v1/subscriptions/',$query);

    //             if ($response->successful()) {
    //                 // Handle the success case
    //                 $data = $response->json();
    //                 echo $data ;
    //                 // Do something with $data
    //             } else {
    //                 // Handle the error case
    //                 $error = $response->body();
    //                 echo 'error'.$error;
    //                 // Log or display the error
    //             }

    //             $query =  [
    //                 'eventIds' => [
    //                     'appointmentBooking.created',
    //                 ],
    //                 'eventSchemaVersion'=> 1,
    //                 'webhookUrl' => config('app.url') . '/webhook-appointment-created',

    //             ];
    //             $response = Http::withHeaders(CentralHelper::generateApiHeaders())->post('https://push-api.mindbodyonline.com/api/v1/subscriptions/',$query);

    //             if ($response->successful()) {
    //                 // Handle the success case
    //                 $data = $response->json();
    //                 echo $data ;
    //                 // Do something with $data
    //             } else {
    //                 // Handle the error case
    //                 $error = $response->body();
    //                 echo 'error'.$error;
    //                 // Log or display the error
    //             }

    //              $query =  [
    //                 'eventIds' => [
    //                     'appointmentBooking.update',
    //                 ],
    //                 'eventSchemaVersion'=> 1,
    //                 'webhookUrl' => config('app.url') . '/webhook-appointment-update',

    //             ];
    //             $response = Http::withHeaders(CentralHelper::generateApiHeaders())->post('https://push-api.mindbodyonline.com/api/v1/subscriptions/',$query);

    //             if ($response->successful()) {
    //                 // Handle the success case
    //                 $data = $response->json();
    //                 echo $data ;
    //                 // Do something with $data
    //             } else {
    //                 // Handle the error case
    //                 $error = $response->body();
    //                 echo 'error'.$error;
    //                 // Log or display the error
    //             }


    // }


public static function activeSubscriptionWebhook($request)
{
    // Define an array of event subscription configurations
    $subscriptions = [
        [
            'eventIds' => ['clientContract.created'],
            'webhookUrl' => config('app.url') . '/webhook-contract-created',
        ],
        [
            'eventIds' => ['appointmentBooking.created'],
            'webhookUrl' => config('app.url') . '/webhook-appointment-created',
        ],
        [
            'eventIds' => ['appointmentBooking.updated'],
            'webhookUrl' => config('app.url') . '/webhook-appointment-updated',
        ]
    ];

    // Loop through each subscription and make API calls
    foreach ($subscriptions as $subscription) {
        $query = [
            'eventIds' => $subscription['eventIds'],
            'eventSchemaVersion' => 1,
            'webhookUrl' => $subscription['webhookUrl'],
        ];

        try {
            // Call the helper function to make the API call
            $response = self::makeSubscriptionRequest($query);

            if ($response->successful()) {
                // Handle the success case
                $data = $response->json();
                $sub_id = $data['SubscriptionId'];
                \Log::info('Webhook subscription applying', ['data' => $data]);
                $query['status'] = 'Active';
                $subscriptionActive = self::makeSubscriptionActive($query,$sub_id);
                $subscriptionActive = $subscriptionActive->json();
                \Log::info('Webhook subscription Active', ['data' => $subscriptionActive]);
            } else {
                // Handle the error case
                $error = $response->body();
                \Log::error('Webhook subscription failed', ['error' => $error]);
            }
        } catch (\Exception $e) {
            // Handle unexpected exceptions
            \Log::error('Exception during subscription request', ['exception' => $e->getMessage()]);
        }
    }

    return self::response(self::OK);
}

private static function makeSubscriptionActive($query,$sub_id){

    $url =  'https://push-api.mindbodyonline.com/api/v1/subscriptions/'. $sub_id;

    // A helper function to send the HTTP request
    return Http::withHeaders(CentralHelper::generateApiHeaders())
        ->patch($url,$query);

}

private static function makeSubscriptionRequest($query)
{
    // A helper function to send the HTTP request
    return Http::withHeaders(CentralHelper::generateApiHeaders())
        ->post('https://push-api.mindbodyonline.com/api/v1/subscriptions/', $query);
}

}
