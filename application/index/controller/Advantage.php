<?php

namespace app\index\controller;

use app\admin\serve\ThemeService;
use app\admin\serve\LogoWallService;
use think\Controller;

class Advantage extends Controller
{
    public function index()
    {
        $logo_service = new LogoWallService();
        $advantage_logo = $logo_service->logoListAdvantage();
        return $this->fetch('',compact('advantage_logo'));
    }

    public function caseListByTypeId($pid)
    {
        $case_service = new ThemeService();
        $res = $case_service->caseListByPid($pid);
        if($res){
            return show(200,$case_service->message,$res);
        }else{
            return show(401,$case_service->error);
        }
    }
}