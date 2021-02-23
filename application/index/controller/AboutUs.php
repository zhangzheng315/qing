<?php

namespace app\index\controller;

use think\Controller;

class AboutUs extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}