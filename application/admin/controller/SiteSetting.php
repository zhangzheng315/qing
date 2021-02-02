<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\serve\ServeSiteSetting;
use think\Validate;

class SiteSetting extends Common{

    public function index(){
        $siteInfo = ServeSiteSetting::data_one_info(['id'=>1],'site_setting');
        return $this->fetch('',['info'=>$siteInfo]);
    }

    public function saveSiteSetting(){
        $data = input('post.');
        $rules =
        [
            'siteTitle' => 'require',
            'icp' => 'require',
        ];
        $msg =
        [
            'siteTitle' => '缺少参数@siteTitle',
            'icp' => '缺少参数@icp',
        ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $str = ServeSiteSetting::saveSiteSettingServe($data);
        if($str){
            return show($this->ok,'更新成功');
        }else{
            return show($this->fail,'更新失败');
        }
    }

}