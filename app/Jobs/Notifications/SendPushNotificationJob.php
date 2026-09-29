<?php

namespace App\Jobs\Notifications;

use App\Models\Notifications\UserDevice;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $title;

    protected $body;

    protected $data;

    public function __construct(User $user, string $title, string $body, array $data = [])
    {
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    public function handle(): void
    {
        $tokens = UserDevice::where('user_id', $this->user->id)
            ->where('status', true)
            ->pluck('device_token')
            ->toArray();

        if (empty($tokens)) {
            Log::info("No active device tokens found for User ID: {$this->user->id}");

            return;
        }

        Log::info("Sending Push Notification to User ID: {$this->user->id} on ".count($tokens).' devices.');

        foreach ($tokens as $token) {
            $this->sendToFcm($token);
        }
    }

    protected function sendToFcm(string $token)
    {
        // Note: For FCM v1, you normally need a Google Service Account JSON and an OAuth2 token.
        // For this implementation, we simulate the request structure that includes metadata for deep linking.

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $this->title,
                    'body' => $this->body,
                ],
                'data' => [
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'id' => '1',
                    'status' => 'done',
                    'reference_type' => $this->data['reference_type'] ?? '',
                    'reference_id' => (string) ($this->data['reference_id'] ?? ''),
                    'category' => $this->data['category'] ?? 'General',
                ],
            ],
        ];

        // In a real environment, you would call:
        // Http::withToken($this->getGoogleAccessToken())->post('https://fcm.googleapis.com/v1/projects/' . env('FIREBASE_PROJECT_ID') . '/messages:send', $payload);

        Log::debug("FCM Payload for token $token: ".json_encode($payload));
    }
}
