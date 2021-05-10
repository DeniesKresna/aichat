<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    //
    protected $fillable = [
        'name', 'start_time', 'end_time', 'voucher_limit'
    ];

    public function customers(){
        return $this->belongsToMany("App\Models\Customer");
    }

    public function transactions(){
        return $this->hasMany("App\Models\PurchaseTransaction");
    }
}
