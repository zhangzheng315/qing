<?php
namespace app\admin\controller;

use app\admin\serve\BannerService;
use app\admin\serve\LabelService;
use app\admin\serve\NavigationService;
use app\admin\serve\VideoService;
use app\admin\serve\VideoTypeService;
use think\Request;
use think\Validate;

class Video extends Common{
    public $videoService;
    public $videoTypeService;
    public $labelService;
    public function __construct(VideoService $videoService,VideoTypeService $videoTypeService, LabelService $labelService)
    {
        parent::__construct();
        $this->videoService = $videoService;
        $this->videoTypeService = $videoTypeService;
        $this->labelService = $labelService;
    }

    public function index()
    {
        $video_type_list = $this->videoTypeService->videoTypeList();
        $label_list = $this->labelService->labelList();
        $add_url = '/admin/video/videoCreate'; //添加
        $edit_url = '/admin/video/videoEdit';  //修改
        return $this->fetch('',compact('video_type_list','label_list','add_url','edit_url'));
    }

    /**
     * 分页获取视频列表
     */
    public function getVideoList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = VideoService::data_paging($data,'video','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加视频
     * @return mixed
     */
    public function videoCreate(Request $request){
        $rules =
            [
                'pid' => 'require',
                'video_url' => 'require',
                'title' => 'require',
                'cover_img_url' => 'require',
            ];
        $msg =
            [
                'pid' => '缺少参数@pid',
                'video_url' => '缺少参数@video_url',
                'title' => '缺少参数@title',
                'cover_img_url' => '缺少参数@cover_img_url',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->videoService->videoCreate($request->param());
        if($res){
            return show($this->ok,$this->videoService->message);
        }else{
            return show($this->fail,$this->videoService->error);
        }
    }

    /**
     * 视频详情
     * @return mixed
     */
    public function videoInfo(Request $request){
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
        $res = $this->videoService->videoInfo($request->param());
        if($res){
            return show($this->ok,$this->videoService->message,$res);
        }else{
            return show($this->fail,$this->videoService->error);
        }
    }

    /**
     * 修改视频
     * @return mixed
     */
    public function videoEdit(){
        $data = input('post.');
        $rules =
            [
                'pid' => 'require',
                'video_url' => 'require',
                'title' => 'require',
                'cover_img_url' => 'require',
            ];
        $msg =
            [
                'pid' => '缺少参数@pid',
                'video_url' => '缺少参数@video_url',
                'title' => '缺少参数@title',
                'cover_img_url' => '缺少参数@cover_img_url',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->videoService->videoEdit($data);
        if($res){
            return show($this->ok,$this->videoService->message);
        }else{
            return show($this->fail,$this->videoService->error);
        }
    }

    /**
     * 删除视频
     * @return mixed
     */
    public function videoDelete(Request $request){
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
        $res = $this->videoService->videoDelete($request->param());
        if($res){
            return show($this->ok,$this->videoService->message);
        }else{
            return show($this->fail,$this->videoService->error);
        }
    }
}