<?php

namespace app\index\controller;

use app\admin\serve\CommonArticleService;
use think\Controller;
use think\Request;

class Docs extends Controller
{
    public $common_article_service;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->common_article_service = new CommonArticleService();
        $article_list = $this->common_article_service->commonArticleList();
        $this->assign('article', $article_list);
    }

    public function index()
    {
        return $this->fetch();
    }
    /* 文章 */
    public function article($id)
    {
        $info = $this->common_article_service->getArticleInfo($id);

        return $this->fetch('',compact('info','id'));
    }
}