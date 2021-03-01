<?php
namespace app\admin\controller;

use app\admin\serve\QBannerService;
use think\Request;
use think\Validate;

class QBanner extends Common{
    public $QBannerService;
    public function __construct(QBannerService $QBannerService)
    {
        parent::__construct();
        $this->QBannerService = $QBannerService;
    }

    public function index()
    {
        $add_url = '/admin/q_banner/QBannerCreate'; //添加
        $edit_url = '/admin/q_banner/QBannerEdit';  //修改
        $pid_list = $this->QBannerService->pid_arr;
        return $this->fetch('',compact('add_url','edit_url','pid_list'));
    }

    /**
     * 分页获取轮播图列表
     */
    public function getQBannerList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = QBannerService::data_paging($data,'q_banner','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加轮播图
     * @return mixed
     */
    public function QBannerCreate(Request $request){
        $rules =
            [
                'img_url' => 'require',
                'pid' => 'require',
            ];
        $msg =
            [
                'img_url' => '缺少参数@img_url',
                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->QBannerService->QBannerCreate($request->param());
        if($res){
            return show($this->ok,$this->QBannerService->message);
        }else{
            return show($this->fail,$this->QBannerService->error);
        }
    }

    /**
     * 轮播图详情
     * @return mixed
     */
    public function QBannerInfo(Request $request){
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
        $res = $this->QBannerService->QBannerInfo($request->param());
        if($res){
            return show($this->ok,$this->QBannerService->message,$res);
        }else{
            return show($this->fail,$this->QBannerService->error);
        }
    }

    /**
     * 修改轮播图
     * @return mixed
     */
    public function QBannerEdit(){
        $data = input('post.');
        $rules =
            [
                'img_url' => 'require',
                'pid' => 'require',
            ];
        $msg =
            [
                'img_url' => '缺少参数@img_url',
                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->QBannerService->QBannerEdit($data);
        if($res){
            return show($this->ok,$this->QBannerService->message);
        }else{
            return show($this->fail,$this->QBannerService->error);
        }
    }

    /**
     * 删除轮播图
     * @return mixed
     */
    public function QBannerDelete(Request $request){
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
        $res = $this->QBannerService->QBannerDelete($request->param());
        if($res){
            return show($this->ok,$this->QBannerService->message);
        }else{
            return show($this->fail,$this->QBannerService->error);
        }
    }
}