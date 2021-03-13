<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use app\admin\serve\SolutionService;
use think\Controller;
use think\Request;

class Solution extends Controller
{
    public $banner_service;
    public $solution_service;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->banner_service = new BannerService();
        $this->solution_service = new SolutionService();
    }

    public function index()
    {
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 3;
        $banner_list = $this->banner_service->bannerListByPid($pid);
        $solution_list = $this->solution_service->solutionList();
        return $this->fetch('',compact( 'banner_list','solution_list'));
    }
    /* 场景解决方案-会议培训*/
    public function train()
    {
      return $this->fetch();
    }
    /* 场景解决方案-营销直播*/
    public function marketActivities()
    {
        return $this->fetch();
    }
    /* 场景解决方案-电商直播 */
    public function onlineRetailers()
    {
        return $this->fetch();
    }
    /* 场景解决方案-企业宣讲会*/
    public function enterprise()
    {
      return $this->fetch();
    }
    /* 场景解决方案-医学会议 */
    public function medical()
    {
        return $this->fetch();
    }
    /* 场景解决方案-手术示教*/
    public function operation()
    {
      return $this->fetch();
    }
    /* 场景解决方案-大会直播*/
    public function meetingLive()
    {
        return $this->fetch();
    }
    /* 场景解决方案-年会直播 */
    public function annualMeeting()
    {
      return $this->fetch();
    }
}
