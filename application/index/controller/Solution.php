<?php

namespace app\index\controller;

use think\Controller;

class Solution extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}
