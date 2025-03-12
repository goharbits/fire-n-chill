<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\AuthRequests\CheckoutShoppingCartRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Http\Requests\AuthRequests\RegisterGuestRequest;
use App\Repositories\AuthRepository\AuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Config;
use App\APIHandler\Auth\AuthHandler;
use App\Http\Requests\AuthRequests\ForgotPasswordRequest;
use App\Http\Requests\AuthRequests\GuestCheckoutRequest;
use App\Http\Requests\AuthRequests\ResetPasswordRequest;
use App\Http\Requests\AuthRequests\VerifyOTPRequest;
use App\Http\Requests\AuthRequests\VerifyOTPDeleteUserRequest;
use App\Models\User;
class AuthController extends Controller
{
    public function __construct(
        private AuthRepository $authRepository,
        private AuthHandler $AuthHandler,
    ) {}

    public function test()
    {
        return view('welcome');
    }


    public function login(LoginRequest $request): JsonResponse
    {

        $response = AuthHandler::generateAuthToken();
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }

        $mainBodyData = [
            'token' => $response->data->AccessToken, // this is mindbody token
            'expire' => $response->data->Expires,
        ];


        try {
            $userLoggedIn = $this->authRepository->login($request->all(), $mainBodyData);

            if (isset($userLoggedIn['error'])) {
                Log::error('Error Login: ' . $userLoggedIn['error']);

                if (strpos($userLoggedIn['error'], 'not verified') !== false) { // For PHP < 8
                    return self::errorResponse(self::FORBIDDEN, $userLoggedIn['error']);
                }
                return self::errorResponse(self::BAD_REQUEST, $userLoggedIn['error']);
            }

            Log::Info('Response Login success : ' . json_encode($userLoggedIn));
            return self::successResponse(self::OK, 'You have logged in successfully', $userLoggedIn);
        } catch (\Exception $error) {
            Log::Error('Error Login Exception: ' . $error->getMessage());
            return self::response(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }


    public function updateClientCard(CheckoutShoppingCartRequest $request)
    {
        $response =   AuthHandler::updateClientCard($request->all());
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::response(self::OK, $response->data);
    }


    public function updateClientProfile(Request $request)
    {
        $response =   AuthHandler::updateClientProfile($request->all());
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, 'Profile updated successfully!', $response->data);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $guestExist = AuthHandler::checkIfGuestExists($request);

            if ($guestExist) {
                try {
                    $userRegistered = $this->authRepository->registerGuestToClient($request->all(), $guestExist);
                    return self::response(self::CREATED, $userRegistered);
                } catch (\Exception $error) {
                    Log::Error('Error Register Guest: ' . $error->getMessage());
                    return self::response(self::INTERNAL_SERVER_ERROR, $error->getMessage());
                }
            } else {
                $checkUser  = AuthHandler::checkUserExists($request);
                if ($checkUser) {
                    return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'This Email already Exist! Please Choose another email');
                }
                $response =   AuthHandler::generateAuthToken();
                $response = json_decode($response->getContent());
                if ($response->status != 200) {
                    return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
                }

                //  after generating the token create client
                $clientResponse = AuthHandler::addClient($request, $response);
                $clientResponse = json_decode($clientResponse->getContent());

                if ($clientResponse->status != 200) {
                    return self::errorResponse(self::INTERNAL_SERVER_ERROR, $clientResponse->message);
                }
                $mainBodyData = [
                    'mainbody_id' => $clientResponse->data->id
                ];

                try {
                    $userRegistered = $this->authRepository->register($request->all(), $mainBodyData);
                    return self::successResponse(self::CREATED, 'User Created Successfully', $userRegistered);
                } catch (\Exception $error) {
                    return self::response(self::INTERNAL_SERVER_ERROR, $error->getMessage());
                }
            }
        } catch (\Exception $error) {
            return self::response(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function checkGuestFreeSevrivc($email){

        $check_user = User::where('email',$email)
                        ->where('free_service_status',1)
                        ->first();

        if($check_user){
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'You have already booked Free Session');
        }

        return self::successResponse(self::CREATED, 'You can book free Session');


    }
    public function guestCheckout($request)
    {


        $response =   AuthHandler::generateAuthToken();
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        //  after generating the token create client
        $guestExist = AuthHandler::checkGuestExists($request);
        if ($guestExist) {
            $checkContract =  AuthHandler::checkClientContract($guestExist->id);
            if ($checkContract) {
                return self::errorResponse(self::INTERNAL_SERVER_ERROR, 'You already have an active contract. Please login to proceed further');
            } else {
                $data = [
                    'id' => $guestExist->id,
                    'mainbody_id' => $guestExist->mainbody_id,
                    'token' => $response->data->AccessToken,
                    'expire' => $response->data->Expires,
                ];
                return self::successResponse(self::CREATED, 'You can create Purchasing', $data);
            }
        } else {
            $clientResponseNew = AuthHandler::addClient($request, $response);
            $clientResponse = json_decode($clientResponseNew->getContent());
            if ($clientResponse->status != 200) {
                return self::errorResponse(self::INTERNAL_SERVER_ERROR, $clientResponse->message);
            }
            $mainBodyData = [
                'token' => $response->data->AccessToken, // this is mindbody token
                'expire' => $response->data->Expires,
                'mainbody_id' => $clientResponse->data->id,
                'guest' => true
            ];

            try {
                $userRegistered = $this->authRepository->register($request, $mainBodyData);

                $data = [
                    'id' => $userRegistered['user']['id'],
                    'mainbody_id' => $clientResponse->data->id,
                    'token' => $response->data->AccessToken,
                    'expire' => $response->data->Expires,
                ];
                return self::successResponse(self::CREATED, 'You can buy now', $data);
            } catch (\Exception $error) {
                Log::Error('Error Register Guest: ', [$error]);
                return self::response(self::INTERNAL_SERVER_ERROR, $error->getMessage());
            }
        }
    }

    public function guestCheckoutToken(Request $request)
    {
        $response =   AuthHandler::generateAuthToken();
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return $response;
    }



    public function updateClientDeviceToken(Request $request): JsonResponse
    {
        try {
            $userRecover = $this->authRepository->updateClientDeviceToken($request->all());
            Log::Info('Reponse : Device Token updated Successfully');

            return self::successResponse(self::CREATED, 'Device Token updated Successfully!' . $userRecover);
        } catch (\Exception $error) {
            Log::Error('Error updateClientDeviceToken : ' . $error->getMessage());
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $userRecover = $this->authRepository->forgotPassword($request->all());
            if (isset($userRecover['error'])) {
                return self::errorResponse(self::INTERNAL_SERVER_ERROR, $userRecover['message']);
            }

            return self::successResponse(self::CREATED, 'Action Performed Successfully');
        } catch (\Exception $error) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }


    public function deleteAccountSendOTP(ForgotPasswordRequest $request): JsonResponse
    {
        $request['delete_account'] = true;
        $response =   $this->forgotPassword($request);
        $response = json_decode($response->getContent());
        if ( $response->status != 201 ) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, $response->message);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $userRecover = $this->authRepository->resetPassword($request->all());
            return self::successResponse(self::OK, 'Password Reset Successfully!', $userRecover);
        } catch (\Exception $error) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function verifyOTP(VerifyOTPRequest $request): JsonResponse
    {
        try {
            $response = $this->authRepository->verifyOTP($request->all());
            if (isset($response['error'])) {
                return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response['message']);
            }
            return self::successResponse(self::OK, 'Action performed Successfully', $response['user']);
        } catch (\Exception $error) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function deleteUserPermanently(VerifyOTPDeleteUserRequest $request): JsonResponse
    {
        try {
            $response = $this->authRepository->deleteUserPermanently($request->all());
            if($response['status'] == 500){
              return self::errorResponse(self::INTERNAL_SERVER_ERROR, @$response['error']);
            }

            return self::successResponse(self::OK, 'User Deleted Successfully!');
        } catch (\Exception $error) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $logout = $this->authRepository->logout($request);
            return self::response(self::CREATED, $logout);
        } catch (\Exception $error) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function loginPage()
    {
        return 'Please Login to proceed further';
    }

    public function loginWeb()
    {
        return view('auth.logs-login');
    }

    public function emailVerification(Request $request)
    {
        try {
            $response = $this->authRepository->verifyEmail($request->all());
            if ($response['status'] === 404) {
                return self::errorResponse(self::NOT_FOUND, 'No, OTP Found.');
            }
            if ($response['status'] === 403) {
                return self::errorResponse(self::UNAUTHORIZED, 'OTP expired');
            }

            return self::successResponse(self::CREATED, 'Your account is verified successfully!');
        } catch (\Exception $error) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function resendLoginOTP(Request $request)
    {
        try {
            $response = $this->authRepository->resendLoginOTP($request->all());
            if ($response['status'] === 404) {
                return self::errorResponse(self::NOT_FOUND, 'No, User Found.');
            }
            if ($response['status'] === 403) {
                return self::errorResponse(self::UNAUTHORIZED, 'OTP expired');
            }

            return self::successResponse(self::CREATED, 'OTP has sent to your email successfully!');
        } catch (\Exception $error) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }


    public function authUser(Request $request)
    {
        try {
            $response = $this->authRepository->authUser($request);
            if ($response['status'] == 404) {
                return redirect()->route('login')->with(['error' => $response['message']]);
            }
            if ($response['status'] == 200) {
                return redirect()->route('dash');
            }
        } catch (\Exception $error) {
            return redirect()->route('login')->with(['error' => $error->getMessage()]);
        }
    }

    public function logoutWeb(Request $request)
    {
        try {
            $response = $this->authRepository->logoutWeb($request);
            return redirect()->route('login');
        } catch (\Exception $error) {
            return redirect()->route('login');
        }
    }

    public function dashboard()
    {
        return view('auth.dash');
    }
}
