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
        $pid = isset($data['id']) ? $data['id'] : 4;
        $banner_list = $banner_service->bannerListByPid($pid);
        return $this->fetch('',compact( 'banner_list'));
    }
    /* 解决方案-企业会议*/
    public function Corporate()
    {
      return $this->fetch();
    }
    /* 解决方案-医学教育 */
    public function Medical()
    {
      return $this->fetch();
    }
    /* 解决方案-企业培训*/
    public function Training()
    {
      return $this->fetch();
    }
    /* 解决方案-在线课堂 */
    public function Onlineclass()
    {
      return $this->fetch();
    }
}
