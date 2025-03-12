<?php

namespace App\Repositories\AuthRepository;

interface AuthInterface
{
    public function login($data, $tokenObj);
    public function register($data, $mainBodyData);
    public function forgotPassword($data);
    public function resetPassword($data);
    public function verifyOTP($data);
    public function logout($data);
    public function logoutWeb($data);
    public function updateClientDeviceToken($request);
    public function resendLoginOTP($request);
    public function registerGuestToClient($request,$mainBodyData);
    public function authUser($request);
}