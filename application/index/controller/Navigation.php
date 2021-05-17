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
        $url = rtrim($url, '/.html');
        $last = substr($url, -1);
        $url_arr = explode('/', $url);
        $last_url = $url_arr[count($url_arr)-1];
        if (strstr($last_url, '.')) {
            $nav_id = ['pid'=>1];
        }else{
            $nav_id = $navigation_service->getActive($last_url);
        }
        return ['status'=>200,'nav_list'=>$nav_list,'nav_id'=>$nav_id];
    }
}