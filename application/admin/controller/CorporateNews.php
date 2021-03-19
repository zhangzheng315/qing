<?php
namespace app\admin\controller;

use app\admin\serve\ArticleService;
use app\admin\serve\CorporateNewsService;
use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class CorporateNews extends Common{
    public $corporateNewsService;
    public $labelService;
    public function __construct(CorporateNewsService $corporateNewsService, LabelService $labelService)
    {
        parent::__construct();
        $this->corporateNewsService = $corporateNewsService;
        $this->labelService = $labelService;
    }

    public function index()
    {
        $label_list = $this->labelService->labelList();
        $add_url = '/admin/corporate_news/newCreate';
        $edit_url = '/admin/corporate_news/newEdit';
        return $this->fetch('',compact('label_list','add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getNewList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = CorporateNewsService::data_paging($data,'corporate_news','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function newCreate(Request $request){
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
        $res = $this->corporateNewsService->newCreate($request->param());
        if($res){
            return show($this->ok,$this->corporateNewsService->message);
        }else{
            return show($this->fail,$this->corporateNewsService->error);
        }
    }

    /**
     * 文章详情
     * @return mixed
     */
    public function newInfo(Request $request){
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
        $res = $this->corporateNewsService->newInfo($request->param());
        if($res){
            return show($this->ok,$this->corporateNewsService->message,$res);
        }else{
            return show($this->fail,$this->corporateNewsService->error);
        }
    }

    /**
     * 文章修改
     * @return mixed
     */
    public function newEdit(){
        $data = input('post.');
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
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->corporateNewsService->newEdit($data);
        if($res){
            return show($this->ok,$this->corporateNewsService->message,$res);
        }else{
            return show($this->fail,$this->corporateNewsService->error);
        }
    }

    /**
     * 文章删除
     * @return mixed
     */
    public function newDelete(){
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
        $res = $this->corporateNewsService->newDelete($data);
        if($res){
            return show($this->ok,$this->corporateNewsService->message,$res);
        }else{
            return show($this->fail,$this->corporateNewsService->error);
        }
    }
}