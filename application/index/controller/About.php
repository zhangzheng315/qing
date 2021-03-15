<?php

namespace app\index\controller;

use app\admin\serve\ContactService;
use app\admin\serve\DevelopmentService;
use app\admin\serve\JoinUsService;
use think\Controller;

class About extends Controller
{
    public function index()
    {
        $joinUsService = new JoinUsService();
        $contactService = new ContactService();
        $development_service = new DevelopmentService();
        $join_list = $joinUsService->joinUsList();
        $contact_list = $contactService->contactList();
        $development_list = $development_service->developmentList();
        return $this->fetch('',compact('join_list','contact_list','development_list'));
    }
}