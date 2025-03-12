<?php

namespace App\Repositories\ClientRepository;

use App\Models\User;
use App\Models\Appointment;
use App\Models\ClientContract;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ClientRepository\ClientInterface;
use Carbon\Carbon;
use App\Firenchill\CentralHelper;
use App\APIHandler\Client\ClientHandler;
class ClientRepository implements ClientInterface
{
    public function __construct(
        private User $user,private Appointment $appointment,
    ) {
    }


    // abundant
    public function getClientServiceStatus(){
        $user_id =   Auth::user()->id;
        $getContract = ClientContracttest::with(['contract'])->where('user_id',$user_id)->where('status',1)->first();
        if($getContract  ){
            $booked_session = $getContract->sessions;
            $max_session = $getContract->contract->weekly_sessions;
            $currentDate = Carbon::now();
            // Calculate the start of the week (Sunday)
            // Calculate the end of the week (Saturday)
            $endOfWeek = $currentDate->copy()->endOfWeek(Carbon::SATURDAY);
            if ($currentDate->between($startOfWeek, $endOfWeek) && $max_session > $booked_session ) {
                return [
                    'status' => 200,
                    'message' => 'You can book sessions',
                    'data'=>['session_booked'=>$booked_session,'valid_sessions'=>$max_session,'data'=>$getContract->contract]
                ];
            } else {
                 return [
                    'status' => 201,
                    'message' => 'Upgrade Your Packege',
                    'data'=>['session_booked'=>$booked_session,'valid_sessions'=>$max_session,'data'=>$getContract->contract]
                ];
            }
        }
         return [
                    'status' => 202,
                    'message' => 'You can book new Contract',
                ];

    }

}
