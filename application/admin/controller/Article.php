<?php
namespace app\admin\controller;

use app\admin\serve\ArticleService;
use app\admin\serve\ArticleTypeService;
use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class Article extends Common{
    public $articleService;
    public $articleTypeService;
    public $labelService;
    public function __construct(ArticleService $articleService, ArticleTypeService $articleTypeService, LabelService $labelService)
    {
        parent::__construct();
        $this->articleService = $articleService;
        $this->articleTypeService = $articleTypeService;
        $this->labelService = $labelService;
    }

    public function index()
    {
        $article_type_list = $this->articleTypeService->articleTypeList();
        $label_list = $this->labelService->labelList();
        $add_url = '/admin/article/articleCreate';
        $edit_url = '/admin/article/articleEdit';
        return $this->fetch('',compact('article_type_list','label_list','add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getArticleList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = ArticleService::data_paging($data,'article','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
            $value['hot_article'] = $value['hot_article'] == 1 ? '是' : '否';
            $value['content_center'] = $value['content_center'] == 1 ? '是' : '否';
            $value['first_home'] = $value['first_home'] == 1 ? '是' : '否';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function articleCreate(Request $request){
        $rules =
            [
                'title' => 'require',
                'content' => 'require',
                'pid' => 'require',
            ];
        $msg =
            [
                'title' => '缺少参数@title',
                'content' => '缺少参数@content',
                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->articleService->articleCreate($request->param());
        if($res){
            return show($this->ok,$this->articleService->message);
        }else{
            return show($this->fail,$this->articleService->error);
        }
    }

    /**
     * 文章详情
     * @return mixed
     */
    public function articleInfo(Request $request){
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
        $res = $this->articleService->articleInfo($request->param());
        if($res){
            return show($this->ok,$this->articleService->message,$res);
        }else{
            return show($this->fail,$this->articleService->error);
        }
    }

    /**
     * 文章修改
     * @return mixed
     */
    public function articleEdit(){
        $data = input('post.');
        $rules =
            [
                'title' => 'require',
                'content' => 'require',
                'pid' => 'require',
            ];
        $msg =
            [
                'title' => '缺少参数@title',
                'content' => '缺少参数@content',
                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->articleService->articleEdit($data);
        if($res){
            return show($this->ok,$this->articleService->message,$res);
        }else{
            return show($this->fail,$this->articleService->error);
        }
    }

    /**
     * 文章删除
     * @return mixed
     */
    public function articleDelete(){
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
        $res = $this->articleService->articleDelete($data);
        if($res){
            return show($this->ok,$this->articleService->message,$res);
        }else{
            return show($this->fail,$this->articleService->error);
        }
    }
}