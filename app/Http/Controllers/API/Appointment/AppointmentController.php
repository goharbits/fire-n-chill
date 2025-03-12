<?php

namespace App\Http\Controllers\API\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AppointmentRepository\AppointmentRepository;
use Illuminate\Http\JsonResponse;
use App\APIHandler\Appointment\AppointmentHandler;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentRepository $appointmentRepository,
        private AppointmentHandler $appointmentHandler,
    ) {}


    public function getPreviousApps()
    { // boookings
        try {
            $getAppointments = $this->appointmentRepository->getPreviousApps();
            return self::response(self::OK, $getAppointments);
        } catch (\Exception $error) {
            return self::response(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }

    public function getUpcomingApps()
    { // boookings

        try {
            $getAppointments = $this->appointmentRepository->getUpcomingApps();
            return self::response(self::OK, $getAppointments);
        } catch (\Exception $error) {
            return self::response(self::INTERNAL_SERVER_ERROR, $error->getMessage());
        }
    }


    public function getAppBookableItems(Request $request)
    {

        $response =   AppointmentHandler::getAppBookableItems($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }

        return self::response(self::OK, $response->data);
    }

    public function getActiveSessionTimes()
    {

        $response =   AppointmentHandler::getActiveSessionTimes();
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, 'Active Session times', $response->data);
    }

    public function bookAppointment(Request $request)
    {

        $response =   AppointmentHandler::bookAppointment($request);
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, 'Appointment Added Successfully.', $response->data);
    }


    public function updateAppointment(Request $request)
    {
        $response = AppointmentHandler::updateAppointment($request->all());
        $response = json_decode($response->getContent());
        if ($response->status != 200) {
            return self::errorResponse(self::INTERNAL_SERVER_ERROR, $response->message);
        }
        return self::successResponse(self::OK, 'Appointment Updated Successfully.', $response->data);
    }
}
