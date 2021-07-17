<?php
namespace app\admin\controller;

use app\admin\serve\JoinUsService;
use think\Request;
use think\Validate;

class JoinUs extends Common{
    public $joinUsService;
    public function __construct(JoinUsService $joinUsService)
    {
        parent::__construct();
        $this->joinUsService = $joinUsService;
    }

    public function index()
    {
        $add_url = '/admin/join_us/joinUsCreate';
        $edit_url = '/admin/join_us/joinUsEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getJoinUsList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = JoinUsService::data_paging($data,'join_us','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function joinUsCreate(Request $request){
        $rules =
            [
                'position' => 'require',
                'salary' => 'require',
                'region' => 'require',
                'education' => 'require',
                'years' => 'require',
            ];
        $msg =
            [
                'position' => '缺少参数@position',
                'salary' => '缺少参数@salary',
                'region' => '缺少参数@region',
                'education' => '缺少参数@education',
                'years' => '缺少参数@years',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->joinUsService->joinUsCreate($request->param());
        if($res){
            return show($this->ok,$this->joinUsService->message);
        }else{
            return show($this->fail,$this->joinUsService->error);
        }
    }

    /**
     * 文章详情
     * @return mixed
     */
    public function joinUsInfo(Request $request){
        $rules =
            [
                'id' => 'require',
            ];
        $msg =
            [
                'id' => '缺少参数@id',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->joinUsService->joinUsInfo($request->param());
        if($res){
            return show($this->ok,$this->joinUsService->message,$res);
        }else{
            return show($this->fail,$this->joinUsService->error);
        }
    }

    /**
     * 文章修改
     * @return mixed
     */
    public function joinUsEdit(){
        $data = input('post.');
        $rules =
            [
                'position' => 'require',
                'salary' => 'require',
                'region' => 'require',
                'education' => 'require',
                'years' => 'require',
            ];
        $msg =
            [
                'position' => '缺少参数@position',
                'salary' => '缺少参数@salary',
                'region' => '缺少参数@region',
                'education' => '缺少参数@education',
                'years' => '缺少参数@years',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->joinUsService->joinUsEdit($data);
        if($res){
            return show($this->ok,$this->joinUsService->message,$res);
        }else{
            return show($this->fail,$this->joinUsService->error);
        }
    }

    /**
     * 文章删除
     * @return mixed
     */
    public function joinUsDelete(){
        $data = input('post.');
        $rules =
            [
                'id' => 'require',
            ];
        $msg =
            [
                'id' => '缺少参数@id',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->joinUsService->joinUsDelete($data);
        if($res){
            return show($this->ok,$this->joinUsService->message,$res);
        }else{
            return show($this->fail,$this->joinUsService->error);
        }
    }
}