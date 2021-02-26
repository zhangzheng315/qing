<?php

namespace app\index\controller;

use app\admin\serve\NavigationService;
use think\Controller;

class Navigation extends Controller
{
    public function navigationListClass()
    {
        $navigation_service = new NavigationService();
        $nav_list = $navigation_service->navigationListClass();
        return ['status'=>200,'nav_list'=>$nav_list];
    }
}