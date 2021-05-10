<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'name'=>'required|max:255',
            'email'=>'required|email|max:255|unique:users',
            'password'=>'required|max:255|min:8',
            'gender'=>'required|in:male,female',
            'date_of_birth'=>'required|date_format:Y-m-d',
            'contact_number'=>'required|max:50'
        ];
    }

     /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => 'This email has been used',
        ];
    }

    protected function failedValidation(Validator $validator) {
        DB::table('api_logs')->where("id",request()->apilog_id)->update(["response"=>"error validation"]);
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
