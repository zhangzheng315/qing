<?php

namespace app\index\controller;

use app\admin\serve\VideoService;
use think\Controller;

class Tutorial extends Controller
{
    public function index()
    {
        $data = request()->param();
        $video_service = new VideoService();
        $info = $video_service->videoInfo($data);
        return $this->fetch('',compact('info'));
    }
}