<?php

namespace app\index\controller;

// use app\admin\serve\JoinUsService;
use app\admin\serve\ArticleService;
use app\admin\serve\CaseService;
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
        $id = 1;
        $first_video = $this->videoService->videoHomeFirst();
        return $this->fetch('',compact('first_video','id'));
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

    /**
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

    public function liveCurl($url)
    {
        $data = $this->liveCurl('https://images.innocomn.com/20210219115134-UBXOL0-aboutUs.mp4?avinfo');
        $json = json_decode($data);
        dd((int)$json->format->duration);

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); //  如果不是https的就注释 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作

        curl_close($curl); // 关闭CURL会话
        return $res;
    }

    public function articleSearch()
    {
        $param = request()->param();
        $search_list = $this->articleService->articleSearch($param['pid'], $param['word']);
        if($search_list){
            return show(200,$this->articleService->message,$search_list);
        }else{
            return show(401,$this->articleService->error);
        }
    }
}