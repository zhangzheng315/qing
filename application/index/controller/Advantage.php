<?php

namespace app\index\controller;

use app\admin\serve\CaseService;
use app\admin\serve\CaseTypeService;
use app\admin\serve\LogoWallService;
use think\Controller;

class Advantage extends Controller
{
    public function index()
    {
        $logo_service = new LogoWallService();
        $case_service = new CaseService();
        $case_type = new CaseTypeService();
        $advantage_logo = $logo_service->logoListAdvantage();
        $advantage_case = $case_service->caseAdvantage();
        $case_type_list = $case_type->caseTypeList();
        return $this->fetch('',compact('advantage_logo','advantage_case','case_type_list'));
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