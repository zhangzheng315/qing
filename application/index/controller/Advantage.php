<?php

namespace app\index\controller;

use app\admin\serve\CaseService;
use app\admin\serve\LogoWallService;
use think\Controller;

class Advantage extends Controller
{
    public function index()
    {
        $logo_service = new LogoWallService();
        $case_service = new CaseService();
        $advantage_logo = $logo_service->logoListAdvantage();
        $advantage_case = $case_service->caseAdvantage();
        return $this->fetch('',compact('advantage_logo','advantage_case'));
    }

    public function caseListByTypeId($pid)
    {
        $case_service = new CaseService();
        $res = $case_service->caseListByPid($pid);
        if($res){
            return show(200,$case_service->message,$res);
        }else{
            return show(401,$case_service->error);
        }
    }
}