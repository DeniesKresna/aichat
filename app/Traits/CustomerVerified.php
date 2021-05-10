<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use app\Models\Customer;

trait CustomerVerified
{
    public function is_campaign_eligible(Customer $customer)
    {
        $transaction_last_30_days_count = DB::table('purchase_transaction')->where('customer_id',$customer->id)
                    ->where('transaction_at','>',DB::raw('NOW() - INTERVAL 30 DAY'))->count();
        if($transaction_last_30_days_count < 3){
            return false;
        }
        
        $transaction_whole_time_spent = DB::table('purchase_transaction')->where('customer_id',$customer->id)->sum('total_spent');
        if($transaction_whole_time_spent < 100){
            return false;
        }

        return true;
    }
}