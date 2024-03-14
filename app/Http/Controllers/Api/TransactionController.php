<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('is_leader')->except(['index', 'show']);
    }

    public function index()
    {
        $authUser = User::whereId(Auth::id())->first();
        if ($authUser->is_leader) {
            $transactions = $authUser->team->transactions;
        } else {
            $transactions = $authUser->transactions;
        }

        return response([
            'transactions' => TransactionResource::collection($transactions)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTransactionRequest $request)
    {
        $data = $request->validated();

        $authUser = User::whereId(Auth::id())->first();
        $authTeamId = $authUser->team_id;
        $userTeamId = User::whereId($data['user_id'])->first()->team_id;

        if ($authTeamId == $userTeamId) {

            $transaction = Transaction::create([
                'team_id' => $authTeamId,
                'user_id' => $data['user_id'],
                'created_by' => Auth::id(),
                'status' => $data['status'],
                'amount' => $data['amount']
            ]);

            return response([
                'message' => 'Transaction Created Successfully',
                'transaction' => new TransactionResource($transaction)
            ], 200);
        }

        return response([
            'message' => 'Unauthorized. Provided details does not match'
        ], 401);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $authUser = User::whereId(Auth::id())->first();
        if ($transaction->team_id == $authUser->team_id) {
            if ($authUser->is_leader) {
                return response([
                    'transaction' => new TransactionResource($transaction)
                ], 200);
            } else if($transaction->user_id == $authUser->id){
                return response([
                    'transaction' => new TransactionResource($transaction)
                ], 200);
            }
        } 

        return response([
            'message' => 'Unauthorized Access'
        ], 401);
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
