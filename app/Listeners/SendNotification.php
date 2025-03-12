<?php

namespace App\Listeners;
use App\Services\GoogleAuthService;
use App\Events\NotificationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class SendNotification{

    public function handle(NotificationCreated $event): void
    {

        $notification = $event->notification;
        $user = $notification->notifiable_id;
        $user =  User::where('id',$user)->first();
        $msg = json_decode($notification['data'], true);

        if ($user && $user->device_token) {
            $data = [
                "message" => [
                    "token" => $user->device_token,
                    "notification" => [
                        "title" => 'Appointment Reminder',
                        "body" => $msg['message'],
                    ]
                ]
            ];

            // Fetch the access token and project ID
            $authService = new GoogleAuthService();
            $accessToken = $authService->getToken();
            $projectId = env('FCM_PROJECT_ID');
            // Send the notification via FCM
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send', $data);

            // Handle the response (log or error handling can be done here)
            if ($response->failed() || isset($response->json()['error'])) {
                // Log the error or handle it accordingly
                Log::Error('ISSUE AT DEVICE TOKEN');
            }
            Log::info('sent');
        }
    }
}