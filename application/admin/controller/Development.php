<?php
namespace app\admin\controller;

use app\admin\serve\DevelopmentService;
use think\Request;
use think\Validate;

class Development extends Common{
    public $developmentService;
    public function __construct(DevelopmentService $developmentService)
    {
        parent::__construct();
        $this->developmentService = $developmentService;
    }

    public function index()
    {
        $add_url = '/admin/development/developmentCreate';
        $edit_url = '/admin/development/developmentEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取发展历程列表
     */
    public function getDevelopmentList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = DevelopmentService::data_paging($data,'development','id');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加发展历程
     * @return mixed
     */
    public function developmentCreate(Request $request){
        $rules =
            [
                'content' => 'require',
                'time' => 'require',
            ];
        $msg =
            [
                'content' => '缺少参数@content',
                'time' => '缺少参数@time',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->developmentService->developmentCreate($request->param());
        if($res){
            return show($this->ok,$this->developmentService->message);
        }else{
            return show($this->fail,$this->developmentService->error);
        }
    }

    /**
     * 发展历程详情
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function developmentInfo(Request $request){
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
        $res = $this->developmentService->developmentInfo($request->param());
        if($res){
            return show($this->ok,$this->developmentService->message,$res);
        }else{
            return show($this->fail,$this->developmentService->error);
        }
    }

    /**
     * 发展历程修改
     * @return mixed
     */
    public function developmentEdit(){
        $data = input('post.');
        $rules =
            [
                'content' => 'require',
                'time' => 'require',
            ];
        $msg =
            [
                'content' => '缺少参数@content',
                'time' => '缺少参数@time',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->developmentService->developmentEdit($data);
        if($res){
            return show($this->ok,$this->developmentService->message,$res);
        }else{
            return show($this->fail,$this->developmentService->error);
        }
    }

    /**
     * 发展历程删除
     * @return mixed
     */
    public function developmentDelete(){
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
        $res = $this->developmentService->developmentDelete($data);
        if($res){
            return show($this->ok,$this->developmentService->message,$res);
        }else{
            return show($this->fail,$this->developmentService->error);
        }
    }
}