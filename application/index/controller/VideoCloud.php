<?php

namespace app\index\controller;

use think\Controller;

class VideoCloud extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}