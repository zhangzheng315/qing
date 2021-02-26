<?php

namespace app\index\controller;

use think\Controller;

class Docs extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    /* 文章 */
    public function article()
    {
        return $this->fetch();
    }
}