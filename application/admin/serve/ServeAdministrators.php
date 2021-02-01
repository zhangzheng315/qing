<?php
namespace app\admin\serve;
use app\admin\serve\Common;
use app\admin\model\ModelAdministrators;
use app\admin\model\AuthRule;
use think\Log;


class ServeAdministrators extends Common{
    
    public static function addRuleServe($data){
        try{
            $data['create_time'] = time();
            $data['update_time'] = time();
            $ruleModel = new AuthRule();
            $add = $ruleModel->insert($data);
            if($add){
                return true;
            }else{
                return false;
            }
        }catch(\Exception $e){
            Log::error('admin/ServeAdministrators/addRuleServe:'.$e->getMessage());
            return false;
        }
    }

}