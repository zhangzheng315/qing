<?php

namespace app\index\controller;

use think\Controller;

class SiteService extends Controller
{
    //现场服务
    public function index()
    {
        return $this->fetch();
    }
}
