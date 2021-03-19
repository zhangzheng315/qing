<?php
namespace app\admin\serve;
use app\admin\controller\Administrators;
use app\admin\model\Admin;
use app\admin\serve\Common;
use app\admin\model\ModelAdministrators;
use app\admin\model\AuthRule;
use think\Log;


class ServeAdministrators extends Common{

    public $data;
    /**
     * 添加权限
     * @param $data
     * @return bool
     */
    public static function addRuleServe($data){
        try{
            $data['created_time'] = time();
            $data['updated_time'] = time();
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

    public function authTree()
    {
        $authRuleModel = new AuthRule();
        $this->data = $authRuleModel->where(['deleted_time' => 0])->order('order','desc')->select();
        $res = $this->tree();
        return $res;
    }

    public function tree($id = 0) {
        # 根据ID找出所有下级
        $childs = $this->child($this->data, $id);
        if (!$childs) {
            return false;
        }

        # 遍历下级数据
        foreach ($childs as $key => $value) {
            # 用下级ID找出下级自身下级数据
            $tree = $this->tree($value['rule_id']);
            if ($tree) {
                $childs[$key]['sub'] = $tree;
            }
        }
        return $childs;
    }

    public function child($res, $pid) {
        $childs = [];
        foreach ($res as $key => $value) {
            if ($value['pid'] == $pid) {
                $childs[] = $value;
            }
        }
        return $childs;
    }

    /**
     * 权限编辑
     * @param $data
     * @return bool
     */
    public static function ruleEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        $data['updated_time'] = time();
        $where = ['rule_id' => $data['rule_id']];
        $authRuleModel = new AuthRule();
        $res = $authRuleModel->update($data,$where);
        if(!$res){
            return false;
        }
        return true;
    }

    public static function ruleDelete($param)
    {
        $where = ['rule_id' => $param['rule_id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $authRuleModel = new AuthRule();
        $res = $authRuleModel->update($data,$where);
        if (!$res) {
            return false;
        }
        return true;
    }
}