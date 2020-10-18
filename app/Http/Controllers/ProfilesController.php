<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $activities = $user->activities()->latest()->with('subject')->get();

        return response()->json([
            'profileUser' => $user,
            'activities' => $activities,
        ]);
    }
}
