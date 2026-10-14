<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppNotification;
use App\Http\Resources\AppNotificationResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AppNotificationController extends Controller
{
    // list notifications (paginated)
    public function index(Request $request)
    {
        $user = auth('sanctum')->user(); // or other guard
        $perPage = (int) $request->get('per_page', 20);

        $query = AppNotification::where('user_id', $user->id)
            ->orderByDesc('created_at');

        $notifications = $query->paginate($perPage);

        return AppNotificationResource::collection($notifications)
            ->additional([
                'success' => 1,
            ]);
    }

    // store notification (admin) — supports file upload for image & icon
    public function store(Request $request)
    {
        // optionally protect via policy/middleware for admin
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'type' => 'nullable|string|max:50',
            'is_promotional' => 'sometimes|boolean',
            'image' => 'sometimes|file|image|max:2048', // 2MB
            'icon'  => 'sometimes|file|image|max:512',  // smaller file
            'data'  => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>0,'errors'=>$validator->errors()], 422);
        }

        $payload = $validator->validated();

        // handle uploads
        if ($request->hasFile('image')) {
            $payload['image'] = $request->file('image')->store('notifications/images', 'public');
        }
        if ($request->hasFile('icon')) {
            $payload['icon'] = $request->file('icon')->store('notifications/icons', 'public');
        }

        $notif = AppNotification::create([
            'user_id' => $payload['user_id'],
            'title' => $payload['title'] ?? null,
            'message' => $payload['message'] ?? null,
            'type' => $payload['type'] ?? null,
            'is_promotional' => $payload['is_promotional'] ?? false,
            'image' => $payload['image'] ?? null,
            'icon' => $payload['icon'] ?? null,
            'data' => $payload['data'] ?? null,
        ]);

        // optionally: dispatch push notifications, emails, websockets here

        return new AppNotificationResource($notif);
    }

    // mark single notification as read
    public function markRead(AppNotification $notification)
    {
        $user = auth('sanctum')->user();
        if ($notification->user_id !== $user->id) {
            return response()->json(['success'=>0,'message'=>'Forbidden'], 403);
        }
        $notification->markAsRead();
        return response()->json(['success'=>1,'message'=>'Marked as read']);
    }

    // mark all notifications for user as read
    public function markAllRead(Request $request)
    {
        $user = auth('sanctum')->user();

        AppNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success'=>1,'message'=>'All marked as read']);
    }

    // delete
    public function destroy(AppNotification $notification)
    {
        $user = auth('sanctum')->user();
        // allow owner or admin - change logic as required
        if ($notification->user_id !== $user->id) {
            return response()->json(['success'=>0,'message'=>'Forbidden'], 403);
        }
        // delete stored files if present
        if ($notification->image) {
            Storage::disk('public')->delete($notification->image);
        }
        if ($notification->icon) {
            Storage::disk('public')->delete($notification->icon);
        }
        $notification->delete();

        return response()->json(['success'=>1,'message'=>'Deleted']);
    }
}
