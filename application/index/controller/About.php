<?php

namespace app\index\controller;

use app\admin\serve\ContactService;
use app\admin\serve\JoinUsService;
use think\Controller;

class About extends Controller
{
    public function index()
    {
        $joinUsService = new JoinUsService();
        $contactService = new ContactService();
        $join_list = $joinUsService->joinUsList();
        $contact_list = $contactService->contactList();
        return $this->fetch('',compact('join_list','contact_list'));
    }
    public function joinUs()
    {
        $joinUsService = new JoinUsService();
        $contactService = new ContactService();
        $join_list = $joinUsService->joinUsList();
        $contact_list = $contactService->contactList();
        return $this->fetch('',compact('join_list','contact_list'));
    }
    public function contactUs()
    {
        $joinUsService = new JoinUsService();
        $contactService = new ContactService();
        $join_list = $joinUsService->joinUsList();
        $contact_list = $contactService->contactList();
        return $this->fetch('',compact('join_list','contact_list'));
    }

    public function news()
    {
        $joinUsService = new JoinUsService();
        $contactService = new ContactService();
        $join_list = $joinUsService->joinUsList();
        $contact_list = $contactService->contactList();
        return $this->fetch('',compact('join_list','contact_list'));
    }
}