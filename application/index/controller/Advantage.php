<?php

namespace app\index\controller;

use think\Controller;

class Advantage extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}