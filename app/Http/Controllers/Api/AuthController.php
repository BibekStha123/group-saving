<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    //
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $name = substr($data['name'], 0, 3);
        $time = date("YmdHis");

        try {
            DB::beginTransaction();

            $team = Team::create([
                'name' => $name.$time
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'gender' => $data['gender'],
                'dob' =>  $data['dob'],
                'address' =>  $data['address'],
                'contact_no' =>  $data['contact_no'],
                'is_leader' => true,
                'team_id' => $team->id
            ]);

            DB::commit();

            return response([
                'user' => new UserResource($user),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (Auth::attempt($data)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token = Auth::claims(['is_leader' => $user->is_leader])->attempt($data);
            // $refreshToken = Auth::claims(['is_leader' => $user->is_leader])->refresh();
            return response([
                'user' => new UserResource($user),
                'access_token' => $token,
                // 'refresh_token' => $refreshToken
            ]);
        }

        return response([
            'errors' => "Credentials does not match"
        ], 401);
    }

    public function logout()
    {
        auth()->logout(true);

        return response([
            'message' => "Logged Out Successfully"
        ], 200);
    }

    public function refreshToken()
    {
        $user = Auth::user();
        $access_token = Auth::claims(['is_leader' => $user->is_leader])->refresh(true, true);

        return response([
            'access_token' => $access_token
        ], 200);
    }
}
