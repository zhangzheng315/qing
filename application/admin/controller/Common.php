<?php
namespace app\admin\controller;
use think\Controller;


class Common extends Controller{

    /**
     * 定义公共变量
     */
    public $ok = 200;
    public $fail = 401;
    public $code = 0;
    public $user;

    /**
     * 初始化方法
     */
    public function _initialize(){
        // $this->islogin();   //判断用户是否需要登录
        // $this->getuser();   //获取用户详细信息
        // $this->menu();      //获取菜单列表
    }

    /**
     * 判断是否登录
     *
     * @return void
     */
    public function islogin(){
        if(empty(session('uid'))){
            $this->redirect('Login/index');
        }
    }

    /**
     * 查询用户详细信息
     *
     * @return void
     */
    public function getuser(){
        try{
            $user = db('admin')->where('id',session('uid'))->find();
        }catch(\Exception $e){
            return show($this->fail,$e->getMessage());
        }
        $this->user = $user;
    }



}