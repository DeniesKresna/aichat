<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    //
    public $timestamps = false;
    protected $table = "purchase_transaction";

    protected $fillable = [
        'total_spent', 'total_saving', 'transaction_at','customer_id'
    ];

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
}
