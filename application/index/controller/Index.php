<?php

namespace app\index\controller;
use think\Db;
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
        $ad_list = $banner_service->bannerListByPid(10000); //广告
        $banner_list = array_merge($ad_list, $banner_list);
        $article_list = $article_service->getFirstHome();
        //
        $linkInfo = $this->linkList();

        return $this->fetch('', compact('banner_list', 'article_list','linkInfo'));
    }

    //获取友链数据
    public function linkList()
    {
        $where = [ 'status' => 1 ];
        $link_list = Db::name('link')->where($where)->order(['order','id'=>'desc'])->select();
        return $link_list;
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
        $type = input('get.type');
        $img = '';
        $title = '';
        if($type == 'licence'){
            $img = '/static/image/index1/licence.jpg';
            $title = '增值电信经营许可证';
        }else if($type == 'Weblog'){
            $img = '/static/image/index1/weblog.jpg';
            $title = '沪网文【2020】3647-260号';
        }else if($type == 'television'){
            $img = '/static/image/index1/television.jpeg';
            $title = '广播电视节目制作经营许可证（沪）字第04046号';
        }else if($type == 'ICP1'){
            $img = '/static/image/index1/ICP1.png';
            $title = 'ICP沪B2-20200997 [信息服务]';
        }else if($type == 'ICP2'){
            $img = '/static/image/index1/ICP2.png';
            $title = 'ICP B2-20200715 [多方通信]';
        }
        return $this->fetch('',['img'=>$img,'title'=>$title]);
    }
}
