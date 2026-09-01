<?php

namespace App\Http\Controllers\Api\v1\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notifications\NotificationResource;
use App\Repositories\Notifications\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $notifications = $this->notificationRepository->getUserNotifications(Auth::id());

        return response()->json(NotificationResource::collection($notifications)->response()->getData(true));
    }

    public function markAsRead(int $id): JsonResponse
    {
        $notification = $this->notificationRepository->findById($id);
        if (! $notification || $notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $this->notificationRepository->markAsRead($id);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllAsRead(): JsonResponse
    {
        $this->notificationRepository->markAllAsRead(Auth::id());

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function destroy(int $id): JsonResponse
    {
        $notification = $this->notificationRepository->findById($id);
        if (! $notification || $notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $this->notificationRepository->delete($id);

        return response()->json(['message' => 'Notification deleted']);
    }
}
