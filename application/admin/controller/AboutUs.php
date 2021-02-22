<?php
namespace app\admin\controller;

use app\admin\serve\AboutUsService;
use app\admin\serve\ArticleService;
use app\admin\serve\ArticleTypeService;
use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class AboutUs extends Common{
    public $aboutUsService;
    public function __construct(AboutUsService $aboutUsService)
    {
        parent::__construct();
        $this->aboutUsService = $aboutUsService;
    }

    public function index()
    {
        $add_url = '/admin/about_us/aboutUsCreateOrEdit';
        $edit_url = '/admin/article/articleEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 添加关于我们
     * @return mixed
     */
    public function aboutUsCreateOrEdit(Request $request){
        $rules =
            [
                'title' => 'require',
                'content' => 'require',
            ];
        $msg =
            [
                'title' => '缺少参数@title',
                'content' => '缺少参数@content',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->aboutUsService->aboutUsCreateOrEdit($request->param());
        if($res){
            return show($this->ok,$this->aboutUsService->message);
        }else{
            return show($this->fail,$this->aboutUsService->error);
        }
    }

    /**
     * 关于我们详情
     * @return mixed
     */
    public function aboutUsInfo(){
        $res = $this->aboutUsService->aboutUsInfo();
        if($res){
            return show($this->ok,$this->aboutUsService->message,$res);
        }else{
            return show($this->fail,$this->aboutUsService->error);
        }
    }
}