<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Campaign\CampaignStoreRequest;
use App\Models\Campaign;
use App\Traits\CustomerVerified;
use Illuminate\Support\Facades\DB;
use Validator;

class CampaignController extends ApiController
{
    //
    use CustomerVerified;
    public function store(CampaignStoreRequest $request){
        $validated = $request->validated();
        $campaign = Campaign::create($request->all());
        if($campaign){
            return self::success_response($campaign,"Success add campaign.");
        }
    }

    public function index(){
        $user = auth()->user();
        $customer = $user->customer;
        $campaigns = [];

        if($user->role == "admin"){
            $campaigns = Campaign::all();
        }else{
            if($this->is_campaign_eligible($customer)){
                $campaigns = $this->getAvailableForCustomer($customer->id);
            }
        }
        return self::success_response($campaigns,"");
    }

    public function participate(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'photo' => 'required|file'
        ]);
        if ($validator->fails()) {
            return self::error_response($validator->errors(),"",422);
        }

        $user = auth()->user();
        $customer = $user->customer;

        $campaign = Campaign::find($id);
        if($campaign){
            if($this->is_campaign_eligible($customer)){
                if($this->getAvailableForCustomer($customer->id,$campaign->id) && $this->verifyFace($request->file('photo'))){
                    $code = md5(date("Y-m-d H:i:s")."-".$customer->id."-".$campaign->id);
                    $campaign->customers()->attach([$customer->id => ['code'=>$code]]);
                    return self::success_response(["voucher_code"=>$code],"Success participate campaign");
                }else{
                    return self::error_response([],"This campaign is not available for you anymore.");
                }
            }else{
                return self::error_response([],"Please check term and condition to participate this campaign");
            }
        }else{
            return self::error_response([],"Cant find the campaign");
        }
    }

    private function getAvailableForCustomer($customer_id,$campaign_id=null){
        $now = date("Y-m-d H:i:s");
        $campaigns = DB::table('campaigns')->whereNotIn('id', function($q) use ($customer_id){
            $q->select('campaign_id')->from('campaign_customer')
                ->where('customer_id','=',$customer_id)
                ->groupBy('campaign_id');
        })->whereNotIn('id', function($q){
            $q->select('cc.campaign_id')->from('campaign_customer as cc')
                ->leftJoin('campaigns as c','c.id','=','cc.campaign_id')
                ->groupBy('cc.campaign_id')->having(DB::raw('count(*)'),'>=','c.voucher_limit');
        })->where('active','1')->where('start_time','<',$now)
        ->where('end_time','>',$now)->get();

        if($campaign_id == null){
            return $campaigns;
        }else{
            foreach($campaigns as $campaign){
                if($campaign->id == $campaign_id){
                    return true;
                }
            }
            return false;
        }
    }

    private function verifyFace($file){
        return true;
    }
}
