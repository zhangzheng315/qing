<?php

namespace app\index\controller;

// use app\admin\serve\JoinUsService;
use app\admin\serve\ArticleService;
use app\admin\serve\LabelService;
use app\admin\serve\QBannerService;
use think\Controller;
use think\Request;

class QingSchool extends Controller
{
    public $qBannerService;
    public $articleService;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->qBannerService = new QBannerService();
        $this->articleService = new ArticleService();
        $this->labelService = new LabelService();
        $list = $this->articleService->hotArticleList();
        $hot_label = $this->labelService->hotLabelList();
        $this->assign('list', $list);
        $this->assign('hot_label', $hot_label);
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
        $banner_list = $this->qBannerService->QBannerListByPid(1);
        $article_list = $this->articleService->articleByPid(1);
        return $this->fetch('',compact('banner_list','article_list'));
    }

    /*产品动态 */
    public function products()
    {
        $banner_list = $this->qBannerService->QBannerListByPid(2);
        $article_list = $this->articleService->articleByPid(2);
        return $this->fetch('',compact('banner_list','article_list'));
    }

    /* 直播资讯*/
    public function liveNews()
    {
        $banner_list = $this->qBannerService->QBannerListByPid(3);
        $article_list = $this->articleService->articleByPid(3);
        return $this->fetch('',compact('banner_list','article_list'));
    }

    public function courseSecond()
    {
        return $this->fetch();
    }
}