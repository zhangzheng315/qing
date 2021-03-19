<?php

namespace app\index\controller;

use app\admin\serve\ContactService;
use app\admin\serve\JoinUsService;
use think\Controller;

class Join extends Controller
{
    
    public function joinUs()
    {
        $param = request()->param();
        $joinUsService = new JoinUsService();
        $join_list = $joinUsService->joinUsList($param);
        return $this->fetch('',compact('join_list'));
    }
    
}