<?php

namespace app\index\controller;

use app\admin\serve\ArticleService;
use app\admin\serve\ContactService;
use app\admin\serve\CorporateNewsService;
use app\admin\serve\JoinUsService;
use app\admin\serve\LabelService;
use think\Request;
use think\Controller;

class CorporateNews extends Controller
{
    public $labelService;
    public $corporateService;
    public $articleService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->labelService = new LabelService();
        $this->corporateService = new CorporateNewsService();
        $this->articleService = new ArticleService();
        $label_list = $this->labelService->labelList();
        $list = $this->articleService->hotArticleList();
        $this->assign('label_list', $label_list);
        $this->assign('list', $list);
    }

    
    public function news()
    {
        $joinUsService = new JoinUsService();
        $contactService = new ContactService();
        $join_list = $joinUsService->joinUsList();
        $contact_list = $contactService->contactList();
        $limit_list = $this->corporateService->corporateLimit();
        return $this->fetch('',compact('join_list','contact_list','limit_list'));
    }
    public function corporateDetail()
    {
        $param = request()->param();
        $id = $param['id'];

        $info = $this->corporateService->newInfoById($id);
        $pid_name = '企业新闻';
        $pid_url = '/index/corporate_news/categoryNews';
        $action = 'categoryNews';

        $pid = ['pid_name' => $pid_name, 'pid_url' => $pid_url,'action'=>$action];

        return $this->fetch('',compact('info','pid'));
    }

    public function categoryNews(Request $request)
    {
        $param = $request->param();
        $new_list = $this->corporateService->newByWhere($param);
        return $this->fetch('',compact('new_list'));

    }
}