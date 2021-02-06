<?php
namespace app\admin\controller;

use app\admin\serve\BannerService;
use app\admin\serve\NavigationService;
use think\Request;
use think\Validate;

class Banner extends Common{
    public $bannerService;
    public $navigationService;
    public function __construct(BannerService $bannerService,NavigationService $navigationService)
    {
        parent::__construct();
        $this->bannerService = $bannerService;
        $this->navigationService = $navigationService;
    }

    public function index()
    {
        $navigation_list = $this->navigationService->navigationList();

        $add_url = '/admin/banner/bannerCreate'; //添加
        $edit_url = '/admin/banner/bannerEdit';  //修改
        $del_url = '/admin/banner/bannerDelete'; //删除
        return $this->fetch('',compact('navigation_list','add_url','edit_url','del_url'));
    }

    /**
     * 分页获取轮播图列表
     */
    public function getBannerList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = BannerService::data_paging($data,'banner','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加轮播图
     * @return mixed
     */
    public function bannerCreate(Request $request){
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
        $res = $this->bannerService->bannerCreate($request->param());
        if($res){
            return show($this->ok,$this->bannerService->message);
        }else{
            return show($this->fail,$this->bannerService->error);
        }
    }

    /**
     * 轮播图详情
     * @return mixed
     */
    public function bannerInfo(Request $request){
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
        $res = $this->bannerService->bannerInfo($request->param());
        if($res){
            return show($this->ok,$this->bannerService->message,$res);
        }else{
            return show($this->fail,$this->bannerService->error);
        }
    }

    /**
     * 修改导航栏
     * @return mixed
     */
    public function bannerEdit(){
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
        $res = $this->bannerService->bannerEdit($data);
        if($res){
            return show($this->ok,$this->bannerService->message);
        }else{
            return show($this->fail,$this->bannerService->error);
        }
    }

    /**
     * 删除导航栏
     * @return mixed
     */
    public function bannerDelete(Request $request){
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
        $res = $this->bannerService->bannerDelete($request->param());
        if($res){
            return show($this->ok,$this->bannerService->message);
        }else{
            return show($this->fail,$this->bannerService->error);
        }
    }
}