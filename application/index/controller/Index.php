<?php

namespace app\index\controller;

use app\admin\model\Banner;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $banner_model = new Banner();
        $where = [
            'status' => 1,
            'deleted_time' => 0,
            'pid' => 1,
        ];
        $banner_list = $banner_model->where($where)->select();
        return $this->fetch('',compact('banner_list'));
    }
}
