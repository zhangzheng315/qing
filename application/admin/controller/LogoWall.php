<?php
namespace app\admin\controller;

use app\admin\serve\CaseTypeService;
use app\admin\serve\LogoWallService;
use app\admin\serve\NavigationService;
use think\Request;
use think\Validate;

class LogoWall extends Common{
    public $logoWallService;
    public $navigationService;
    public $caseTypeService;
    public function __construct(LogoWallService $logoWallService,NavigationService $navigationService, CaseTypeService $caseTypeService)
    {
        parent::__construct();
        $this->logoWallService = $logoWallService;
        $this->navigationService = $navigationService;
        $this->caseTypeService = $caseTypeService;
    }

    public function index()
    {
        $navigation_list = $this->navigationService->navigationList();
        $case_type_list = $this->caseTypeService->caseTypeList();
        $add_url = '/admin/logo_wall/logoWallCreate'; //添加
        $edit_url = '/admin/logo_wall/logoWallEdit';  //修改
        return $this->fetch('',compact('navigation_list','case_type_list','add_url','edit_url'));
    }

    /**
     * 分页获取logo墙列表
     */
    public function getLogoWallList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = LogoWallService::data_paging($data,'logowall','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加logo墙
     * @return mixed
     */
    public function logoWallCreate(Request $request){
        $rules =
            [
                'pid' => 'require',
                'img_url' => 'require',
                'type_id' => 'require',
            ];
        $msg =
            [
                'pid' => '缺少参数@pid',
                'img_url' => '缺少参数@img_url',
                'type_id' => '缺少参数@type_id',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->logoWallService->logoWallCreate($request->param());
        if($res){
            return show($this->ok,$this->logoWallService->message);
        }else{
            return show($this->fail,$this->logoWallService->error);
        }
    }

    /**
     * logo墙详情
     * @return mixed
     */
    public function logoWallInfo(Request $request){
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
        $res = $this->logoWallService->logoWallInfo($request->param());
        if($res){
            return show($this->ok,$this->logoWallService->message,$res);
        }else{
            return show($this->fail,$this->logoWallService->error);
        }
    }

    /**
     * 修改logo墙
     * @return mixed
     */
    public function logoWallEdit(){
        $data = input('post.');
        $rules =
            [
                'pid' => 'require',
                'img_url' => 'require',
                'type_id' => 'require',
            ];
        $msg =
            [
                'pid' => '缺少参数@pid',
                'img_url' => '缺少参数@img_url',
                'type_id' => '缺少参数@type_id',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->logoWallService->logoWallEdit($data);
        if($res){
            return show($this->ok,$this->logoWallService->message);
        }else{
            return show($this->fail,$this->logoWallService->error);
        }
    }

    /**
     * 删除logo墙
     * @return mixed
     */
    public function logoWallDelete(Request $request){
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
        $res = $this->logoWallService->logoWallDelete($request->param());
        if($res){
            return show($this->ok,$this->logoWallService->message);
        }else{
            return show($this->fail,$this->logoWallService->error);
        }
    }

    /**
     * logo墙
     * @return mixed
     */
    public function getLogoByWhere(Request $request){
        $rules =
            [
                'pid' => 'require',
                'type_id' => 'require',
            ];
        $msg =
            [
                'pid' => '缺少参数@pid',
                'type_id' => '缺少参数@type_id',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->logoWallService->getLogoByWhere($request->param());
        if($res){
            return show($this->ok,$this->logoWallService->message,$res);
        }else{
            return show($this->fail,$this->logoWallService->error);
        }
    }
}