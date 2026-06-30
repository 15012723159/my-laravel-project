<?php
/**
 * @Notes:
 * @Date: 2026/1/16
 * @Time: 10:16
 * @Interface ProfileController
 * @return
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ACUser;
use App\Models\User;
use App\Service\CurlService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * User: qinfuxing
 * Date: 2026/1/16
 * Time: 10:16
 */
class ProfileController extends Controller
{
    public function index(){

        return $this->success(['hello' => 'profile.index']);
    }


    public function edit(Request $request){
        $res = ACUser::query()->where('id',1)->update(['name'=>'hello world','introduction'=>'']);
        return $this->success(['hello' => 'profile.edit'.$res]);
    }

    public function create(Request $request){
        $acuser = new ACUser();
        $acuser->name = "qfx";
        $acuser->introduction = "真的自我";
        $acuser->save();
        return $this->success(['id' => $acuser->id]);
    }

    public function batchAll(Request $request){
        $params['form']['use_free_shipping_privilege'] = 1;
        $use_free_shipping_privilege = 1;
        $use_free_shipping_privilege = isset($params['form']['use_free_shipping_privilege']) && $params['form']['use_free_shipping_privilege'] ==  1 && $use_free_shipping_privilege ? 1 : ($params['form']['use_free_shipping_privilege'] ?? 0);
        var_dump($use_free_shipping_privilege);die;
        $set['introduction'] =null;
        var_dump($set);
        var_dump(isset($set['introduction']));
        if( array_key_exists('introduction',$set) && is_null($set['introduction'])){
            var_dump(111);
            $set['introduction'] = '';
        }
        var_dump($set);
        /*$acuser = new ACUser();
        $acuser->name = "qfx";
        $acuser->introduction = "真的自我";
        $acuser->();*/
        $set = ["avatar"=> ""];
        $update_fields = ['avatar', 'display_name', 'gender', 'region',
            'display_country_code', 'birthday', 'introduction', 'user_name',
            'social_links', 'print_type', 'printer_id', 'level', 'first_name', 'last_name', 'phone', 'country_code'];
        //只获取允许修改的字段
        $set = Arr::only($set, $update_fields);
        var_dump($set);die;
        return $this->success(['id' => $acuser->id]);
    }

    public function curl(){
       //$result =  app(CurlService::class)->callThirdApi('/json/220.181.108.144','get',[],[]);
       //var_dump($result);

        $result =  app(CurlService::class)->callThirdApi('/','get',[],[]);
        return $result;
    }

    public function log(Request $request){
        $layer_height = $request->input('layer_height');
        if(in_array(0.2,["0.4","0.20","0.8","0.20"])){
           $layer_height = round($layer_height/2,1);
           echo $layer_height;
        }else{
            echo "2222";
        }

        Log::channel('api')->info('hello world');
        return $this->success('hello world');
    }

    public function  user($id,$action){
        return $this->success(['id' => $id,'action' => $action]);
    }
}
