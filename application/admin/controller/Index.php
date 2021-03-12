<?php
namespace app\admin\controller;
use app\admin\serve\ArticleService;
use app\admin\serve\CaseService;
use app\admin\serve\VideoService;

class Index extends Common{

    public function index(){
        $article_service = new ArticleService();
        $case_service = new CaseService();
        $video_service = new VideoService();
        $time = date('Y-m-d H:i:s');
        $article_count = $article_service->articleCount();
        $case_count = $case_service->caseCount();
        $video_count = $video_service->videoCount();
        $data_arr = [
            [
                'name' => '文章数量',
                'count' => $article_count,
                'link'=>'/admin/article/index'
            ],
            [
                'name' => '案例数量',
                'count' => $case_count,
                'link'=>'/admin/case_m/index'
            ],
            [
                'name' => '视频数量',
                'count' => $video_count,
                'link'=>'/admin/video/index'
            ],
        ];
        return $this->fetch('',compact('time','data_arr'));
    }

}