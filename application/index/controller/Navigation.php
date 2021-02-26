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

        $url = request()->param()['url'];
        $last = substr($url, -1);
        if ($last == '/') {
            $nav_id = ['pid'=>1];
        }else{
            $url_arr = explode('/', $url);
            $controller = $url_arr[count($url_arr)-2];
            $nav_id = $navigation_service->getActive($controller);
        }
        return ['status'=>200,'nav_list'=>$nav_list,'nav_id'=>$nav_id];
    }
}