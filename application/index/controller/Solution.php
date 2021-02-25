<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use think\Controller;

class Solution extends Controller
{
    public function index()
    {
        $banner_service = new BannerService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 3;
        $banner_list = $banner_service->bannerListByPid($pid);
        return $this->fetch('',compact( 'banner_list'));
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
    public function onlineClass()
    {
      return $this->fetch();
    }
}
