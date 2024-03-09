<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:teams'
        ]);

        try {
            DB::beginTransaction();

            $team = Team::create([
                'name' => $data['name']
            ]);
    
            //update user's team_id
            $user = User::find(Auth::id());
            $user['team_id'] = $team->id;
    
            $user->update();

            DB::commit();
    
            return response([
                'message' => 'Team Created',
                'team' => $team
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
