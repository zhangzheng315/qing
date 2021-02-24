<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use think\Controller;

class Deploy extends Controller
{
    //视频云服务--私有化部署
    public function index()
    {
        $banner_service = new BannerService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 10;
        $banner_list = $banner_service->bannerListByPid($pid);
        return $this->fetch('',compact('banner_list'));
    }
}