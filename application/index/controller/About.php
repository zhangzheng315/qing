<?php

namespace app\index\controller;

use app\admin\serve\AboutUsService;
use app\admin\serve\ContactService;
use app\admin\serve\DevelopmentService;
use think\Controller;

class About extends Controller
{
    public function index()
    {
        $development_service = new DevelopmentService();
        $about_service = new AboutUsService();
        $development_list = $development_service->developmentList();
        $about = $about_service->aboutUsInfo();
        return $this->fetch('',compact('development_list','about'));
    }
}