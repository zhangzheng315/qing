<?php
namespace app\admin\controller;

use app\admin\serve\NavigationService;
use app\admin\serve\VideoTypeService;
use think\Request;
use think\Validate;

class VideoType extends Common{
    public $videoTypeService;
    public $navigationService;
    public function __construct(VideoTypeService $videoTypeService, NavigationService $navigationService)
    {
        parent::__construct();
        $this->videoTypeService = $videoTypeService;
        $this->navigationService = $navigationService;
    }

    public function index()
    {
        $add_url = '/admin/video_type/videoTypeCreate';
        $edit_url = '/admin/video_type/videoTypeEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取视频分类列表
     */
    public function getVideoTypeList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = VideoTypeService::data_paging($data,'video_type','order');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加视频分类
     * @return mixed
     */
    public function videoTypeCreate(Request $request){
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
        $res = $this->videoTypeService->videoTypeCreate($request->param());
        if($res){
            return show($this->ok,$this->videoTypeService->message);
        }else{
            return show($this->fail,$this->videoTypeService->error);
        }
    }

    /**
     * 视频分类详情
     * @return mixed
     */
    public function videoTypeInfo(Request $request){
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
        $res = $this->videoTypeService->videoTypeInfo($request->param());
        if($res){
            return show($this->ok,$this->videoTypeService->message,$res);
        }else{
            return show($this->fail,$this->videoTypeService->error);
        }
    }

    /**
     * 视频分类修改
     * @return mixed
     */
    public function videoTypeEdit(){
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
        $res = $this->videoTypeService->videoTypeEdit($data);
        if($res){
            return show($this->ok,$this->videoTypeService->message,$res);
        }else{
            return show($this->fail,$this->videoTypeService->error);
        }
    }

    /**
     * 视频分类删除
     * @return mixed
     */
    public function videoTypeDelete(){
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
        $res = $this->videoTypeService->videoTypeDelete($data);
        if($res){
            return show($this->ok,$this->videoTypeService->message,$res);
        }else{
            return show($this->fail,$this->videoTypeService->error);
        }
    }
}