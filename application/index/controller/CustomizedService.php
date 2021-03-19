<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use think\Controller;

class CustomizedService extends Controller
{
    //定制化服务--直播集成开发
    public function index()
    {
        $banner_service = new BannerService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 13;
        $banner_list = $banner_service->bannerListByPid($pid);
        return $this->fetch('',compact('banner_list'));
    }

    //定制化服务--app/小程序
    public function applets()
    {
        $banner_service = new BannerService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 23;
        $banner_list = $banner_service->bannerListByPid($pid);
        return $this->fetch('',compact('banner_list'));
    }
}