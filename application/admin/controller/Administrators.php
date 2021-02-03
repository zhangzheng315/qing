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
        $str = ModelAdministrators::getFieldList('auth_group','title,group_id',['status'=>1]);
        $add_url = '/admin/administrators/adminCreate';
        return $this->fetch('',['group'=>$str,'add_url'=>$add_url]);
    }

    /**
     * 角色管理
     */
    public function role(){
        $str = ModelAdministrators::getFieldList('auth_rule','title,rule_id',['status'=>1]);
        $add_url = '/admin/administrators/roleCreate';
        return $this->fetch('',['rules'=>$str,'add_url'=>$add_url]);
    }

    /**
     * 权限管理
     */
    public function rule(){
        $str = ModelAdministrators::getFieldList('auth_rule','title,rule_id',['status'=>1]);
        $add_url = '';
        return $this->fetch('',['rules'=>$str,'add_url'=>$add_url]);
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
     * 获取角色详情
     */
    public function getRoleInfo(){
        $data = input('post.');
        $str = ServeAdministrators::data_one_info($data,'auth_group');
        if($str){
            $str['rules'] = explode(',',$str['rules']);
            $str['rules_all'] =  ModelAdministrators::getFieldList('auth_rule','title,rule_id',['status'=>1]);
            return show($this->ok,'success',$str);
        }else{
            return show($this->fail,'error');
        }
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

    /**
     * 修改用户组
     */
    public function editGroup(){
        $data = input('post.');
        $rules =
        [
            'title' => 'require',
            'status' => 'require',
            'group_id' => 'require',
        ];
        $msg =
        [
            'title' => '缺少参数@title',
            'status' => '缺少参数@status',
            'group_id' => '缺少参数@group_id'
        ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $str = ServeAdministrators::editGroupServe($data);
        if($str){
            return show($this->ok,'修改成功');
        }else{
            return show($this->fail,'修改失败');
        }
    }

    /**
     * 新增管理人员
     */
    public function adminCreate(){
        $data = input('post.');
        $rules =
            [
                'name' => 'require',
                'email' => 'require',
                'pwd' => 'require',
                'group_id' => 'require',
            ];
        $msg =
            [
                'name' => '缺少参数@name',
                'email' => '缺少参数@email',
                'pwd' => '缺少参数@pwd',
                'group_id' => '缺少参数@group_id'
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $str = ServeAdministrators::adminCreate($data);
        if($str){
            return show($this->ok,'添加成功');
        }else{
            return show($this->fail,'添加失败');
        }
    }

    /**
     * 新增角色
     */
    public function roleCreate(){
        $data = input('post.');
        dd($data);
        $rules =
            [
                'name' => 'require',
                'email' => 'require',
                'pwd' => 'require',
                'group_id' => 'require',
            ];
        $msg =
            [
                'name' => '缺少参数@name',
                'email' => '缺少参数@email',
                'pwd' => '缺少参数@pwd',
                'group_id' => '缺少参数@group_id'
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $str = ServeAdministrators::adminCreate($data);
        if($str){
            return show($this->ok,'添加成功');
        }else{
            return show($this->fail,'添加失败');
        }
    }



}