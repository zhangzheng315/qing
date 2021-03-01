<?php
namespace app\admin\controller;

use app\admin\serve\ArticleService;
use app\admin\serve\ArticleTypeService;
use app\admin\serve\ContentCenterService;
use app\admin\serve\HotArticleService;
use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class ContentCenter extends Common{
    public $contentCenterService;
    public $articleTypeService;
    public $labelService;
    public function __construct(ContentCenterService $contentCenterService, ArticleTypeService $articleTypeService, LabelService $labelService)
    {
        parent::__construct();
        $this->contentCenterService = $contentCenterService;
        $this->articleTypeService = $articleTypeService;
        $this->labelService = $labelService;
    }

    public function index()
    {
        $add_url = '';
        $edit_url = '/admin/content_center/contentCenterEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getContentCenterList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = ContentCenterService::data_paging($data,'content_center','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 文章详情
     * @return mixed
     */
    public function contentCenterInfo(Request $request){
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
        $res = $this->contentCenterService->contentCenterInfo($request->param());
        if($res){
            return show($this->ok,$this->contentCenterService->message,$res);
        }else{
            return show($this->fail,$this->contentCenterService->error);
        }
    }

    /**
     * 文章修改
     * @return mixed
     */
    public function contentCenterEdit(){
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
        $res = $this->contentCenterService->contentCenterEdit($data);
        if($res){
            return show($this->ok,$this->contentCenterService->message,$res);
        }else{
            return show($this->fail,$this->contentCenterService->error);
        }
    }
}