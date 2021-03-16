<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use app\admin\serve\CommonArticleService;
use app\admin\serve\VideoService;
use think\Controller;

class MoreLive extends Controller
{
    public function index()
    {
        $banner_service = new BannerService();
        $common_article_service = new CommonArticleService();
        $video_service = new VideoService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 26;
        $banner_list = $banner_service->bannerListByPid($pid);
        $article_list = $common_article_service->commonArticleList();
        $new = $common_article_service->newAdd();
        $more_video = $video_service->moreLiveVideo();

        return $this->fetch('',compact('banner_list','article_list','new','more_video'));
    }
}