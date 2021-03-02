<?php

namespace app\index\controller;

// use app\admin\serve\JoinUsService;
use app\admin\serve\ArticleService;
use app\admin\serve\QBannerService;
use think\Controller;
use think\Request;

class QingSchool extends Controller
{
    public $qBannerService;
    public $articleService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->qBannerService = new QBannerService();
        $this->articleService = new ArticleService();
    }

    /* 内容中心*/
    public function index()
    {
        $banner_list = $this->qBannerService->QBannerListByPid(0);
        $article_list = $this->articleService->articleContentCenter();
        return $this->fetch('',compact('banner_list','article_list'));
    }

    public function videoCourse()
    {
        return $this->fetch();
    }

    /* 案例解析*/
    public function caseAn()
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
}