<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionStoreRequest extends FormRequest
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
            'total_saving'=>'numeric',
            'total_spent'=>'required|numeric',
            'transaction_at'=>'required|date_format:Y-m-d H:i:s',
        ];
    }

     /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    protected function failedValidation(Validator $validator) {
        DB::table('api_logs')->where("id",request()->apilog_id)->update(["response"=>"error validation"]);
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
