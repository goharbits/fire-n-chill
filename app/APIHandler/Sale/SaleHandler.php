<?php

namespace App\APIHandler\Sale;

use App\Models\Config;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\APIHandler\Base;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientContract;
use App\Models\PurchaseService;
use App\Models\Appointment;
use App\Models\Contract;
use App\APIHandler\Appointment\AppointmentHandler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseContractMail;
use App\Mail\PurchaseServiceMail;
use App\Mail\BookAppointmentMail;
use Illuminate\Support\Facades\Log;
use App\Firenchill\CentralHelper;

class SaleHandler extends Base
{

    public static function salePurchaseContracts($request)
    {
        $start_date = '';
        $end_date = '';
        // $today = Carbon::now()->toDateTimeString();
        // replaced with selected date
        $today = Carbon::parse($request['StartDateTime']);
        try {
            // Define the API token and other headers
            $contract =  Contract::where('id', $request['contractId'])->first();
            if (!$contract) {
                return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'Contract Not Found');
            }

            if (Auth::check() || Auth::guard('api')->check()) {
                $user = Auth::user() ?? Auth::guard('api')->user();
                $mainbody_token = $user->mainbody_token;
                $client_id = $user->mainbody_id;
                $user_id = $user->id;
                ClientContract::where('user_id', $user_id)->update(['status' => 0]);
                $payload = [
                    "Test" => config('app.test_api'),
                    "LocationId" => config('app.locationIdOne'),
                    "ClientId" => $client_id,
                    "ContractId" => $contract->contract_id,
                    // 'StartDate'=> $request['StartDateTime'],
                    'StartDate' => $today,
                    "FirstPaymentOccurs" => "Instant",
                    "Type" => "StoredCard",
                    "SendNotifications" => false,
                    "StoredCardInfo" => [
                        "LastFour" => $request['LastFour'],
                    ]
                ];
            } else if ($request['guest'] == 'true') {
                $mainbody_token =  $request['token'];
                $client_id =  $request['mainbody_id'];
                $user_id =  $request['id'];
                $payload = [
                    "Test" => config('app.test_api'),
                    "LocationId" => config('app.locationIdOne'),
                    "ClientId" => $client_id,
                    "ContractId" => $contract->contract_id,
                    'StartDate' => $today,
                    "FirstPaymentOccurs" => "Instant",
                    "SendNotifications" => false,
                    "CreditCardInfo" => [
                        "CreditCardNumber" => $request['CreditCardNumber'],
                        "ExpMonth" => $request['ExpMonth'],
                        "ExpYear" => $request['ExpYear'],
                        "BillingName" => $request['CardHolder'],
                    ]
                ];
            }


            Log::Notice('Payload salePurchaseContracts : ' . json_encode($payload));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->post(CentralHelper::getApiUrl() . 'sale/purchasecontract', $payload);
            Log::Info('Response salePurchaseContracts : ' . $response);

            // Handle the response
            if ($response->successful()) {
                $responseData = $response->json();
                $start_date = $today;
                $end_date = $today->copy()->addMonth();
                $newArray = [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'contract_id' => $contract->id
                ];
                $request->merge($newArray);
                $clientContract = self::createClientContract($request, $responseData, $user_id);
                try {
                    $user = User::where('id', $user_id)->first();
                    Mail::to($user->email)->send(new PurchaseContractMail([
                        'full_name' => $user->first_name . " " . $user->last_name,
                        'email'     => $user->email,
                        'contractDetails' => $clientContract->contract
                    ]));
                } catch (\Exception $e) {
                    //    create log herer
                }
                Log::Info('Created salePurchaseContracts  : ' . @$clientContract);

                return self::successResponse(self::OK, 'Contract Purchased Successfully', $clientContract);
            } else {
                $res =  $response->json();
                Log::Error('Error salePurchaseContracts : ' . @$res['Error']['Message']);
                return self::errorResponse(self::BAD_REQUEST, 'Unfortunately! Unable to process payment with this card.');
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error salePurchaseContracts exception: ' . @$e);
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while purchasing Contract, ' . $e->getMessage());
        }
    }


    public static function updateSalePurchaseContracts($request)
    {
        try {
            $startDateTime = $request['StartDateTime'];

            $user = Auth::user() ?? Auth::guard('api')->user();
            $user_id = $user->id;
            // ->addDay()
            // $today = Carbon::now()->format('Y-m-d\TH:i:s');

            $clientContract =  ClientContract::with(['contract'])
                ->where('user_id', $user_id)
                ->orderBy('id', 'desc')
                ->where('status', 1)
                ->where('contract_id',1)
                ->first();

            if ($clientContract) {
                $startDateTime = Carbon::parse($request['StartDateTime']);
                $contractStartDate = Carbon::parse($clientContract->start_date);

                if ($startDateTime->lte($contractStartDate)) { // Use lt() for "less than" comparison
                     Log::Error('The contract update date and time must be later than the original contract purchase date and time.');
                    return self::errorResponse(
                        self::INTERNAL_SERVER_ERROR,
                        'The contract update date and time must be later than the original contract purchase date and time.'
                    );
                }

                $booked_session = $clientContract->sessions;
                $max_session = $clientContract->contract->sessions;
                $old_session_price = $clientContract->contract->price;

                $oldSessionPercentage =  $booked_session / $max_session * 100;
                $discountAmount       =  $oldSessionPercentage / 100 * $old_session_price;

                $newContract = Contract::where('id', $request['contractId'])->first();
                $newAmount   =   $oldSessionPercentage / 100 *  $newContract->price;
                $charged_amount = $newAmount - $discountAmount;
                $newArray  = [
                    'discount_amount' => $discountAmount,
                    'charged_amount'  => $charged_amount,
                    'client_contract_id' => $clientContract->client_contract_id,
                    'contract_old_id'=>$clientContract->contract->id,
                    'StartDateTime'=>$startDateTime,
                    'terminate'=>true
                ];
                $request->merge($newArray);

                $createContractResponse =  self::salePurchaseContracts($request);
                $contractResponse = json_decode($createContractResponse->getContent());
                if ($contractResponse->status != 200) {
                    return self::errorResponse(self::INTERNAL_SERVER_ERROR, $contractResponse->message);
                } else {
                    return self::terminateContract($request,$clientContract);
                }
                // $contractTerminate = self::terminateContract($request, $clientContract);
                // $terminateResponse = json_decode($contractTerminate->getContent());
                // if ($terminateResponse->status != 200) {
                //     return self::errorResponse(self::INTERNAL_SERVER_ERROR, $terminateResponse->message);
                // } else {
                //     return self::salePurchaseContracts($request);
                // }
            } else {
                return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'Contract not found');
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            // Handle any other exceptions
            Log::Error($e->getMessage());
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while terminating Contract' . $e->getMessage());
        }
    }

    public static function terminateContract($request, $clientContract)
    {

        try {
            $mainbody_token =  Auth::user() ? Auth::user()->mainbody_token : $request['mainbody_token'];
            $client_id =  Auth::user() ? Auth::user()->mainbody_id : $request['mainbody_id'];
            $payload = [
                'ClientId' => $client_id,
                'ClientContractId' => $clientContract['client_contract_id'],
                'TerminationDate' => $request['StartDateTime'],
                'TerminationComments' => $request['terminationComments'] ?? 'Client wants to update'
            ];

            Log::Notice('Payload terminateContract : ' . json_encode($payload));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->post(CentralHelper::getApiUrl() . 'client/terminatecontract', $payload);
            Log::Info('Response terminateContract : ' . $response);

            if ($response->successful()) {
                self::disableClientContractOld($request, $clientContract);
                Log::Info('Contract Terminated Successfully on Mindbodyy');
                return self::successResponse(self::OK, 'Contract Terminated Successfully');
            } else {
                $res = $response->json();
                Log::Error('Contract Terminated Error ' . @$res['Error']['Message']);
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Failed to fetch client details', 'details' => @$res['Error']['Message']]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error updateClientProfile : ' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while fetching available dates' . $e->getMessage());
        }
    }

    public static function disableClientContractOld($request, $clientContract)
    {
        $clientContract->update([
            'status' => 0,
            'termination_comment' => 'Contract Terminated',
            'termination_date' => $request['StartDateTime'],
        ]);

        if($clientContract){
            Log::Info('Contract Terminated Successfully locally');
        }
    }

    public static function createClientContract($request, $responseData, $user_id)
    {
        try {
            $clientContract = new ClientContract();
            $clientContract->user_id = $user_id;
            $clientContract->location_id = $responseData['LocationId'];
            $clientContract->contract_id = $request['contract_id'];
            $clientContract->sessions = 0;
            $clientContract->client_contract_id = $responseData['ClientContractId'];
            $clientContract->total = $responseData['Totals']['Total'];
            $clientContract->sub_total = $responseData['Totals']['SubTotal'];
            $clientContract->discount = $responseData['Totals']['Discount'];
            $clientContract->tax = $responseData['Totals']['Tax'];
            $clientContract->start_date = $request['start_date'];
            $clientContract->end_date = $request['end_date'];
            $clientContract->status = 1;
            $clientContract->payment_processing_failures = $responseData['PaymentProcessingFailures'];
            $clientContract->save();
            return $clientContract;
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error('Error createClientContract:' . $e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while purchasing service');
        }
    }

    public static function checkoutshoppingcart($request)
    {

        try {
            // Define the API token and other headers
            $service =  Service::where('id', $request['service_id'])->first();

            if (!$service) {
                return self::errorResponse(self::BAD_REQUEST, 'Unfortunately! Service Not Found ');
            }

            if (Auth::check() || Auth::guard('api')->check()) {

                $user = Auth::user() ?? Auth::guard('api')->user();
                $mainbody_token =  $user->mainbody_token;
                $client_id =  $user->mainbody_id;
                $user_id =  $user->id;

                $requestData = [
                        "ClientId" => $client_id,
                        "Test" => config('app.test_api'),
                        "Items" => [
                            [
                                "Item" => [
                                    "Type" => "Service",
                                    "Metadata" => [
                                        "ID" => $service->service_id ?? null
                                    ]
                                ],
                                "Quantity" => 1,
                                "AppointmentBookingRequests" => [
                                    [
                                        "ApplyPayment" => true,
                                        "StaffId" => config('app.staffId'),
                                        "LocationId" => config('app.locationIdTwo'),
                                        "SessionTypeId" => config('app.sessionTypeId'),
                                        "StartDateTime" => $request['StartDateTime'],
                                        "Resources" => []
                                    ]
                                ]
                            ]
                        ],
                        "InStore" => true,
                         "Payments" => [],
                        "SendEmail" => false,
                        "LocationId" => config('app.locationIdTwo')
                    ];
                    $payments = [];
                    if ($service->type == 'free') {

                        $payments[] = [
                            "Type" => 'Comp',
                            "Metadata" => [
                                "Amount" => $service->price ?? null
                            ]
                        ];
                    }else if($service->type !== 'free' && $request['payment_mode'] == 'by_card'){

                      $payments[] = [
                            "Type" => "StoredCard",
                                "Metadata" => [
                                    "Amount" => $service->price ?? null,
                                    "LastFour" => $request['LastFour'],
                                ]
                        ];

                    }else if($service->type !== 'free' && $request['payment_mode'] == 'by_person'){

                        $payments[] = [
                            "Type" => 'Comp',
                            "Metadata" => [
                                "Amount" => $service->price ?? null
                            ]
                        ];

                    }else{
                        $payments[] = [
                            "Type" => "StoredCard",
                                "Metadata" => [
                                    "Amount" => $service->price ?? null,
                                    "LastFour" => $request['LastFour'],
                                ]
                        ];
                    }

                    $requestData['Payments'] = $payments;

            } elseif ($request['guest'] == 'true') {

                $mainbody_token =  $request['token'];
                $client_id =  $request['mainbody_id'];
                $user_id =  $request['id'];
                $requestData = [
                    "ClientId" => $client_id,
                    "Test" => config('app.test_api'),
                    "Items" => [
                        [
                            "Item" => [
                                "Type" => "Service",
                                "Metadata" => [
                                    "ID" => $service->service_id ?? null
                                ]
                            ],
                            "Quantity" => 1,
                            "AppointmentBookingRequests" => [
                                [
                                    "ApplyPayment" => true,
                                    "StaffId" => config('app.staffId'),
                                    "LocationId" => config('app.locationIdTwo'),
                                    "SessionTypeId" => config('app.sessionTypeId'),
                                    "StartDateTime" => $request['StartDateTime'],
                                    "Resources" => []
                                ]
                            ]
                        ]
                    ],
                    "InStore" => true,
                    "Payments" => [],
                    "SendEmail" => false,
                    "LocationId" => config('app.locationIdTwo')
                ];

                $payments = [];

                if ($service->type == 'free') {

                    $payments[] = [
                        "Type" => 'Comp',
                        "Metadata" => [
                            "Amount" => $service->price ?? null
                        ]
                    ];
                }else if($service->type !== 'free' && $request['payment_mode'] == 'by_card'){

                    $payments[] = [
                        "Type" => 'CreditCard',
                        "Metadata" => [
                            "Amount" => $service->price ?? null,
                            "CreditCardNumber" => $request['CreditCardNumber'],
                            "ExpMonth" => $request['ExpMonth'],
                            "ExpYear" => $request['ExpYear'],
                            "BillingName" => $request['CardHolder']
                        ]
                    ];

                }else if($service->type !== 'free' && $request['payment_mode'] == 'by_person'){

                    $payments[] = [
                        "Type" => 'Comp',
                        "Metadata" => [
                            "Amount" => $service->price ?? null
                        ]
                    ];

                }else{
                    $payments[] = [
                        "Type" => 'CreditCard',
                        "Metadata" => [
                            "Amount" => $service->price ?? null,
                            "CreditCardNumber" => $request['CreditCardNumber'],
                            "ExpMonth" => $request['ExpMonth'],
                            "ExpYear" => $request['ExpYear'],
                            "BillingName" => $request['CardHolder']
                        ]
                    ];
                }

                $requestData['Payments'] = $payments;
            }


            Log::Notice('Payload checkoutshoppingcart : ' . json_encode($requestData));
            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))
                ->post(CentralHelper::getApiUrl() . 'sale/checkoutshoppingcart', $requestData);
            Log::Info('Response checkoutshoppingcart : ' . $response);

            if ($response->successful()) {
                $res = $response->json();
                if (isset($res['ShoppingCart']['Id'])) {

                    // self::sendServicePurchaseEmail($service,$user_id);
                    $newArray = [
                        'service_id' => $service->service_id,
                        'service_type'=>$service->type
                    ];

                    $request->merge($newArray);
                    $created = self::createAppointmentService($request, $res, $user_id);
                    if ($created) {
                        return self::response(self::OK, 'Appointment Booked Successfully');
                    }
                }
                return self::errorResponse(self::BAD_REQUEST, 'Service not purchased');
            } else {
                $res =  $response->json();
                Log::Error(@$res['Error']['Message']);
                // return self::errorResponse(self::BAD_REQUEST, 'Unfortunately! Unable to purchase single session ' . @$res['Error']['Message']);
                if($service->type == 'free'){
                    $message = 'Unfortunately! You can not book free session';
                }else{
                    $message = 'Unfortunately! Unable to process payment with this card.';
                }

                return self::errorResponse(self::BAD_REQUEST, $message);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error($e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while purchasing single session ' . $e->getMessage());
        }
    }

    public static function sendServicePurchaseEmail($service, $user_id)
    {
        if ($service) {
            try {
                $user = User::where('id', $user_id)->first();
                Mail::to($user->email)->send(new PurchaseServiceMail([
                    'full_name' => $user->first_name . " " . $user->last_name,
                    'email'     => $user->email,
                    'serviceDetails' => $service
                ]));
            } catch (\Exception $e) {
                //    create log herer
                Log::Error($e->getMessage());
            }
        }
    }

    public static function sendBookAppointmentEmail($appointment, $user_id)
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
                Mail::to($user->email)->send(new BookAppointmentMail($data));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                //    create log herer
            }
        }
    }

    public static function createAppointmentService($request, $appointmentData, $user_id)
    {
        try {

            if($request['service_type'] == 'free'){
                User::where('id',$user_id)
                ->update(['free_service_status' => 1]);
            }

            $appointment = Appointment::create([
                'user_id' => $user_id,
                'gender_preference' => @$appointmentData['Appointments'][0]['GenderPreference'],
                'duration' => @$appointmentData['Appointments'][0]['Duration'],
                'provider_id' => @$appointmentData['Appointments'][0]['ProviderId'],
                'appointment_id' => @$appointmentData['Appointments'][0]['Id'],
                'status' => @$appointmentData['Appointments'][0]['Status'],
                'start_date_time' => @$appointmentData['Appointments'][0]['StartDateTime'],
                'end_date_time' => @$appointmentData['Appointments'][0]['EndDateTime'],
                'notes' => @$appointmentData['Appointments'][0]['Notes'],
                'staff_requested' => @$appointmentData['Appointments'][0]['StaffRequested'],
                'program_id' => @$appointmentData['Appointments'][0]['ProgramId'],
                'session_type_id' => @$appointmentData['Appointments'][0]['SessionTypeId'],
                'location_id' => @$appointmentData['Appointments'][0]['LocationId'],
                'staff_id' => @$appointmentData['Appointments'][0]['StaffId'],
                'contract_id' => @$request['contract_id'] ?? null,
                'service_id' => @$request['service_id'] ?? null,
                'online_description' => @$appointmentData['Appointments'][0]['OnlineDescription'],
            ]);
            Log::Info('Response createAppointmentService : ' . $appointment);
            self::sendBookAppointmentEmail($appointment, $user_id);
            if ($appointment) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::Error('Error createAppointmentService : ' . $e->getMessage());
            return false;
        }
    }

}
