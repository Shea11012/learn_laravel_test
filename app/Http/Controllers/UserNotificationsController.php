<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationsController extends Controller
{
    public function index()
    {
        $notifications = \Auth::user()->notifications()->paginate(20);
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json($notifications);
    }
}
