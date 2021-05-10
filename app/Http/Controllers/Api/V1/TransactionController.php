<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Models\PurchaseTransaction;

class TransactionController extends ApiController
{
    //
    public function store(TransactionStoreRequest $request){
        $validated = $request->validated();
        
        $data = $request->all();
        
        $customer = auth()->user()->customer;
        $data["customer_id"] = $customer->id;
        $transaction = PurchaseTransaction::create($data);
        if($transaction){
            return self::success_response([],"Success add transaction.");
        }else{
            return self::error_response([],"Failed add transaction.");
        }
    }
}
