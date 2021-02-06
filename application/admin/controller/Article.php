<?php
namespace app\admin\controller;

use app\admin\serve\ArticleService;
use app\admin\serve\NavigationService;
use think\Request;
use think\Validate;

class Article extends Common{
    public $articleService;
    public $navigationService;
    public function __construct(ArticleService $articleService, NavigationService $navigationService)
    {
        parent::__construct();
        $this->articleService = $articleService;
        $this->navigationService = $navigationService;
    }

    public function index()
    {
        $navigation_list = $this->navigationService->navigationList();
        $add_url = '/admin/article/articleCreate';
        $edit_url = '/admin/article/articleEdit';
        $del_url = '/admin/article/articleDelete';
        return $this->fetch('',compact('navigation_list','add_url','edit_url','del_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getArticleList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = ArticleService::data_paging($data,'article','order');
        $navigation_list = $this->navigationService->navigationList();
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
            foreach ($navigation_list as $item) {
                if ($item['id'] == $value['pid']) {
                    $value['belong'] = $item['menu_name'];
                }
            }
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