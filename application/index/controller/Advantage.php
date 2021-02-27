<?php

namespace app\index\controller;

use app\admin\serve\CaseService;
use think\Controller;

class Advantage extends Controller
{
    public function index()
    {
        return $this->fetch();
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