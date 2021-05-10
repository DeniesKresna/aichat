<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $fillable = [
        'first_name', 'last_name', 'gender', 'date_of_birth', 'contact_number', 'email','user_id'
    ];

    public function user(){
        return $this->belongsTo("App\Models\User");
    }

    public function transactions(){
        return $this->hasMany("App\Models\Transaction");
    }
}
