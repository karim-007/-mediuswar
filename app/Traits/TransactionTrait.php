<?php

namespace App\Traits;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

trait TransactionTrait
{
    /*
     * method for image upload
     * */
    protected function transactionFeeCalculate($user, $amount): array
    {
        $fee = 0;
        $total_transaction = Transaction::where(['user_id'=>$user->id,'transaction_type'=>'Withdrawal'])->sum('amount');

        if ($user->account_type == 'Business'){
            if ($total_transaction >= 50000) $fee = $amount * 0.015;
            else $fee = $amount * 0.025;
        }else{
            $today = new Carbon();
            if($today->dayOfWeek == Carbon::FRIDAY || $amount <= 100) $fee = 0;
            elseif ($total_transaction < 5000) { // checking transaction is over 5k or not then
                                                // every transaction 1k free of cost minus and 5k month
                                                // transaction free check how much use and not uses minus from main
                $fee = max(($amount - 1000 - (5000 - $total_transaction)) * 0.015,0);
            }else{
                $fee = max(($amount - 1000) * 0.015,0);
            }
        }
        return $fee;
    }
}
