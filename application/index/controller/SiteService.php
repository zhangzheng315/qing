<?php

namespace app\index\controller;

use think\Controller;

class SiteService extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}
