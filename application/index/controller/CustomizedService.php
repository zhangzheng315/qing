<?php

namespace app\index\controller;

use think\Controller;

class customizedService extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}