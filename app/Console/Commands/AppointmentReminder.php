<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ScriptRepository\ScriptInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\GoogleAuthService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use App\Events\NotificationCreated;
use App\Notifications\AppointmentReminderNotification;

class AppointmentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send reminder apoointment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->sendNotification();
    }

     public function sendNotification()
    {

        $projectId = env('FCM_PROJECT_ID');
        $SERVER_API_KEY = env('FCM_SERVER_KEY');

        // Check if the server key is present
        if (empty($SERVER_API_KEY)) {
            return [
                'error' => 502,
                'message' => 'FCM Server Key not found'
            ];
        }

        // Fetch appointments based on the remaining time
        $appointments24Hours = $this->getAppointmentsWithTimeDifference(24 * 60);
        $appointments30Minutes = $this->getAppointmentsWithTimeDifference(30);
        $appointments1Minute = $this->getAppointmentsWithTimeDifference(3);

        // Send notifications for each set of appointments
        $this->sendNotificationsForAppointments($appointments24Hours,  $projectId, 'Your appointment is in 24 hours!');
        $this->sendNotificationsForAppointments($appointments30Minutes,  $projectId, 'Your appointment is in 30 minutes!');
        $this->sendNotificationsForAppointments($appointments1Minute,  $projectId, 'Your appointment is in few minutes!');

        return [
            'success' => 200,
            'message' => 'Notifications sent successfully.'
        ];
    }

    protected function getAppointmentsWithTimeDifference(int $minutesDifference)
    {
        $now = Carbon::now();
        $targetTime = $now->copy()->addMinutes($minutesDifference);
        return Appointment::where('start_date_time', '>=', $now)
            ->where('start_date_time', '<=', $targetTime)
            ->where('notify_status', 0)
            ->get();
    }

    protected function sendNotificationsForAppointments($appointments, $projectId, $messageBody)
    {
        foreach ($appointments as $appointment) {
            // Fetch the user associated with the appointment
            $user = User::where('id', $appointment->user_id)->whereNotNull('device_token')->first();

            // If user has a device token, send notification
            if ($user && $user->device_token) {

                // Create a new notification in the database

                $notificationData = [
                    'message' => $messageBody,
                ];

                $notification = Notification::create([
                    'type' => 'App\Notifications\AppointmentReminderNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $user->id,
                    'data' => json_encode($notificationData),
                    'read_at' => null,
                ]);

                event(new NotificationCreated($notification));
                // do not send the notification again and again
                $appointment->update(['notify_status'=>1]);
            }
        }


    }
}