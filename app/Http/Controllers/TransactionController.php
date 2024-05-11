<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function index(Request $request): TransactionCollection
    {
        $transactions = Transaction::all();

        return new TransactionCollection($transactions);
    }

    public function store(TransactionStoreRequest $request): TransactionResource
    {
        $transaction = Transaction::create($request->validated());

        return new TransactionResource($transaction);
    }

    public function show(Request $request, Transaction $transaction): TransactionResource
    {
        return new TransactionResource($transaction);
    }

    public function update(TransactionUpdateRequest $request, Transaction $transaction): TransactionResource
    {
        $transaction->update($request->validated());

        return new TransactionResource($transaction);
    }

    public function destroy(Request $request, Transaction $transaction): Response
    {
        $transaction->delete();

        return response()->noContent();
    }

    public function allTransactions(Request $request)
    {
        $user = auth('api')->user();
        $transactions = Transaction::where('user_id',$user->id)->get(['id','user_id', 'transaction_type', 'amount', 'fee', 'date']);
        return response()->json(['data'=>$transactions,'current_balance'=>$user->balance],200);
    }

    public function depositTransactions(Request $request)
    {
        $user = auth('api')->user();
        $transactions = Transaction::where(['user_id'=>$user->id,'transaction_type'=>'Deposit'])->get();
        return TransactionResource::collection($transactions);
    }

    public function withdrawTransactions(Request $request)
    {
        $user = auth('api')->user();
        $transactions = Transaction::where(['user_id'=>$user->id,'transaction_type'=>'Withdrawal'])->get();
        return TransactionResource::collection($transactions);
    }
}
