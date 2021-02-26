<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use app\admin\serve\SolutionService;
use think\Controller;

class Solution extends Controller
{
    public function index()
    {
        $banner_service = new BannerService();
        $solution_service = new SolutionService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 3;
        $banner_list = $banner_service->bannerListByPid($pid);
        $solution_list = $solution_service->solutionList();
        return $this->fetch('',compact( 'banner_list','solution_list'));
    }
    /* 解决方案-企业会议*/
    public function corporate()
    {
      return $this->fetch();
    }
    /* 解决方案-医学教育 */
    public function medical()
    {
      return $this->fetch();
    }
    /* 解决方案-企业培训*/
    public function training()
    {
      return $this->fetch();
    }
    /* 解决方案-在线课堂 */
    public function onlineclass()
    {
      return $this->fetch();
    }
    /* 解决方案-营销活动*/
    public function marketactivite()
    {
      return $this->fetch();
    }
    /* 手术示教*/
    public function operation()
    {
      return $this->fetch();
    }
    /* 金融保险 */
    public function financial()
    {
      return $this->fetch();
    }
    /* 大会直播*/
    public function meetinglive()
    {
      return $this->fetch();
    }
}
