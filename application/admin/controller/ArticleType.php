<?php
namespace app\admin\controller;

use app\admin\serve\ArticleTypeService;
use think\Request;
use think\Validate;

class ArticleType extends Common{
    public $articleTypeService;
    public function __construct(ArticleTypeService $articleTypeService)
    {
        parent::__construct();
        $this->articleTypeService = $articleTypeService;
    }

    public function index()
    {
        $add_url = '/admin/article_type/articleTypeCreate';
        $edit_url = '/admin/article_type/articleTypeEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取视频分类列表
     */
    public function getArticleTypeList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = ArticleTypeService::data_paging($data,'article_type','order');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加视频分类
     * @return mixed
     */
    public function articleTypeCreate(Request $request){
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
        $res = $this->articleTypeService->articleTypeCreate($request->param());
        if($res){
            return show($this->ok,$this->articleTypeService->message);
        }else{
            return show($this->fail,$this->articleTypeService->error);
        }
    }

    /**
     * 视频分类详情
     * @return mixed
     */
    public function articleTypeInfo(Request $request){
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
        $res = $this->articleTypeService->articleTypeInfo($request->param());
        if($res){
            return show($this->ok,$this->articleTypeService->message,$res);
        }else{
            return show($this->fail,$this->articleTypeService->error);
        }
    }

    /**
     * 视频分类修改
     * @return mixed
     */
    public function articleTypeEdit(){
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
        $res = $this->articleTypeService->articleTypeEdit($data);
        if($res){
            return show($this->ok,$this->articleTypeService->message,$res);
        }else{
            return show($this->fail,$this->articleTypeService->error);
        }
    }

    /**
     * 视频分类删除
     * @return mixed
     */
    public function articleTypeDelete(){
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
        $res = $this->articleTypeService->articleTypeDelete($data);
        if($res){
            return show($this->ok,$this->articleTypeService->message,$res);
        }else{
            return show($this->fail,$this->articleTypeService->error);
        }
    }
}