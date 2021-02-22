<?php

namespace app\index\controller;

use think\Controller;

class CaseCenter extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}