<?php

namespace app\index\controller;

use app\admin\serve\ThemeService;
use app\admin\serve\LogoWallService;
use think\Controller;

class Channel extends Controller
{
    public function index()
    {
        $logo_service = new LogoWallService();
        $advantage_logo = $logo_service->logoListAdvantage();
        return $this->fetch('',compact('advantage_logo'));
    }
}