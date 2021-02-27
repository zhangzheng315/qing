<?php

namespace app\index\controller;

// use app\admin\serve\JoinUsService;
use think\Controller;

class QingSchool extends Controller
{
    public function index()
    {
      return $this->fetch();
    }

    public function videoCourse()
    {
      return $this->fetch();
    }
    public function courseSecond()
    {
      return $this->fetch();
    }
}