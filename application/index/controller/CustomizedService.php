<?php

namespace app\index\controller;

use app\admin\model\Banner;
use think\Controller;

class CustomizedService extends Controller
{
    //定制化服务--直播集成开发
    public function index()
    {
        $banner_model = new Banner();
        $where = [
            'status' => 1,
            'deleted_time' => 0,
            'pid' => 13,
        ];
        $banner_list = $banner_model->where($where)->select();
        return $this->fetch('',compact('banner_list'));
    }
}