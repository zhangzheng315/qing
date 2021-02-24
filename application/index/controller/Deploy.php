<?php

namespace app\index\controller;

use app\admin\model\Banner;
use think\Controller;

class Deploy extends Controller
{
    //视频云服务--私有化部署
    public function index()
    {
        $banner_model = new Banner();
        $where = [
            'status' => 1,
            'deleted_time' => 0,
            'pid' => 10,
        ];
        $banner_list = $banner_model->where($where)->select();
        return $this->fetch('',compact('banner_list'));
    }
}