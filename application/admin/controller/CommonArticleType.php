<?php
namespace app\admin\controller;

use app\admin\serve\ArticleTypeService;
use app\admin\serve\CommonArticleTypeService;
use think\Request;
use think\Validate;

class CommonArticleType extends Common{
    public $commonArticleTypeService;
    public function __construct(CommonArticleTypeService $commonArticleTypeService)
    {
        parent::__construct();
        $this->commonArticleTypeService = $commonArticleTypeService;
    }

    public function index()
    {
        $add_url = '/admin/common_article_type/commonArticleTypeCreate';
        $edit_url = '/admin/common_article_type/commonArticleTypeEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取公共文章分类列表
     */
    public function getCommonArticleTypeList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = CommonArticleTypeService::data_paging($data,'common_article_type','order');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加公共文章分类
     * @return mixed
     */
    public function commonArticleTypeCreate(Request $request){
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
        $res = $this->commonArticleTypeService->commonArticleTypeCreate($request->param());
        if($res){
            return show($this->ok,$this->commonArticleTypeService->message);
        }else{
            return show($this->fail,$this->commonArticleTypeService->error);
        }
    }

    /**
     * 公共文章分类详情
     * @return mixed
     */
    public function commonArticleTypeInfo(Request $request){
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
        $res = $this->commonArticleTypeService->commonArticleTypeInfo($request->param());
        if($res){
            return show($this->ok,$this->commonArticleTypeService->message,$res);
        }else{
            return show($this->fail,$this->commonArticleTypeService->error);
        }
    }

    /**
     * 公共文章分类修改
     * @return mixed
     */
    public function commonArticleTypeEdit(){
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
        $res = $this->commonArticleTypeService->commonArticleTypeEdit($data);
        if($res){
            return show($this->ok,$this->commonArticleTypeService->message,$res);
        }else{
            return show($this->fail,$this->commonArticleTypeService->error);
        }
    }

    /**
     * 公共文章分类删除
     * @return mixed
     */
    public function commonArticleTypeDelete(){
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
        $res = $this->commonArticleTypeService->commonArticleTypeDelete($data);
        if($res){
            return show($this->ok,$this->commonArticleTypeService->message,$res);
        }else{
            return show($this->fail,$this->commonArticleTypeService->error);
        }
    }
}