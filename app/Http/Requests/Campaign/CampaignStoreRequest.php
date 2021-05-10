<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;

class CampaignStoreRequest extends FormRequest
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
            'start_time'=>'required|date_format:Y-m-d H:i:s',
            'end_time'=>'required|date_format:Y-m-d H:i:s|after:start_time',
            'voucher_limit'=>'required|integer',
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
            
        ];
    }

    protected function failedValidation(Validator $validator) {
        DB::table('api_logs')->where("id",request()->apilog_id)->update(["response"=>"error validation"]);
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
