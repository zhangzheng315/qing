<?php

namespace app\index\controller;

use app\admin\model\Banner;
use app\admin\serve\ArticleService;
use app\admin\serve\BannerService;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $banner_service = new BannerService();
        $article_service = new ArticleService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 1;
        $banner_list = $banner_service->bannerListByPid($pid);
        $article_list = $article_service->getFirstHome();
        return $this->fetch('', compact('banner_list', 'article_list'));
    }
    // 法律声明
    public function legalNotice()
    {
        return $this->fetch();
    }

    // 隐私协议
    public function privacy()
    {
        return $this->fetch();
    }

    // 服务协议
    public function serviceAgreement()
    {
        return $this->fetch();
    }

    // 增值电信经营许可证
    public function licence()
    {
        return $this->fetch();
    }
}
