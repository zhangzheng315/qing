<?php

namespace app\index\controller;

use think\Controller;

class Deploy extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}