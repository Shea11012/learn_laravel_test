<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserAvatarsController extends Controller
{
    public function store()
    {
        $this->validate(\request(),[
            'avatar' => 'required|image|dimensions:min_width=200,min_height=200',
        ]);

        $user = \Auth::user();
        $user->update([
            'avatar_path' => \request()->file('avatar')->store('avatars','public'),
        ]);
    }
}
