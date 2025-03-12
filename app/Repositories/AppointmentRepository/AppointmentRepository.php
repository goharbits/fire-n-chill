<?php

namespace App\Repositories\AppointmentRepository;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AppointmentRepository\AppointmentInterface;
use Carbon\Carbon;
class AppointmentRepository implements AppointmentInterface
{
    public function __construct(
        private User $user,private Appointment $appointment
    ) {
    }



}