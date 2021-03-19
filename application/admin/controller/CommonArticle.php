<?php
namespace app\admin\controller;

use app\admin\serve\ArticleService;
use app\admin\serve\CommonArticleService;
use app\admin\serve\CommonArticleTypeService;
use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class CommonArticle extends Common{
    public $commonArticleService;
    public $labelService;
    public $commonTypeService;
    public function __construct(CommonArticleService $commonArticleService, LabelService $labelService, CommonArticleTypeService $commonArticleTypeService)
    {
        parent::__construct();
        $this->commonArticleService = $commonArticleService;
        $this->labelService = $labelService;
        $this->commonTypeService = $commonArticleTypeService;
    }

    public function index()
    {
        $article_type = $this->commonTypeService->articleTypeList();
        $label_list = $this->labelService->labelList();
        $add_url = '/admin/common_article/commonArticleCreate';
        $edit_url = '/admin/common_article/commonArticleEdit';
        return $this->fetch('',compact('article_type','label_list','add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getCommonArticleList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = CommonArticleService::data_paging($data,'common_article','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
            $value['hot'] = $value['hot'] == 1 ? '是' : '否';
            $value['content_center'] = $value['content_center'] == 1 ? '是' : '否';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function commonArticleCreate(Request $request){
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
        $res = $this->commonArticleService->commonArticleCreate($request->param());
        if($res){
            return show($this->ok,$this->commonArticleService->message);
        }else{
            return show($this->fail,$this->commonArticleService->error);
        }
    }

    /**
     * 文章详情
     * @return mixed
     */
    public function commonArticleInfo(Request $request){
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
        $res = $this->commonArticleService->commonArticleInfo($request->param());
        if($res){
            return show($this->ok,$this->commonArticleService->message,$res);
        }else{
            return show($this->fail,$this->commonArticleService->error);
        }
    }

    /**
     * 文章修改
     * @return mixed
     */
    public function commonArticleEdit(){
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
        $res = $this->commonArticleService->commonArticleEdit($data);
        if($res){
            return show($this->ok,$this->commonArticleService->message,$res);
        }else{
            return show($this->fail,$this->commonArticleService->error);
        }
    }

    /**
     * 文章删除
     * @return mixed
     */
    public function commonArticleDelete(){
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
        $res = $this->commonArticleService->commonArticleDelete($data);
        if($res){
            return show($this->ok,$this->commonArticleService->message,$res);
        }else{
            return show($this->fail,$this->commonArticleService->error);
        }
    }
}