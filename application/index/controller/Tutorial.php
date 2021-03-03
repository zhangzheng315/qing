<?php

namespace app\index\controller;

use think\Controller;

class Tutorial extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}