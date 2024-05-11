<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\TransactionTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class TransactionController extends Controller
{
    use TransactionTrait;
    /*
     * method for user deposit amount
     * */
    public function store(TransactionStoreRequest $request): TransactionResource
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data +=[
                'transaction_type'=>'Deposit',
                'date'=>Carbon::today(),
            ];
            $transaction = Transaction::create($data);
            $user = User::find($data['user_id']);
            $user->update(['balance'=>$user->balance+$data['amount']]);
            DB::commit();
            return new TransactionResource($transaction);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status'=>'fail','message'=>"transaction fail"],404);
        }
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

    /*
     * method for show user all transaction
     * */
    public function allTransactions(Request $request)
    {
        $user = auth('api')->user();
        $transactions = Transaction::where('user_id',$user->id)->get(['id','user_id', 'transaction_type', 'amount', 'fee', 'date']);
        return response()->json(['data'=>$transactions,'current_balance'=>$user->balance],200);
    }

    /*
     * method for show user deposit transaction
     * */
    public function depositTransactions(Request $request)
    {
        $user = auth('api')->user();
        $transactions = Transaction::where(['user_id'=>$user->id,'transaction_type'=>'Deposit'])->get();
        return TransactionResource::collection($transactions);
    }

    /*
     * method for show user withdraw transaction
     * */
    public function withdrawTransactions(Request $request)
    {
        $user = auth('api')->user();
        $transactions = Transaction::where(['user_id'=>$user->id,'transaction_type'=>'Withdrawal'])->get();
        return TransactionResource::collection($transactions);
    }

    /*
     * method for withdraw amount
     * */
    public function withdraw(TransactionStoreRequest $request)
    {
        $data = $request->validated();
        $user = User::find($data['user_id']);
        $fee = $this->transactionFeeCalculate($user, $data['amount']);
        $total = $data['amount'] + $fee;
        if ($user->balance < $total) return response()->json(['status'=>'fail','message'=>'Insufficient amount'],404);
        $data +=[
            'fee'=>$fee,
            "date"=>Carbon::today()
        ];
        DB::beginTransaction();
        try {
            $transaction = Transaction::create($data);
            $user->update(['balance'=>$user->balance - $total]);
            DB::commit();
            return new TransactionResource($transaction);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status'=>'fail','message'=>'Invalid request'],404);
        }
    }



}
