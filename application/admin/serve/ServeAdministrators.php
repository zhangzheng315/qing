<?php
namespace app\admin\serve;
use app\admin\controller\Administrators;
use app\admin\model\Admin;
use app\admin\serve\Common;
use app\admin\model\ModelAdministrators;
use app\admin\model\AuthRule;
use think\Log;


class ServeAdministrators extends Common{

    /**
     * 添加权限
     * @param $data
     * @return bool
     */
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

    public static function editGroupServe($data){
        try{
        $string = '';
        foreach($data as $key=>$val){
            if(strstr($key,'like')){
                $string.= $val.',';
            }
        }
        $string = substr($string,0,strlen($string)-1);
        $arr['rules'] = $string;
        $arr['title'] = $data['title'];
        $arr['status'] = $data['status'];
        $arr['update_time'] = time();
        $edit = db('auth_group')->where('group_id',$data['group_id'])->update($arr);
        if($edit){
            return true;
        }else{
            return false;
        }
        }catch(\Exception $e){
            Log::error('admin/ServeAdministrators/addRuleServe:'.$e->getMessage());
            return false;
        }
    }

    /**
     * 添加管理人员
     * @param $data
     * @return bool
     */
    public static function adminCreate($data){
        try{
            $data['create_time'] = time();
            $data['update_time'] = time();
            $adminModel = new Admin();
            $add = $adminModel->insert($data);
            if($add){
                return true;
            }else{
                return false;
            }
        }catch(\Exception $e){
            Log::error('admin/ServeAdministrators/adminCreate:'.$e->getMessage());
            return false;
        }
    }

}