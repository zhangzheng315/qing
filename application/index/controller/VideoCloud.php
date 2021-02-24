<?php

namespace app\index\controller;

use app\admin\model\Banner;
use think\Controller;

class VideoCloud extends Controller
{
    //视频云服务--轻直播平台
    public function index()
    {
        $banner_model = new Banner();
        $where = [
            'status' => 1,
            'deleted_time' => 0,
            'pid' => 12,
        ];
        $banner_list = $banner_model->where($where)->select();
        return $this->fetch('',compact('banner_list'));
    }
}