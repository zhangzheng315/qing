<?php

namespace app\index\controller;

// use app\admin\serve\JoinUsService;
use app\admin\serve\ArticleService;
use app\admin\serve\LabelService;
use app\admin\serve\QBannerService;
use app\admin\serve\VideoService;
use app\admin\serve\VideoTypeService;
use think\Controller;
use think\Request;

class QingSchool extends Controller
{
    public $qBannerService;
    public $articleService;
    public $labelService;
    public $videoTypeService;
    public $videoService;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->qBannerService = new QBannerService();
        $this->articleService = new ArticleService();
        $this->labelService = new LabelService();
        $this->videoTypeService = new VideoTypeService();
        $this->videoService = new VideoService();
        $list = $this->articleService->hotArticleList();
        $hot_label = $this->labelService->hotLabelList();
        $video_type = $this->videoTypeService->videoTypeList();
        $this->assign('list', $list);
        $this->assign('hot_label', $hot_label);
        $this->assign('video_type', $video_type);
    }

    /* 内容中心*/
    public function index()
    {
        $banner_list = $this->qBannerService->QBannerListByPid(0);
        $article_list = $this->articleService->articleContentCenter();

        return $this->fetch('', compact('banner_list', 'article_list'));
    }

    public function videoCourse()
    {
        $first_video = $this->videoService->videoHomeFirst();
        return $this->fetch('',compact('first_video'));
    }

    /* 案例解析*/
    public function caseAn()
    {
        $banner_list = $this->qBannerService->QBannerListByPid(1);
        $article_list = $this->articleService->articleByPid(1);
        return $this->fetch('', compact('banner_list', 'article_list'));
    }

    /*产品动态 */
    public function products()
    {
        $banner_list = $this->qBannerService->QBannerListByPid(2);
        $article_list = $this->articleService->articleByPid(2);
        return $this->fetch('', compact('banner_list', 'article_list'));
    }

    /* 直播资讯*/
    public function liveNews()
    {
        $banner_list = $this->qBannerService->QBannerListByPid(3);
        $article_list = $this->articleService->articleByPid(3);
        return $this->fetch('', compact('banner_list', 'article_list'));
    }

    public function courseSecond()
    {
        return $this->fetch();
    }

    /* 新闻详情页 */
    public function newsDetail($id,$pid)
    {
        $article_info = $this->articleService->articleInfoById($id,$pid);
        switch ($pid) {
            case 0:
                $top_name = '内容中心';
                $top_url = '/index/qing_school/index';
                break;
            case 1:
                $top_name = '案例解析';
                $top_url = '/index/qing_school/caseAn';
                break;
            case 2:
                $top_name = '产品动态';
                $top_url = '/index/qing_school/products';
                break;
            case 3:
                $top_name = '直播资讯';
                $top_url = '/index/qing_school/liveNews';
                break;
            default :
                $top_name = '';
                $top_url = '';
        }
        if (!$top_name) {
            $top_name = $article_info['info']['pid_name'];
            switch ($top_name) {
                case '内容中心':
                    $top_url = '/index/qing_school/index';
                    break;
                case '案例解析':
                    $top_url = '/index/qing_school/caseAn';
                    break;
                case '产品动态':
                    $top_url = '/index/qing_school/products';
                    break;
                case '直播资讯':
                    $top_url = '/index/qing_school/liveNews';
                    break;
            }
        }
        return $this->fetch('',compact('article_info','pid','top_name','top_url'));
    }
}