<?php

namespace app\index\controller;

use think\Controller;

class Channel extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}