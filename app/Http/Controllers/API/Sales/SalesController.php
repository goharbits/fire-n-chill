<?php

namespace App\Http\Controllers\API\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SaleRepository\SaleRepository;
use App\Http\Requests\AuthRequests\CheckoutShoppingCartRequest;
use App\Http\Requests\AuthRequests\CheckoutShoppingCartPerSessionRequest;
use App\Http\Requests\AuthRequests\CheckoutShoppingCartFreeRequest;
use Illuminate\Http\JsonResponse;
use App\APIHandler\Sale\SaleHandler;
use App\Http\Controllers\API\Auth\AuthController;

class SalesController extends Controller
{
    public function __construct(
        private AuthController $authController,
        private SaleRepository $saleRepository,
        private SaleHandler $saleHandler,
    ) {}

    public function salePurchaseContracts(CheckoutShoppingCartRequest $request)
    {
        $response =   SaleHandler::salePurchaseContracts($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, $response->message, $response->data);
    }

    public function updateSalePurchaseContracts(Request $request)
    {
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, $response->message, $response->data);
    }

    public function guestCheckoutshoppingcart(CheckoutShoppingCartPerSessionRequest $request)
    {

            $response = $this->authController->guestCheckout($request);
            $data = json_decode($response->getContent());

            if ($data && $data->status === self::CREATED) {

                $request->merge((array)$data->data);

                $response = SaleHandler::checkoutshoppingcart($request);
                $response = json_decode($response->getContent());
                if ($response->status != 200) {
                    return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
                }

                return self::successResponse(self::OK, @$response->data);
            }
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);



        $response = SaleHandler::checkoutshoppingcart($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, @$response->data);
    }

    public function checkoutshoppingcart(CheckoutShoppingCartRequest $request)
    {
        $response = SaleHandler::checkoutshoppingcart($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, @$response->data);
    }


     public function checkoutshoppingcartFreeService(CheckoutShoppingCartFreeRequest $request)
    {

        $response = $this->authController->checkGuestFreeSevrivc($request->email);
        $data = json_decode($response->getContent());

        if ($data && $data->status === self::INTERNAL_SERVER_ERROR) {
            return self::errorResponse($data->status, $data->message);
        }

            $response = $this->authController->guestCheckout($request);
            $data = json_decode($response->getContent());

            if ($data && $data->status === self::CREATED) {

                $request->merge((array)$data->data);

                $response = SaleHandler::checkoutshoppingcart($request);
                $response = json_decode($response->getContent());
                if ($response->status != 200) {
                    return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
                }

                return self::successResponse(self::OK, @$response->data);
            }
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
    }
}
