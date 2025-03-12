<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository\ClientRepository;
use Illuminate\Http\JsonResponse;
use App\APIHandler\Client\ClientHandler;

class ClientController extends Controller
{

    public function __construct(
        private ClientRepository $clientRepository,
        private ClientHandler $clientHandler,
    ) {}

    public function getClientCompleteInfo(Request $request)
    {
        $response =     ClientHandler::getClientCompleteInfo($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }

        return self::response(self::OK, $response->data);
    }

    public function getClientschedule(Request $request)
    {
        $response = ClientHandler::getClientschedule($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }

        return self::successResponse(self::OK, 'Client Schedules', $response->data);
    }

    public function getClientVisits()
    {

        $response = ClientHandler::getClientVisits();
        $response = json_decode($response->getContent());

        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }

        return self::response(self::OK, $response->data);
    }

    // public function terminateContract(Request $request){
    //     $response =   ClientHandler::terminateContract($request);
    //     $response = json_decode($response->getContent());
    //         if($response->status != 200){
    //             return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
    //         }

    //         return self::response(self::OK, $response->data);
    // }

    public function getClientVitality(Request $request)
    {

        try {
            $getClientVitality = $this->clientRepository->getClientVitality();
            return self::successResponse(self::OK, 'Client Data Found', $getClientVitality);
        } catch (\Exception $error) {
            return self::response(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function getClientServiceStatus(Request $request)
    {

        $response =  ClientHandler::getClientServices($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, 'Client data found', $response->data);
    }

    public function getClientUpgradeableService(Request $request)
    {

        $response =   ClientHandler::getClientUpgradeableService($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, 'Client can upgrade now ', $response->data);
    }
}
