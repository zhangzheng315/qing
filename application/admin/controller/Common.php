<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;


class Common extends Controller{

    /**
     * 定义公共变量
     */
    public $ok = 200;
    public $fail = 401;
    public $code = 0;
    public $user;
    public $menu_list;

    /**
     * 初始化方法
     */
    public function _initialize(){
        // $this->islogin();   //判断用户是否需要登录
        // $this->getuser();   //获取用户详细信息
         $this->menu();      //获取菜单列表
    }

    /**
     * 判断是否登录
     *
     * @return void
     */
    public function islogin(){
        if(empty($_SESSION['uid'])){
//        if(empty(Session::get('uid'))){
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

    public function menu()
    {
        $menu_admin_list = db('menu')->where(['deleted_time'=>0,'status'=>1])->field('menu_name,id')->select();
        $this->assign('menu_admin_list',$menu_admin_list);
    }



}