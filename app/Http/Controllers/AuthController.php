<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

define('YOUR_SERVER_URL', config('services.passport.login_endpoint'));
define('CLIENT_ID', config('services.passport.client_id'));
define('CLIENT_SECRET', config('services.passport.client_secret'));

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }
    public function postLogin(Request $request)
    {
        return $request->all();
    }
    public function logout()
    {
        dd(auth());
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        //return response()->json('Logged out successfully', 200);
        return response()->json(['msg'=>'Token revoked'], 200);
    }
    // public function logout()
    // {
    //     \Auth::guard('api')->user()->token()->revoke();
    //     \Auth::guard('api')->user()->token()->delete();
    //     return response()->json(['msg'=>'Token revoked'], 200);
    // }
}