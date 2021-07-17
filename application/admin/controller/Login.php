<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\serve\ServeLogin;
use think\Validate;

class Login extends Controller{

    public function index(){
        return $this->fetch();
    }

    public function login(){
        $data = input('post.');
        $rules =
        [
            'email' => 'require',
            'pwd' => 'require',
        ];
        $msg =
        [
            'email' => '缺少参数@email',
            'pwd' => '缺少参数@pwd',
        ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show(401,$validate->getError());
        }
        $str = ServeLogin::logining($data);
        return $str;
    }

    public function logout(){
        session('uid',null);
        $this->redirect('/admin/Login/index');
    }

}