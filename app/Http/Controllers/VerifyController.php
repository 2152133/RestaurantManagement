<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VerifyController extends Controller
{
    public function verify($remember_token)
    {
        User::where('remember_token', $remember_token)->firstOrFail()
        ->update([
            //'remember_token' => null,
            'last_shift_end' => Carbon::now(),
            'last_shift_start' => Carbon::now(),
            'email_verified_at' => Carbon::now()
        ]);

        return response('success', 200);
    }
}
