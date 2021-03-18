<?php

namespace app\index\controller;

// use app\admin\serve\JoinUsService;
use app\admin\serve\ArticleService;
use app\admin\serve\ThemeService;
use app\admin\serve\CommonArticleService;
use app\admin\serve\LabelService;
use app\admin\serve\QBannerService;
use app\admin\serve\VideoService;
use app\admin\serve\VideoTypeService;
use think\Controller;
use think\Request;
use think\Validate;

class QingSchool extends Controller
{
    public $qBannerService;
    public $articleService;
    public $labelService;
    public $videoTypeService;
    public $videoService;
    public $common_article_service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->qBannerService = new QBannerService();
        $this->articleService = new ArticleService();
        $this->labelService = new LabelService();
        $this->videoTypeService = new VideoTypeService();
        $this->videoService = new VideoService();
        $this->common_article_service = new CommonArticleService();
        $list = $this->articleService->hotArticleList();
        $hot_label = $this->labelService->hotLabelList();
        $video_type = $this->videoTypeService->videoTypeList();
        $video_type_limit = $this->videoTypeService->videoTypeLimitList();
        $this->assign('list', $list);
        $this->assign('hot_label', $hot_label);
        $this->assign('video_type', $video_type);
        $this->assign('video_type_limit', $video_type_limit);
    }

    /* 内容中心*/
    public function index()
    {
        $param = request()->param();
        $banner_list = $this->qBannerService->QBannerListByPid(0);
        $article_list = $this->articleService->articleContentCenter($param);

        return $this->fetch('', compact('banner_list', 'article_list'));
    }

    public function videoCourse()
    {
        $id = 1;
        $first_video = $this->videoService->videoHomeFirst();
        return $this->fetch('',compact('first_video','id'));
    }

    /* 案例解析*/
    public function caseAn()
    {
        $param = request()->param();
        $param['pid'] = 1;
        $banner_list = $this->qBannerService->QBannerListByPid(1);
        $article_list = $this->articleService->articleByWhere($param);
        return $this->fetch('', compact('banner_list', 'article_list'));
    }

    /*产品动态 */
    public function products()
    {
        $param = request()->param();
        $param['pid'] = 2;
        $banner_list = $this->qBannerService->QBannerListByPid(2);
        $article_list = $this->articleService->articleByWhere($param);
        return $this->fetch('', compact('banner_list', 'article_list'));
    }

    /* 直播资讯*/
    public function liveNews()
    {
        $param = request()->param();
        $param['pid'] = 3;
        $banner_list = $this->qBannerService->QBannerListByPid(3);
        $article_list = $this->articleService->articleByWhere($param);
        return $this->fetch('', compact('banner_list', 'article_list'));
    }

    /**
     * 视频学院二级页面
     * @return mixed
     */
    public function courseSecond()
    {
        $param = request()->param();
        $pid = $param['pid'];
        $pid_name = $this->videoTypeService->videoTypeName($param['pid']);
        $video_list = $this->videoService->getVideoListByWhere($param);
        return $this->fetch('',compact('pid_name','video_list','pid'));
    }

    /* 新闻详情页 */
    public function newsDetail($id,$pid)
    {
        $article_info = $this->articleService->articleInfoById($id,$pid);
        switch ($pid) {
            case 0:
                $top_name = '内容中心';
                $top_url = '/blog';
                $action = 'index';
                break;
            case 1:
                $top_name = '案例解析';
                $top_url = '/blog/demo';
                $action = 'caseAn';
                break;
            case 2:
                $top_name = '产品动态';
                $top_url = '/blog/dynamic';
                $action = 'products';
                break;
            case 3:
                $top_name = '直播资讯';
                $top_url = '/blog/archives';
                $action = 'liveNews';
                break;
            default :
                $top_name = '';
                $top_url = '';
                $action = '';
        }
        if (!$top_name) {
            $top_name = $article_info['info']['pid_name'];
            switch ($top_name) {
                case '内容中心':
                    $top_url = '/blog';
                    $action = 'index';
                    break;
                case '案例解析':
                    $top_url = '/blog/demo';
                    $action = 'caseAn';
                    break;
                case '产品动态':
                    $top_url = '/blog/dynamic';
                    $action = 'products';
                    break;
                case '直播资讯':
                    $top_url = '/blog/archives';
                    $action = 'liveNews';
                    break;
            }
        }
        return $this->fetch('',compact('article_info','pid','top_name','top_url','action'));
    }

    /**
     * 按条件获取分类视频列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function getVideoListByWhere(Request $request){
        $rules =
            [
                'pid' => 'require',
            ];
        $msg =
            [
                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show(401,$validate->getError());
        }
        $video_service = new VideoService();
        $res = $video_service->getVideoListByWhere($request->param());
        if($res){
            return show(200,$video_service->message,$res);
        }else{
            return show(401,$video_service->error);
        }
    }


    // 直播百科
    public function article()
    {
        $param = request()->param();
        $param['browse'] = 1;
        $article_f = 0;
        $article_info = [];
        if (isset($param['id'])) {
            $article_f = 1;
            $article_info = $this->common_article_service->getArticleInfo($param);
        }
        $article = $this->common_article_service->commonArticleList();
        $article_list = $this->common_article_service->articleByWhere(['pid'=>1]);
        return $this->fetch('',compact('article','article_list','article_f','article_info'));
    }

    /**
     * 按条件获取文章百科   文章列表
     * @return array|int[]
     */
    public function articleByWhere()
    {
        $param = request()->param();
        $article_list = $this->common_article_service->articleByWhere($param);
        if (!$article_list) {
            return ['status'=>401];
        }
        return ['status'=>200,'data'=>$article_list];
    }

    /**
     * 文章百科--文章详情
     * @param $id
     * @return array|int[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function articleInfo($id)
    {
        $param = ['id' => $id];
        $info = $this->common_article_service->getArticleInfo($param);
        if (!$info) {
            return ['status'=>401];
        }
        return ['status'=>200,'data'=>$info];
    }

    /**
     * 视频分页列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function videoListByPage(Request $request){
        $rules =
            [
                'curr' => 'require',
            ];
        $msg =
            [
                'curr' => '缺少参数@curr',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show(401,$validate->getError());
        }
        $res = $this->videoService->videoListByPage($request->param());
        if($res){
            return show(200,$this->videoService->message,$res);
        }else{
            return show(401,$this->videoService->error);
        }
    }

    public function videoWordSearch()
    {
        $param = request()->param();
        $video_list = $this->videoService->videoWordSearch($param);
        if (!$video_list) {
            return ['status'=>401];
        }
        return ['status'=>200,'data'=>$video_list];
    }
}