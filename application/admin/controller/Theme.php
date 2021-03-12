<?php
namespace app\admin\controller;

use app\admin\serve\ThemeService;
use app\admin\serve\CaseTypeService;
use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class Theme extends Common{
    public $themeService;
    public function __construct(ThemeService $themeService)
    {
        parent::__construct();
        $this->themeService = $themeService;
    }

    public function index()
    {
        $add_url = '/admin/theme/themeCreate';
        $edit_url = '/admin/theme/themeEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取视频主题列表
     */
    public function getThemeList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = ThemeService::data_paging($data,'theme','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加视频主题
     * @return mixed
     */
    public function caseCreate(Request $request){
        $rules =
            [
                'theme_name' => 'require',
            ];
        $msg =
            [
                'theme_name' => '缺少参数@theme_name',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->themeService->themeCreate($request->param());
        if($res){
            return show($this->ok,$this->themeService->message);
        }else{
            return show($this->fail,$this->themeService->error);
        }
    }

    /**
     * 视频主题详情
     * @return mixed
     */
    public function themeInfo(Request $request){
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
        $res = $this->themeService->themeInfo($request->param());
        if($res){
            return show($this->ok,$this->themeService->message,$res);
        }else{
            return show($this->fail,$this->themeService->error);
        }
    }

    /**
     * 视频主题修改
     * @return mixed
     */
    public function themeEdit(){
        $data = input('post.');
        $rules =
            [
                'theme_name' => 'require',
            ];
        $msg =
            [
                'theme_name' => '缺少参数@theme_name',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->themeService->themeEdit($data);
        if($res){
            return show($this->ok,$this->themeService->message,$res);
        }else{
            return show($this->fail,$this->themeService->error);
        }
    }

    /**
     * 视频主题删除
     * @return mixed
     */
    public function themeDelete(){
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
        $res = $this->themeService->themeDelete($data);
        if($res){
            return show($this->ok,$this->themeService->message,$res);
        }else{
            return show($this->fail,$this->themeService->error);
        }
    }
}