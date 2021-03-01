<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use think\Controller;

class SiteService extends Controller
{
    //现场服务
    public function index()
    {
        $banner_service = new BannerService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 24;
        $banner_list = $banner_service->bannerListByPid($pid);
        return $this->fetch('',compact( 'banner_list'));
    }
}
