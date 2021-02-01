<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\serve\ServeAdministrators;
use app\admin\model\ModelAdministrators;
use think\Validate;


class Administrators extends Common{

    /**
     * 管理员列表
     */
    public function list(){
        return $this->fetch();
    }

    /**
     * 角色管理
     */
    public function role(){
        return $this->fetch();
    }

    /**
     * 权限管理
     */
    public function rule(){
        $str = ModelAdministrators::getFieldList('auth_rule','title,rule_id',['status'=>1]);
        return $this->fetch('',['rules'=>$str]);
    }

    /**
     * 分页获取权限列表
     */
    public function getRuleList(){
        $data = input('get.');
        $data['status'] = 1;
        $str = ServeAdministrators::data_paging($data,'auth_rule','sort');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 分页获取角色列表
     */
    public function getRoleList(){
        $data = input('get.');
        $data['status'] = 1;
        $str = ServeAdministrators::data_paging($data,'auth_group','group_id');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 分页获取管理员列表
     */
    public function getAdminList(){
        $data = input('get.');
        $data['status'] = 1;
        $str = ServeAdministrators::data_paging($data,'admin','id');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }


    /**
     * 新增权限列表数据
     */
    public function addRule(){
        $data = input('post.');
        $rules =
        [
            'title' => 'require',
            'status' => 'require',
            'pid' => 'require',
            'sort' => 'require',
        ];
        $msg =
        [
            'title' => '缺少参数@title',
            'status' => '缺少参数@status',
            'pid' => '缺少参数@pid',
            'sort' => '缺少参数@sort'
        ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $str = ServeAdministrators::addRuleServe($data);
        if($str){
            return show($this->ok,'添加成功');
        }else{
            return show($this->fail,'添加失败');
        }
    }



}