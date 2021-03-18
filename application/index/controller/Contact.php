<?php

namespace app\index\controller;

use app\admin\serve\ContactService;
use app\admin\serve\JoinUsService;
use think\Controller;

class Contact extends Controller
{
    
    public function contactUs()
    {
        $contactService = new ContactService();
        $contact_list = $contactService->contactList();
        return $this->fetch('',compact('contact_list'));
    }
    
}