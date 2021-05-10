<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\User\CustomerStoreRequest;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerController extends ApiController
{
    //
    public function store(CustomerStoreRequest $request)
    {
        $validated = $request->validated();

        //accomodate all user request parameter, and hashing the password
        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        //create the user by the container variable
        $user = User::create($data);
        if(!$user)
            return self::error_response([],"Cant register user. Please Try Again");
        
        $name_words = explode(" ",$request->name);
        $data["first_name"]=$name_words[0];
        
        $last_name = "";
        if(count($name_words) > 1){
            for($i=1; $i<count($name_words); $i++){
                $last_name .= $name_words[$i];
            }
        }
        $data["last_name"]=$last_name;
        $data["user_id"]=$user->id;
        $customer = Customer::create($data);
        
        if($customer)
            return self::success_response([],"Success add customer.");
        else{
            $user->delete();
            return self::error_response([],"Fail add customer.");
        }
    }


}
