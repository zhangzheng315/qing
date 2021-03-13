<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use app\admin\serve\SolutionService;
use think\Controller;

class Industry extends Controller
{
    public function index()
    {
        $banner_service = new BannerService();
        $solution_service = new SolutionService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 3;
        $banner_list = $banner_service->bannerListByPid($pid);
        $solution_list = $solution_service->solutionList();
        return $this->fetch('',compact( 'banner_list','solution_list'));
    }
    /* 行业解决方案-医疗*/
    public function medical_care()
    {
      return $this->fetch();
    }

    /* 行业解决方案-教育*/
    public function education()
    {
      return $this->fetch();
    }

    /* 行业解决方案-金融*/
    public function banking()
    {
      return $this->fetch();
    }

    /* 行业解决方案-传媒*/
    public function media()
    {
      return $this->fetch();
    }
    
}
