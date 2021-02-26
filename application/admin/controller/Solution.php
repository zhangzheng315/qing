<?php
namespace app\admin\controller;

use app\admin\serve\SolutionService;
use think\Request;
use think\Validate;

class Solution extends Common{
    public $solutionService;
    public function __construct(SolutionService $solutionService)
    {
        parent::__construct();
        $this->solutionService = $solutionService;
    }

    public function index()
    {
        $add_url = '/admin/solution/solutionCreate'; //添加
        $edit_url = '/admin/solution/solutionEdit';  //修改
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取解决方案列表
     */
    public function getSolutionList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = SolutionService::data_paging($data,'solution','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
            $value['type'] = $value['type'] == 1 ? '场景解决方案' : '行业解决方案';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加解决方案
     * @return mixed
     */
    public function solutionCreate(Request $request){
        $rules =
            [
                'img_url' => 'require',
                'title' => 'require',
            ];
        $msg =
            [
                'img_url' => '缺少参数@img_url',
                'title' => '缺少参数@title',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->solutionService->solutionCreate($request->param());
        if($res){
            return show($this->ok,$this->solutionService->message);
        }else{
            return show($this->fail,$this->solutionService->error);
        }
    }

    /**
     * 解决方案详情
     * @return mixed
     */
    public function solutionInfo(Request $request){
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
        $res = $this->solutionService->solutionInfo($request->param());
        if($res){
            return show($this->ok,$this->solutionService->message,$res);
        }else{
            return show($this->fail,$this->solutionService->error);
        }
    }

    /**
     * 修改解决方案
     * @return mixed
     */
    public function solutionEdit(){
        $data = input('post.');
        $rules =
            [
                'img_url' => 'require',
                'title' => 'require',
            ];
        $msg =
            [
                'img_url' => '缺少参数@img_url',
                'title' => '缺少参数@title',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->solutionService->solutionEdit($data);
        if($res){
            return show($this->ok,$this->solutionService->message);
        }else{
            return show($this->fail,$this->solutionService->error);
        }
    }

    /**
     * 删除解决方案
     * @return mixed
     */
    public function solutionDelete(Request $request){
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
        $res = $this->solutionService->solutionDelete($request->param());
        if($res){
            return show($this->ok,$this->solutionService->message);
        }else{
            return show($this->fail,$this->solutionService->error);
        }
    }
}