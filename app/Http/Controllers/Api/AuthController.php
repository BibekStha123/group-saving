<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
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
                'user' => $user,
                'team' => $team
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
            $token = $user->createToken('mytoken')->plainTextToken;

            return response([
                'user' => $user,
                'access_token' => $token
            ]);
        }

        return response([
            'errors' => "Credentials does not match"
        ], 401);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete;
        return response([
            'message' => "Logged Out Successfully"
        ], 200);
    }
}
