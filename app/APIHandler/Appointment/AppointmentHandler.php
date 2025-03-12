<?php

namespace App\APIHandler\Appointment;

use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\APIHandler\Base;
use App\Models\Appointment;
use App\Models\ClientContract;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Firenchill\CentralHelper;
use App\Mail\BookAppointmentMail;
use App\Mail\UpdateAppointmentMail;
use Illuminate\Support\Facades\Mail;


class AppointmentHandler extends Base
{

    public static function getAppAvailableDate()
    {
        try {
            // Define the API token and other headers
            $mainbody_token =  Auth::user()->mainbody_token;
            $apiKey  = env('API_KEY');
            $siteId  = env('SITE_ID');
            $api_url = env('API_URL');
            // Simplified client data
            $headers = [
                'Accept' => 'application/json',
                'siteId' => $siteId,
                'authorization' => $mainbody_token,
                'API-Key' => $apiKey
            ];

            $today = Carbon::today();
            $endDate = $today->addDays(28)->format('m/d/Y');;

            $query = [
                'request.sessionTypeId' => 23,
                'request.endDate' => $endDate,
                'request.locationId' => 1,
                // 'request.startDate' => '2016-03-13T12:52:32.123Z',
            ];


            $response = Http::withHeaders($headers)->get($api_url . 'appointment/availabledates', $query);
            // Handle the response
            if ($response->successful()) {
                $res = $response->json();

                $availableDates = $res['AvailableDates'] ?? [];

                $separatedDates = self::separateDateAndTime($availableDates, $time = false);

                return self::response(self::OK, $separatedDates);
            } else {
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Failed to fetch available dates', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error($e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while fetching available dates');
        }
    }

    public static function convertDateFormat($date)
    {
        $changeDate = Carbon::createFromFormat('Y-m-d', $date);
        $changeDate->setTime(0, 0, 0);
        $newDate = $changeDate->format('Y-m-d\TH:i:s\Z');
        return $newDate;
    }

    public static function separateDateAndTime($availableDates, $time = false)
    {

        $separatedDates = [];
        if (!empty($availableDates) && is_array($availableDates)) {
            foreach ($availableDates as $dateTime) {
                // Split the date-time string into date and time components
                $dateTimeParts = explode('T', $dateTime);
                $date = $dateTimeParts[0];

                if ($time) {
                    $timeAndTimezone = explode('-', $dateTimeParts[1]);
                    $from = $timeAndTimezone[0];
                    $to = isset($timeAndTimezone[1]) ? $timeAndTimezone[1] : null;

                    $separatedDates[] = [
                        'from' => substr($from, 0, 5), // Extract time without milliseconds
                        'to' => $to,
                    ];
                } else {
                    $separatedDates[] = $date;
                }
            }
        }

        return $separatedDates;
    }

    public static function getAppBookableItems($request)
    {
        try {
            // Define the API token and other headers

            if (Auth::check() || Auth::guard('api')->check()) {
                $user = Auth::user() ?? Auth::guard('api')->user();
                $mainbody_token =  $user->mainbody_token;
            } elseif ($request['guest'] == 'true') {
                $mainbody_token = $request['token'];
            }

            // header with data
            $end_date = Carbon::now()->addMonth(5)->toDateString();
            $query = [
                'request.sessionTypeIds' => config('app.sessionTypeId'),
                'request.endDate' => $end_date,
                'request.locationIds' => config('app.locationIdTwo'),
                'request.staffIds' => config('app.staffId'),
            ];

            Log::Notice('Payload getAppBookableItems : '.json_encode($query ));

            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))
            ->get(CentralHelper::getApiUrl().'appointment/bookableitems',$query);
            Log::Info('response getAppBookableItems : '.json_encode($response ));

            // Handle the response
            if ($response->successful()) {
                $res = $response->json();

                $result = self::manageDateTime($res);
                // Log::Info('Response getAppBookableItems dates found successfully');
                return self::response(self::OK, $result);
            } else {
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Failed to fetch available dates', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while fetching available dates');
        }
    }



   public static function manageDateTime($res)
{
    // Define the time interval and get the start and end times from config
    $interval = 30; // 30-minute intervals
    $opening_time = config('app.opening_time', '06:00 AM'); // Starting time of the day (default '06:00 AM')
    $closing_time = config('app.closing_time', '09:30 PM'); // Ending time of the day (default '11:30 PM')

    // Step 1: Generate all possible time slots for the day from opening_time to closing_time
    $fullDaySlots = [];
    $start = strtotime($opening_time);
    $end = strtotime($closing_time);

    // Generate full day slots (from opening_time to closing_time)
    while ($start < $end) {
        $from = date('h:i A', $start);
        $next = strtotime('+30 minutes', $start);
        $to = date('h:i A', $next);
        $timeRange = "$from - $to";  // Format: 06:00 AM - 06:30 AM

        $fullDaySlots[] = $timeRange;

        $start = $next;
    }


    // Step 2: Add the availability data to the slots
    $result = [];

    // Loop through the availability data and set the availability for each slot
   foreach ($res['Availabilities'] as $availability) {
    $startDate = Carbon::parse($availability['StartDateTime'])->format('Y-m-d');
    $startTime = Carbon::parse($availability['StartDateTime']);
    $endTime = Carbon::parse($availability['EndDateTime']);

    // Ensure the date key exists in the result array
    if (!isset($result[$startDate])) {
        $result[$startDate] = [];
    }

    // Align start time to the nearest interval if it's off
    $startMinute = $startTime->minute % $interval;
    if ($startMinute !== 0) {
        $startTime->addMinutes($interval - $startMinute);
    }

    // Loop to fill the available slots for the specific date
    while ($startTime < $endTime) {
        $nextTime = $startTime->copy()->addMinutes($interval);

        // Ensure that the nextTime does not exceed the endTime
        if ($nextTime > $endTime) {
            break;
        }

        $timeRange = $startTime->format('h:i A') . ' - ' . $nextTime->format('h:i A');

        $result[$startDate][$timeRange] = 1; // Mark the slot as available
        $startTime = $nextTime;
    }
}
    // Step 3: Fill the full day slots with the availability data (0 for unavailable)
    $newAvailabilityArray = [];

    // Loop through each date in the availability data
    foreach ($result as $date => $slots) {

        // Initialize the date in the new availability array if not already set
        if (!isset($newAvailabilityArray[$date])) {
            $newAvailabilityArray[$date] = [];
        }

        // For each slot of the full day (from opening_time to closing_time)
        foreach ($fullDaySlots as $slot) {

            // If the slot exists in the availability data, keep the value as is
            if (isset($slots[$slot])) {
                $newAvailabilityArray[$date][$slot] = $slots[$slot];
            } else {
                // Otherwise, mark the slot as unavailable (0)
                $newAvailabilityArray[$date][$slot] = 0;
            }
        }

    }

    // Return the updated availability array with all the slots filled
    return $newAvailabilityArray;
}


    public static function getActiveSessionTimes()
    {

        try {
            // Define the API token and other headers
            $mainbody_token =  Auth::user()->mainbody_token;
            $apiKey  = env('API_KEY');
            $siteId  = env('SITE_ID');
            $api_url = env('API_URL');
            // header with data
            $headers = [
                'Accept' => 'application/json',
                'siteId' => $siteId,
                'authorization' => $mainbody_token,
                'API-Key' => $apiKey
            ];

            $query =  [
                // 'request.endTime' => '2016-03-13T12:52:32.123Z',
                'request.limit' => 62,
                'request.offset' => 100,
                'request.scheduleType' => 'Appointment',
                'request.sessionTypeIds[0]' => 23,
                'request.startTime' => '2024-07-31T20:30:00Z', // 8:30px
            ];

            $response = Http::withHeaders($headers)->get($api_url . 'appointment/activesessiontimes', $query);

            // Handle the response
            if ($response->successful()) {
                $res = $response->json();
                return self::response(self::OK, $res);
            } else {
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Failed to fetch available dates', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error($e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while fetching available dates');
        }
    }

    public static function bookAppointment($request, $singleSession = false)
    {
        try {
            // Define the API token and other headers
            if (Auth::check() || Auth::guard('api')->check()) {
                $user = Auth::user() ?? Auth::guard('api')->user();
                $mainbody_token =  $user->mainbody_token;
                $client_id =  $user->mainbody_id;
                $user_id  = $user->id;
            } elseif ($request['guest'] == 'true') {
                $mainbody_token =  $request['token'];
                $client_id =  $request['mainbody_id'];
                $user_id =  $request['id'];
            }

            $clientContract = '';
            if ($singleSession) {
                $newArray = [
                    'contract_id' => '',
                    'service_id' => $request['service_id'],
                ];
                $request->merge($newArray);
            } else {

                $clientContract = ClientContract::with(['contract'])->where('user_id', $user_id)
                    ->where('status', 1)->first();

                if ($clientContract) {
                    $newArray = [
                        'contract_id' => $clientContract->contract->id,
                        'service_id' => $clientContract->contract->service_id,
                        'client_contract_id' => $clientContract->client_contract_id
                    ];
                    $request->merge($newArray);
                } else {
                    return self::errorResponse(self::BAD_REQUEST, 'No contract exists against your profile');
                }
            }

            // header with data

            $requestBody = [
                'ApplyPayment' => true,
                'ClientId' => $client_id,
                'LocationId' => config('app.locationIdTwo'),
                'SessionTypeId' => config('app.sessionTypeId'),
                'StaffId' => config('app.staffId'),
                "StartDateTime" => $request['StartDateTime'],
                'ClientServiceId' => $request['ClientServiceId'] ?? null,
                "SendEmail" => false,
                //'service_id' =>  $request['service_id'],
                // "StartDateTime"=> "2024-07-18T08:30:00",
            ];


            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))->post(CentralHelper::getApiUrl() . 'appointment/addappointment', $requestBody);
            // Handle the response
            if ($response->successful()) {
                $appointmentData = $response->json();

                $app = self::createAppointment($request, $appointmentData, $user_id);

                if ($clientContract) {
                    self::updateClientContractSessionCount($clientContract);
                }
                if ($app) {
                    return self::response(self::OK, 'Appointment Created Successfully',$appointmentData);
                } else {
                    return self::errorResponse(self::BAD_REQUEST, 'Appointment Creation Failed');
                }
            } else {
                $err = $response->json();
                return self::errorResponse(self::BAD_REQUEST, @$err['Error']['Message']);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::Error($e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while booking appointment ' . $e->getMessage());
        }
    }

    public static function createAppointment($request, $appointmentData, $user_id)
    {
        try {
            $appointment = Appointment::create([
                'client_contract_id' => $request['client_contract_id'] ?? null,
                'user_id' => $user_id,
                'gender_preference' => $appointmentData['Appointment']['GenderPreference'],
                'duration' => $appointmentData['Appointment']['Duration'],
                'provider_id' => $appointmentData['Appointment']['ProviderId'],
                'appointment_id' => $appointmentData['Appointment']['Id'],
                'status' => $appointmentData['Appointment']['Status'],
                'start_date_time' => $appointmentData['Appointment']['StartDateTime'],
                'end_date_time' => $appointmentData['Appointment']['EndDateTime'],
                'notes' => $appointmentData['Appointment']['Notes'],
                'staff_requested' => $appointmentData['Appointment']['StaffRequested'],
                'program_id' => $appointmentData['Appointment']['ProgramId'],
                'session_type_id' => $appointmentData['Appointment']['SessionTypeId'],
                'location_id' => $appointmentData['Appointment']['LocationId'],
                'staff_id' => $appointmentData['Appointment']['StaffId'],
                'contract_id' => $request['contractId'] ?? null,
                'service_id' => $request['service_id'] ?? null,
                'online_description' => $appointmentData['Appointment']['OnlineDescription'],
            ]);

            Log::Info('Appointment Created : ' . $appointment);

            if ($appointment) {
                self::sendBookAppointmentEmail($appointment, $user_id);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::Error($e->getMessage());
            return false;
        }
    }

    public static function sendBookAppointmentEmail($appointment, $user_id, $update = false)
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
                if ($update) {
                    Mail::to($user->email)->send(new UpdateAppointmentMail($data));
                } else {
                    Mail::to($user->email)->send(new BookAppointmentMail($data));
                }
            } catch (\Exception $e) {
                Log::Error($e->getMessage());
            }
        }
    }


    public static function updateClientContractSessionCount($clientContract)
    {
        $session_count =  $clientContract->sessions;
        $clientContract->update(['sessions' => ($session_count + 1)]);
    }


    public static function updateAppointment($request)
    {
        try {
            // Define the API token and other headers
            $mainbody_token =  Auth::user()->mainbody_token;
            $client_id =  Auth::user()->mainbody_id;


                $payload = [
                    'AppointmentId' => $request['appointment_id'],
                    // 'Notes' => $request['notes'] ?? null,
                    'Test' => $request['test'] ?? false,
                    'StartDateTime' => $request['start_date_time'],
                    // 'EndDateTime' => $request['end_date_time'],
                ];

            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))
                ->post(CentralHelper::getApiUrl() . 'appointment/updateappointment', $payload);

            // Handle the response
            if ($response->successful()) {
                $appointmentData = $response->json();
                $app = $appointmentData['Appointment'];
                Appointment::where('appointment_id', $request['appointment_id'])->update(
                    [
                        'start_date_time' => $app['StartDateTime'],
                        'end_date_time' => $app['EndDateTime'],
                        'notes' => $app['Notes'],
                        'status' => $app['Status'],
                        'duration' => $app['Duration'],
                        'gender_preference' => $app['GenderPreference'],
                        'session_type_id' => $app['SessionTypeId'],
                        'staff_id' => $app['StaffId']
                    ]
                );
                $appointment =  Appointment::where('appointment_id', $request['appointment_id'])->first();
                if ($appointment) {
                    self::sendBookAppointmentEmail($appointment, $appointment->user_id, $update = true);
                }
                Log::Info('Update updateAppointment : ' . $appointment);
                return self::response(self::OK, $appointment);
            } else {
                return self::errorResponse(self::BAD_REQUEST, ['error' => 'Unfortunately! Failed to update appointment', 'details' => $response->json()]);
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while updating appointment details');
        }
    }


    public static function cencelAppointment($request)
    {
        try {
            // Define the API token and other headers
            $mainbody_token =  Auth::user()->mainbody_token;
            $client_id =  Auth::user()->mainbody_id;

            $payload = [
                'AppointmentId' => $request['appointment_id'],
                'Test' => $request['test'] ?? false,
                'Execute' => "cancel"
            ];

            Log::Info('Cancel Appointment : ' . json_encode($payload));

            $response = Http::withHeaders(CentralHelper::generatePostApiHeader($mainbody_token))
                        ->post(CentralHelper::getApiUrl() . 'appointment/updateappointment', $payload);
            // Handle the response
            if ($response->successful()) {

                $appointmentData = $response->json();
                Appointment::where('appointment_id', $request['appointment_id'])->delete();
                Log::Info('Cancel Appointment : app id'. $request['appointment_id'] );
                return self::successResponse(self::OK, 'Appointment Cancelled Successfully!');
            } else {
                return self::errorResponse(self::BAD_REQUEST, $response->json());
            }
        } catch (HttpResponseException $e) {
            // Re-throw the HttpResponseException to be handled elsewhere
            throw $e;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            // Handle any other exceptions
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'An unexpected error occurred while cancelling appointment details');
        }
    }
}
