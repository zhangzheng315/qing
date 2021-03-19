<?php
namespace app\admin\serve;
use app\admin\serve\Common;
use app\admin\model\Admin;
use think\Log;


class ServeLogin extends Common{
    
    public static function logining($data){
        try{
            $adminModel = new Admin();
            $userinfo = $adminModel->where('email',$data['email'])->find();
            if(!$userinfo){
                return show(401,'账号不存在！');
            }
            if($userinfo['pwd'] != md5($data['pwd'])){
                return show(401,'密码不正确！');
            }
            session('uid',$userinfo['id']);
            return show(200,'登陆成功！');
        }catch(\Exception $e){
            Log::error('admin/ServeLogin/logining:'.$e->getMessage());
            return false;
        }
    }

}