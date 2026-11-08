<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function latest()
    {
        $user = auth()->user();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),

            'notifications' => $user->unreadNotifications()
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'data' => $notification->data,
                        'created_at' => $notification->created_at->diffForHumans(),
                    ];
                }),
        ]);
    }

    public function markAsRead(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}