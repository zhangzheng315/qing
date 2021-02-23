<?php

namespace app\index\controller;

use app\admin\model\Banner;
use app\admin\serve\CaseService;
use think\Controller;

class CaseCenter extends Controller
{
    public function index()
    {
        //精选案例
        $case_service = new CaseService();
        $case_selected = $case_service->getCaseSelected();
        //案例轮播图
        $banner_model = new Banner();
        $where = [
            'status' => 1,
            'deleted_time' => 0,
            'pid' => 4,
        ];
        $banner_list = $banner_model->where($where)->select();
        return $this->fetch('',compact('case_selected', 'banner_list'));
    }
}