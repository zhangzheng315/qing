<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use app\admin\serve\SolutionService;
use think\Controller;
use think\Request;

class Industry extends Controller
{
    public $banner_service;
    public $solution_service;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->banner_service = new BannerService();
        $this->solution_service = new SolutionService();
    }

    public function index()
    {
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 3;
        $banner_list = $this->banner_service->bannerListByPid($pid);
        $solution_list = $this->solution_service->solutionList();
        return $this->fetch('',compact( 'banner_list','solution_list'));
    }
    /* 行业解决方案-医疗*/
    public function medicalCare()
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
