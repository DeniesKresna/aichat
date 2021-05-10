<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApiController extends BaseController
{
    public function __construct(Request $request)
    {
        if(!$request->has("hasbeencreated")){
            $apilog_id = DB::table('api_logs')->insertGetId(["url"=>URL::current(),"request"=>json_encode($request->all()),"ip"=>request()->ip(), "ua"=>request()->header('user_agent')]);
            $request->merge(["apilog_id"=>$apilog_id,"hasbeencreated"=>true]);
        }
    }

    /**
     * @param array $datas
     * @return \Illuminate\Http\JsonResponse
     */
    public function error_response($data=[],$message = "",$status_code = 450){
        DB::table('api_logs')->where("id",request()->apilog_id)->update(["response"=>$message]);
        if($data == [])
            $data = null;
        return response()->json([
            "data"=>$data,
            "message"=>$message
        ],$status_code);
    }

    /**
     * @param array $datas
     * @return \Illuminate\Http\JsonResponse
     */
    public function success_response($data=[],$message=""){
        DB::table('api_logs')->where("id",request()->apilog_id)->update(["response"=>json_encode($data)]);
        if($data == [])
            $data = null;
        return response()->json([
            "data"=>$data,
            "message"=>$message
        ],200);
    }
}
