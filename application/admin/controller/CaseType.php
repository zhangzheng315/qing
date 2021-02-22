<?php
namespace app\admin\controller;

use app\admin\serve\CaseService;
use app\admin\serve\CaseTypeService;
use app\admin\serve\NavigationService;
use think\Request;
use think\Validate;

class CaseType extends Common{
    public $caseTypeService;
    public $navigationService;
    public function __construct(CaseTypeService $caseTypeService, NavigationService $navigationService)
    {
        parent::__construct();
        $this->caseTypeService = $caseTypeService;
        $this->navigationService = $navigationService;
    }

    public function index()
    {
        $add_url = '/admin/case_type/caseTypeCreate';
        $edit_url = '/admin/case_type/caseTypeEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getCaseTypeList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = CaseTypeService::data_paging($data,'case_type','order');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function caseTypeCreate(Request $request){
        $rules =
            [
                'name' => 'require',
            ];
        $msg =
            [
                'name' => '缺少参数@name',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->caseTypeService->caseTypeCreate($request->param());
        if($res){
            return show($this->ok,$this->caseTypeService->message);
        }else{
            return show($this->fail,$this->caseTypeService->error);
        }
    }

    /**
     * 文章详情
     * @return mixed
     */
    public function caseTypeInfo(Request $request){
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
        $res = $this->caseTypeService->caseTypeInfo($request->param());
        if($res){
            return show($this->ok,$this->caseTypeService->message,$res);
        }else{
            return show($this->fail,$this->caseTypeService->error);
        }
    }

    /**
     * 文章修改
     * @return mixed
     */
    public function caseTypeEdit(){
        $data = input('post.');
        $rules =
            [
                'name' => 'require',
            ];
        $msg =
            [
                'name' => '缺少参数@name',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->caseTypeService->caseTypeEdit($data);
        if($res){
            return show($this->ok,$this->caseTypeService->message,$res);
        }else{
            return show($this->fail,$this->caseTypeService->error);
        }
    }

    /**
     * 文章删除
     * @return mixed
     */
    public function caseTypeDelete(){
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
        $res = $this->caseTypeService->caseTypeDelete($data);
        if($res){
            return show($this->ok,$this->caseTypeService->message,$res);
        }else{
            return show($this->fail,$this->caseTypeService->error);
        }
    }
}