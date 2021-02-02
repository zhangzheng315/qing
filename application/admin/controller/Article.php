<?php
namespace app\admin\controller;

use app\admin\serve\ArticleService;
use think\Request;
use think\Validate;

class Article extends Common{
    public $articleService;
    public function __construct(ArticleService $articleService)
    {
        parent::__construct();
        $this->articleService = $articleService;
    }

    public function index()
    {
        $navigation_list = db('navigation')->where(['deleted_time'=>0,'status'=>1])->field('menu_name,id')->select();
        return $this->fetch('',compact('navigation_list'));
    }

    /**
     * 分页获取文章列表
     */
    public function getArticleList(){
        $data = input('get.');
        $data['status'] = 1;
        $str = ArticleService::data_paging($data,'article','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加菜单
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
}