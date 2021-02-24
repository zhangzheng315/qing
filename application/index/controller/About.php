<?php

namespace app\index\controller;

use app\admin\serve\JoinUsService;
use think\Controller;

class About extends Controller
{
    public function index()
    {
        $joinUsService = new JoinUsService();
        $join_list = $joinUsService->joinUsList();
        return $this->fetch('',compact('join_list'));
    }
}