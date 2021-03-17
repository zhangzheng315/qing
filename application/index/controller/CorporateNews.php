<?php

namespace app\index\controller;

use app\admin\serve\ContactService;
use app\admin\serve\JoinUsService;
use app\admin\serve\LabelService;
use think\Request;
use think\Controller;

class CorporateNews extends Controller
{
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->labelService = new LabelService();
        $label_list = $this->labelService->labelList();
        $this->assign('label_list', $label_list);
    }

    
    public function news()
    {
        $joinUsService = new JoinUsService();
        $contactService = new ContactService();
        $join_list = $joinUsService->joinUsList();
        $contact_list = $contactService->contactList();
        return $this->fetch('',compact('join_list','contact_list'));
    }
    public function coporateDetail()
    {
        $param = request()->param();
        $id = $param['id'];
        $type_id = $param['type_id'];
        $param['browse'] = 1;

        $info = $this->case_service->caseInfo($param);
        switch ($info['pid']) {
            case 1:
                $pid_name = '医疗';
                $pid_url = '/index/case_center/medical';
                $action = 'medical';
                break;
            case 2:
                $pid_name = '教育';
                $pid_url = '/index/case_center/education';
                $action = 'education';
                break;
            case 3:
                $pid_name = '金融';
                $pid_url = '/index/case_center/finance';
                $action = 'finance';
                break;
            case 4:
                $pid_name = '汽车';
                $pid_url = '/index/case_center/car';
                $action = 'car';
                break;
            case 5:
                $pid_name = '科技';
                $pid_url = '/index/case_center/technology';
                $action = 'technology';
                break;
            case 6:
                $pid_name = '地产';
                $pid_url = '/index/case_center/property';
                $action = 'property';
                break;
        }
        $pid = ['pid_name' => $pid_name, 'pid_url' => $pid_url,'action'=>$action];
        $pre_and_nex = $this->case_service->preAndNext($id, $type_id, $info['pid']);

        return $this->fetch('',compact('info','pid','pre_and_nex'));
    }

    public function categoryNews()
    {
        return $this->fetch();
    }
}