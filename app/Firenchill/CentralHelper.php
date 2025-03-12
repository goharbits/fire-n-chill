<?php

namespace App\Firenchill;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Loyalty;
use Illuminate\Support\Facades\DB;
use App\APIHandler\Client\ClientHandler;
use Carbon\Carbon;

class CentralHelper
{
    public function __construct(
        private Appointment $appointment,
    ) {}
    public static function generateNumericOTP($length = 6)
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= mt_rand(0, 9);
        }
        return $otp;
    }

    public static function getEnvironmentKeys()
    {
        $value = '';
        $environment =  config('app.environment');
        if ($environment == 'production') {
            $value = 'production_credentials';
        } else {
            $value = 'staging_credentials';
        }
        return $value;
    }

    public static function getLoyalty()
    {
        //  $loyaltyLevel = Loyalty::where('level', 12)->first();

        //  return [
        //             'status' => 200,
        //             'message' => 'Loyalty level retrieved successfully',
        //             'data' => $loyaltyLevel,
        //             'level' => $loyaltyLevel->level
        //         ];

        $loyaltyLevel = '';
        if (Auth::check() || Auth::guard('api')->check()) {
            $user = Auth::user() ?? Auth::guard('api')->user();
            $userId = $user->id;
            // Retrieve the loyalty levels
            $loyaltyLevels = Loyalty::orderBy('level', 'asc')->get();
            // Retrieve the first appointment date for the user

            $firstAppointment = Appointment::where('user_id', $userId)
                // ->where('status','Booked')
                ->first();
            if (!$firstAppointment) {
                return [
                    'status' => 204,
                    'message' => 'No appointments found for this user.',
                    'data' => [],
                ];
            }


            $response = ClientHandler::getClientVisits($months = 12);
            $response = json_decode($response->getContent());

            $data = self::calculateUserSessions($userId, $response);

            $totalSessions = $data['totalSessions'];
            $weeks = $data['weeks'];

            if ($weeks >= 13) {
                $appointments = collect($response->data);
                $currentWeekSessions = $appointments->filter(function ($appointment) {
                    // Convert appointment start_date_time to Carbon instance
                    $appointmentDate = Carbon::parse($appointment->start_date_time);
                    // Check if the appointment is in the current week
                    return $appointmentDate->isSameWeek(Carbon::now(), Carbon::MONDAY);
                })->count();


                if ($currentWeekSessions < 2) {
                    // use this if need to continue the per week sessions
                    // User does not meet the maintenance mode requirement; find the highest level they qualify for
                    // $loyaltyLevel = Loyalty::where('week', '<=', $weeks)
                    //     ->where('sessions', '<=', $totalSessions)
                    //     ->orderBy('level', 'desc')
                    //     ->first();
                    $loyaltyLevel = Loyalty::where('level', 12)->first();
                } else {
                    // User is in maintenance mode; return level 12 loyalty object
                    $loyaltyLevel = Loyalty::where('level', 12)->first();
                }
            } else {
                // User has not reached 13 weeks; find the highest level they qualify for
                $loyaltyLevel = Loyalty::where('week', '<=', $weeks)
                    ->where('sessions', '<=', $totalSessions)
                    ->orderBy('level', 'desc')
                    ->first();
            }

            if ($loyaltyLevel) {
                return [
                    'status' => 200,
                    'message' => 'Loyalty level retrieved successfully',
                    'data' => $loyaltyLevel,
                    'level' => $loyaltyLevel->level
                ];
            } else {
                return [
                    'status' => 204,
                    'message' => 'Loyalty level not Found',
                    'data' => $loyaltyLevel,
                    'level' => 0
                ];
            }
        } else {
            // Handle the case where the user is not authenticated
            return [
                'status' => 200,
                'message' => 'Loyalty level retrieved successfully',
                'data' => $loyaltyLevel,
                'level' => 0
            ];
        }
    }

    public static function calculateUserSessions($userId, $response)
    {
        // Fetch user's appointment data, grouped by week
        $weeks = 0;
        $totalSessions = 0;
        if (empty($response->data)) {
            return [
                'totalSessions' => $totalSessions,
                'weeks' => $weeks
            ];
        }

        $collection = collect($response->data);

        $groupedByWeek = $collection->groupBy(function ($appointment) {
            // Parse the date and get the week of the year, considering the year as well
            $date = Carbon::parse($appointment->start_date_time);
            return $date->copy()->startOfWeek()->format('Y-W');
        });

        // Step 2: Filter out weeks with less than two sessions
        $filteredWeeks = $groupedByWeek->filter(function ($weekGroup) {
            return $weekGroup->count() > 1; // Keep only weeks with more than one session
        });

        // Step 3: Count sessions per week, limiting to 2 per week
        $totalSessions = $filteredWeeks->sum(function ($weekGroup) {
            // Limit the count to a maximum of 2 sessions per week
            return min($weekGroup->count(), 2);
        });

        // Step 4: Calculate the number of weeks with more than one session
        $numberOfWeeksWithMultipleSessions = $filteredWeeks->count();

        // Step 5: Return the result as an array
        return [
            'totalSessions' => $totalSessions,
            'weeks' => $numberOfWeeksWithMultipleSessions
        ];



        // // Step 1: Group appointments by week
        // $groupedByWeek = $collection->groupBy(function ($appointment) {
        //     return Carbon::parse($appointment->start_date_time)->format('Y-W');
        // });

        // // Step 2: Filter out weeks with less than two sessions
        // $filteredWeeks = $groupedByWeek->filter(function ($weekGroup) {
        //     return $weekGroup->count() > 1; // Keep only weeks with more than one session
        // });

        // // Step 3: Count sessions per week, limiting to 2 per week
        // $totalSessions = $filteredWeeks->sum(function ($weekGroup) {
        //     // Limit the count to a maximum of 2 sessions per week
        //     return min($weekGroup->count(), 2);
        // });

        // // Step 4: Calculate the number of weeks with more than one session
        // $numberOfWeeksWithMultipleSessions = $filteredWeeks->count();

        // // Step 5: Return the result as an array
        // return [
        //     'totalSessions' => $totalSessions,
        //     'weeks' => $numberOfWeeksWithMultipleSessions
        // ];


        //         // Assuming $collection is your collection instance
        //     $groupedByWeek = $collection->groupBy(function ($appointment) {
        //         return Carbon::parse($appointment->start_date_time)->format('Y-W');
        //     });
        //     // Filter to find weeks with more than one appointment
        //     $weeksWithMultipleSessions = $groupedByWeek->filter(function ($appointments) {
        //         return $appointments->count() > 1;
        //     });
        //     // Count the number of weeks that have more than one appointment
        //     $numberOfWeeksWithMultipleSessions = $weeksWithMultipleSessions->count();

        //     // Count sessions per week and limit to 2 per week
        //     $totalSessions = $groupedByWeek->sum(function ($weekGroup) {
        //         return min($weekGroup->count(), 2);
        //     });
        //     // Calculate the total number of weeks
        //     $weeks = $groupedByWeek->count();

        //     // Return the result as an array (or you could return a JSON response if needed)
        //    return [
        //         'totalSessions' => $totalSessions,
        //         'weeks' => $numberOfWeeksWithMultipleSessions
        //     ];

        // $appointments = Appointment::where('user_id', $userId)
        //     ->select(DB::raw('YEARWEEK(start_date_time, 1) as year_week'), DB::raw('COUNT(*) as session_count'))
        //     ->groupBy('year_week')
        //     ->havingRaw('COUNT(*) >= 2')
        //     ->where('status','Booked')
        //     ->get();
        // // Limit sessions to 2 per week
        // $totalSessions = $appointments->sum(function ($week) {
        //     return min($week->session_count, 2);
        // });

        // // Calculate the total number of weeks
        // $weeks = $appointments->count();

        //  // Check if the user is at level 8 (maintenance mode)

        // return [
        //     'totalSessions' => $totalSessions,
        //     'weeks' => $weeks
        // ];

    }

    public static function generateApiHeaders()
    {
        $value = '';
        $environment =  config('app.environment');
        if ($environment == 'production') {
            $value = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'siteId' => config('app.production_site_id'),
                'API-Key' => config('app.production_api_key')
            ];
        } else {
            $value =  [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'siteId' => config('app.sandbox_site_id'),
                'API-Key' => config('app.sandbox_api_key'),
            ];
        }
        return $value;
    }

    public static function getApiUrl()
    {
        $value = '';
        $environment = config('app.environment');

        if ($environment == 'production') {
            $value = config('app.production_api_url');
        } else {
            $value =  config('app.sandbox_api_url');
        }
        return $value;
    }

    public static function generatePostApiHeader($token)
    {

        $headers =  self::generateApiHeaders();
        if ($token) {
            $headers['Authorization'] = $token;
        }
        return $headers;
    }


    public static function getClinentLevel()
    {

        $user_id = Auth::user()->id;
        return  Appointment::where('user_id', $user_id)
            ->where('status', 'Booked')
            ->count();
    }

    public static function findClosestSession($loyalties, $total_session_booked)
    {
        $matchLoyality = null;
        foreach ($loyalties as $loyality) {
            if ($loyality->sessions <= $total_session_booked) {
                $matchLoyality = $loyality;
            } else {
                break;
            }
        }
        return $matchLoyality;
    }
}
