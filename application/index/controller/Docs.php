<?php

namespace app\index\controller;

use app\admin\serve\CommonArticleService;
use think\Controller;

class Docs extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    /* 文章 */
    public function article($id)
    {
        $common_article_service = new CommonArticleService();

        return $this->fetch();
    }
}