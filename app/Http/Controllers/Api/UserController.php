<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_leader')->except('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUserId = Auth::id();
        $team = User::find($authUserId)->team;
        return response([
            'users' => $team->users->except($authUserId)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' => $data['gender'],
            'dob' =>  $data['dob'],
            'address' =>  $data['address'],
            'contact_no' =>  $data['contact_no'],
            'team_id' => User::find(Auth::id())->team_id
        ]);

        return response([
            'user' => new UserResource($user),
            'message' => 'User Created Successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $authUserId = Auth::id();
        $authTeamId = User::find($authUserId)->team_id;
        if ($authTeamId == $user->team_id) {
            return response([
                'user' => new UserResource($user)
            ], 200);
        }

        return response([
            'message' => 'Unauthorized Access'
        ], 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'min:6',
            'gender' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'contact_no' => 'required|unique:users,contact_no,' .  $user->id,
        ]);


        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response([
            'user' => new UserResource($user),
            'message' => 'User Updated Successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        $authUserId = Auth::id();
        $team = User::find($authUserId)->team;

        return response([
            'message' => 'Deleted Successfully',
            'user' => $team->users->except($authUserId)
        ]);
    }

    public function udpateProfile(Request $request)
    {
        $userId = Auth::id();
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'min:6',
            'gender' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'contact_no' => 'required|unique:users,contact_no,' .  $userId,
        ]);


        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = User::whereId($userId);
        $user->update($data);

        return response([
            'user' => new UserResource($user->first()),
            'message' => 'User Updated Successfully'
        ], 200);
    }
}
