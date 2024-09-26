<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Target extends Controller
{
    public function sendNotification()
    {
        $user = User::find(19); // The user you want to notify (change to your target user)

        // Trigger the notification
        $user->notify(new \App\Notifications\Target($user));

        return response()->json(['message' => 'Notification sent!']);
    }

    public function index()
    {
        $user = User::find(19);

        $notifications = $user->notifications()->latest()->get();

        return response()->json(['notifications' => $notifications]);

    }
}
