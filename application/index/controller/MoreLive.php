<?php

namespace app\index\controller;

use think\Controller;

class MoreLive extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}