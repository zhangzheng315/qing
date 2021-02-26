<?php

namespace app\index\controller;

use app\admin\model\Banner;
use app\admin\serve\BannerService;
use think\Controller;

class VideoCloud extends Controller
{
    //视频云服务--轻直播平台
    public function index()
    {
        $banner_service = new BannerService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 12;
        $banner_list = $banner_service->bannerListByPid($pid);
        return $this->fetch('',compact('banner_list'));
    }
}