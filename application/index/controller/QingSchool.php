<?php

namespace app\index\controller;

// use app\admin\serve\JoinUsService;
use think\Controller;

class QingSchool extends Controller
{
    /* 内容中心*/
    public function index()
    {
      return $this->fetch();
    }

    public function videoCourse()
    {
      return $this->fetch();
    }
    /* 案例解析*/
    public function case()
    {
      return $this->fetch();
    } 
    /*产品动态 */
    public function products()
    {
      return $this->fetch();
    }
    /* 直播资讯*/
    public function liveNews()
    {
      return $this->fetch();
    }
    /* 新闻详情页 */
    public function newsDetail()
    {
      return $this->fetch();
    }
}