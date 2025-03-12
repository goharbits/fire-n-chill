<?php

namespace App\Repositories\AuthRepository;

use App\Models\OTP;
use App\Models\User;
use App\Models\Appointment;
use App\Models\ClientContract;
use App\Mail\ForgotPasswordMail;
use App\Mail\PassowrdChangedMail;
use App\Mail\DeleteAccountMail;
use App\Mail\SignUpWelcomeMail;
use App\APIHandler\Auth\AuthHandler;
use App\APIHandler\Sale\SaleHandler;
use App\APIHandler\Appointment\AppointmentHandler;
use App\Mail\EmailVerificationMail;
use App\Firenchill\CentralHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Repositories\AuthRepository\AuthInterface;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AuthRepository implements AuthInterface
{
    public function __construct(
        private User $user,
        private OTP $OTP
    ) {}

    public function login($data, $tokenObj)
    {
        $email = $data['email'];
        $password = $data['password'];

        $user = $this->user->where('email', $email)->first();

        if (!is_null($user)) {
            if($user->status == 0){
                return [
                    'error' => 'This email is deactivated. Please sign up to create a full account or use a different email to log in. '
                ];
            }
            if ($user->guest_user == 1 && is_null($user->email_verified_at)) {
                return [
                    'error' => 'This email was used for a guest checkout and is not yet linked to an account. Please sign up to create a full account or use a different email to log in. '
                ];
            }
            if (is_null($user->email_verified_at)) {
                return [
                    'error' => 'Your Email is not verified. Please verify your email.'
                ];
            }
            //Check for password match
            if (Hash::check($password, $user->password)) {
                $user->update([
                    'mainbody_token' => $tokenObj['token'],
                    'remember_mainbody_token' => $tokenObj['expire']
                ]);
                $token = $user->createToken('passportToken')->accessToken;
                return [
                    'user' => $user,
                    'token' => $token
                ];
            }
            Log::Error('Credentials do not match!');

            return [
                'error' => 'Credentials do not match!'
            ];
        }
        Log::Error('User Not Found');
        return [
            'error' => 'User Not Found'
        ];
    }

    public function register($data, $mainBodyData)
    {
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        // $dob = $data['birth_date'];
        if (isset($data['guest']) && $data['guest'] == 'true') {
            $pass = Str::random(8);
            $last_name = $last_name;
            $guest = 1;
            $mainbody_token = '';
        } else {
            $pass = $data['password'];
            $last_name = $last_name;
            $guest = 0;
            $mainbody_token = '';
        }

        $password = Hash::make($pass);
        $payload = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $data['email'],
            'password' => $password,
            // 'birth_date' => $dob,
            'mainbody_id' => $mainBodyData['mainbody_id'],
            'mainbody_token' => $mainbody_token,
            'guest_user' => $guest,
            // 'email_verified_at'=>now()
        ];
        Log::Info('Payload Client fire & Chill: ' . json_encode($payload));

        $user = $this->user->create($payload);
        Log::Info('Client Created fire & Chill: ' . json_encode(@$user));
        // $token = $user->createToken('passportToken')->accessToken;
        $otp = CentralHelper::generateNumericOTP();

        if (!isset($data['guest']) || $data['guest'] !== 'true') {
            $this->OTP->create([
                'user_id' => $user->id,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addHour()
            ]);
        }

        try{
            if ($user->guest_user == 0)
            {
                Mail::to($data['email'])->send(new SignUpWelcomeMail([
                    'full_name' => $user->first_name . " " . $user->last_name,
                    'email' => $user->email
                ]));

                Mail::to($data['email'])->send(new EmailVerificationMail([
                    'otp' => $otp,
                    'full_name' => $user->first_name . " " . $user->last_name,
                    'email' => $user->email
                ]));
            }
        } catch (\Exception $ex) {
            Log::Error('Error Client Register:' . $ex->getMessage());
        }

        return [
            'user' => $user,
            // 'token' => $token
        ];
    }
    public function registerGuestToClient($data, $user)
    {
        $password = Hash::make($data['password']);
        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'password' => $password,
            // 'birth_date' => $data['birth_date'],
            'guest_user' => 0,
        ]);
        $otp = CentralHelper::generateNumericOTP();

        $this->OTP->create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' =>  Carbon::now()->addHour()
        ]);

        try {
            Mail::to($data['email'])->send(new SignUpWelcomeMail([
                'full_name' => $user->first_name . " " . $user->last_name,
                'email' => $user->email
            ]));

            if ($user->guest_user == 0) {
                Mail::to($data['email'])->send(new EmailVerificationMail([
                    'otp' => $otp,
                    'full_name' => $user->first_name . " " . $user->last_name,
                    'email' => $user->email
                ]));
            }
        } catch (\Exception $ex) {
            Log::Error('Error registerGuestToClient' . $ex->getMessage());
        }

        return [
            'user' => $user,
            // 'token' => $token
        ];
    }

    public function forgotPassword($data): array
    {


        $email = $data['email'];
        $user = $this->user->where('email', $email)->first();
        if ($user->guest_user == 1) {
            return [
                'error' => 405,
                'message' => 'This email is available as guest. Please Register yourself.'
            ];
        }
        $otp = CentralHelper::generateNumericOTP();
        $expires_at =   Carbon::now()->addHour();
        $this->OTP->where('user_id', $user->id)->delete();
        $this->OTP->create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => $expires_at
        ]);

        try {
            if(isset($data['delete_account'])){

                Mail::to($email)->send(new DeleteAccountMail([
                    'otp' => $otp,
                    'full_name' => $user->first_name . " " . $user->last_name,
                    'email' => $user->email,
                    'expires_at' => $expires_at
                ]));

            }else{

                Mail::to($email)->send(new ForgotPasswordMail([
                    'otp' => $otp,
                    'full_name' => $user->first_name . " " . $user->last_name,
                    'email' => $user->email,
                    'expires_at' => $expires_at
                ]));
            }

        } catch (\Exception $ex) {
            Log::Error('Error forgotPassword' . $ex->getMessage());
        }

        return [
            'status' => 200,
            'message' => 'Email has been sent! Please check your mailbox.'
        ];
    }

    public function verifyOTP($data): array | object
    {

        $otp = $data['otp'];
        //get OTP
        $getOTP = $this->OTP->where([
            // 'user_id' => $userId,
            'otp' => $otp
        ])->first();

        //if otp not found
        if (is_null($getOTP)) {
            return [
                'error' => 404,
                'message' => 'No otp found.'
            ];
        }
        //if otp expired
        $currentDateTime = Carbon::now();
        if ($currentDateTime->greaterThan($getOTP->expires_at)) {
            return [
                'error' => 502,
                'message' => 'OTP has expired.'
            ];
        }

        $user = $this->user->where('id', $getOTP->user_id)->select('id', 'first_name', 'last_name')->first();

        // $getOTP->delete();
        return [
            'status' => 200,
            'user' => $user
        ];
    }

    public function resetPassword($data): array | object
    {
        $otp = $data['otp'];
        $userId = $data['user_id'];
        $password = Hash::make($data['password']);

        //get OTP
        $getOTP = $this->OTP->where([
            'user_id' => $userId,
            'otp' => $otp
        ])->first();

        //if otp not found
        if (is_null($getOTP)) {
            return [
                'error' => 404,
                'message' => 'No otp found.'
            ];
        }
        //if otp expired
        $currentDateTime = Carbon::now();
        if ($currentDateTime->greaterThan($getOTP->expires_at)) {
            return [
                'error' => 502,
                'message' => 'OTP has expired.'
            ];
        }

        //update password
        $user = $this->user->where('id', $userId)->select('id', 'email', 'first_name', 'last_name', 'password')->first();
        $user->update([
            'password' => $password,
            'email_verified_at' => now()
        ]);

        try {
                Mail::to($user->email)->send(new PassowrdChangedMail([
                    'full_name' => $user->first_name . " " . $user->last_name,
                    'email' => $user->email,
                ]));


        } catch (\Exception $ex) {
            Log::Error('Error forgotPassword' . $ex->getMessage());
        }

        $getOTP->delete();
        return $user;
    }

    public function deleteUserPermanently($data): array | object
    {
        $otp = $data['otp'];
        $userId = $data['user_id'];

        //get OTP
        $getOTP = $this->OTP->where([
            'user_id' => $userId,
            'otp' => $otp
        ])->first();

        //if otp not found
        if (is_null($getOTP)) {
            return [
                'status'=>500,
                'error' => 'No otp found.',
                'message' => 'No otp found.'
            ];
        }
        //if otp expired
        $currentDateTime = Carbon::now();
        if ($currentDateTime->greaterThan($getOTP->expires_at)) {
            return [
                'status'=>500,
                'error' => 'OTP has expired.',
                'message' => 'OTP has expired.'
            ];
        }


        $user = $this->user->where('id', $userId)->select('id','mainbody_id','mainbody_token')->first();

        $response =  $this->getClientContractsToDelete($user);
        if($response['status'] == 500){
            return  $response;
        }

        $resAppointment =  $this->removeAppointmentAgainstUser($user);

        if($resAppointment['status'] == 500){
            return  $response;
        }


        $responseDeactive =  $this->deactiveClient($user);
        $responseDeactive = json_decode($responseDeactive->getContent());

        if ($responseDeactive->status != 200) {
            return [
                'status'=>500,
                'error'=>$responseDeactive->message
            ];
        }

        return [
            'status' => 200,
            'message' => 'User Deleted Successfully!'
        ];
        // $getOTP->delete();
        // return $user;
    }


    public function removeAppointmentAgainstUser($user){

        $appointments = Appointment::where('status','Booked')->where('user_id',$user->id)->get();
        foreach($appointments as $appointment){
           try{
                $request =
                [
                    'cancelAppointment' => true,
                    'appointment_id' => $appointment->appointment_id,
                    'test' => false
                ];
                $appResponses = AppointmentHandler::cencelAppointment($request);
                $appResponse = json_decode($appResponses->getContent());

                if ($appResponse->status != 200) {
                     Log::Error('Apointment cancel issue : ' . @$appResponse->message->Error->Message);
                    // return [
                    //     'status'=>500,
                    //     'error'=>$appResponse->message
                    // ];
                }

            }catch (\Exception $e) {
                Log::Error('Apointment cancel issue : ' . $e->getMessage());

            }
        }
        return [
                'status'=>200,
        ];
    }

    public function getClientContractsToDelete($user){

        $contract = ClientContract::where('status',1)->where('user_id',$user->id)->first();
        if($contract){
            $createToken = AuthHandler::generateAuthToken();
            $createToken = json_decode($createToken->getContent());
            if ($createToken->status != 200) {
                //return self::errorResponse(self::INTERNAL_SERVER_ERROR, $createToken->message);
                return [
                    'status'=>500,
                    'error'=>$createToken->message
                ];
            }

            $currentDateTime = $contract->start_date;
            $request = [
                'mainbody_id'=> $user->mainbody_id,
                'mainbody_token'=> $createToken->data->AccessToken,
                'StartDateTime'=> $currentDateTime,
                'terminationComments'=>'Client deleted'
            ];

            $contract['client_contract_id'] = $contract->client_contract_id;
            $response = SaleHandler::terminateContract($request,$contract);
            $response = json_decode($response->getContent());

            if ($response->status != 200) {
                return [
                    'status'=>500,
                    'error'=>$response->message
                ];
            }
        }

        // after terminating Contracts , deactive the client



        return [
                'status'=>200,
              ];
    }


    public function deactiveClient($user){

        return AuthHandler::deactiveClient($user);
    }


    public function verifyEmail($data)
    {
        $otpCheck = $this->OTP->where(
            // 'user_id' => $data['user_id'],
            'otp',
            $data['otp']
        )->first();

        if (is_null($otpCheck)) {
            return [
                'status' => 404
            ];
        }

        $currentDateTime = Carbon::now();
        if ($currentDateTime->greaterThan($otpCheck->expires_at)) {
            return [
                'status' => 403
            ];
        }
        $user = $this->user->where('id', $otpCheck->user_id)->update(['email_verified_at' => now()]);
        $otpCheck->delete();
        return [
            'status' => 200

        ];
    }

    public function logout($request)
    {
        $user = Auth::user() ?? Auth::guard('api')->user();
        if ($user) {
            Log::Info('Response ClientLogout User id: ' . $user->id);
            $this->user->where('id', $user->id)->update([
                'mainbody_token' => '',
                'remember_mainbody_token' => ''
            ]);

            $token = $user->token();
            $token->revoke();
        }

        return true;
    }

    public function updateClientDeviceToken($request)
    {
        $user = Auth::user() ?? Auth::guard('api')->user();
        if ($user) {
            $this->user->where('id', $user->id)->update([
                'device_token' => $request['device_token']
            ]);
        }
        return true;
    }

    public function resendLoginOTP($data)
    {
        $user =  $this->user->where('email', $data['email'])->first();
        if (!$user) {
            return [
                'status' => 404
            ];
        }
        $this->OTP->where('user_id', $user->id)->delete();
        $otp = CentralHelper::generateNumericOTP();

        $this->OTP->create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' =>  Carbon::now()->addHour()
        ]);

        try {
            Mail::to($user->email)->send(new EmailVerificationMail([
                'otp' => $otp,
                'full_name' => $user->first_name . " " . $user->last_name,
                'email' => $user->email
            ]));
        } catch (\Exception $ex) {
            Log::Error('Error resendLoginOTP:' . $ex->getMessage());
        }

        return [
            'status' => 200

        ];
    }

    public function authUser($request)
    {
        // Validate the input data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication passed, now check the user's role
            $user = Auth::user();

            if ($user->role == 1) {
                // Role is 2, redirect to the intended page (e.g., dashboard)
                return ['status' => '200', 'message' => 'User found'];
            } else {
                // Role is not 2, log out the user and redirect back with an error
                Auth::logout();
                return ['status' => '404', 'message' => 'User Not Exist'];
            }
        }

        return ['status' => '404', 'message' => 'The provided credentials do not match our records.'];
    }

    public function logoutWeb($request)
    {
        Auth::guard('web')->logout();
        return true;
    }
}
