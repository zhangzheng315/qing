<?php

namespace app\admin\controller;

use think\Controller;

use app\admin\model\Admin;
use app\admin\model\AuthRule;

;


class Common extends Controller
{

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
    public function _initialize()
    {

        $this->islogin();   //判断用户是否需要登录
        $this->getuser();   //获取用户详细信息
        $this->menu();      //获取菜单列表
    }

    /**
     * 判断是否登录
     *
     * @return void
     */
    public function islogin()
    {

        if (empty(session('uid'))) {
            $this->redirect('/admin/Login/index');
        }
    }

    /**
     * 查询用户详细信息
     *
     * @return void
     */
    public function getuser()
    {
        try {
            $user = (new Admin())->where('id', session('uid'))->find();
            $this->assign('user', $user);
            $this->user = $user;
        } catch (\Exception $e) {
            return show($this->fail, $e->getMessage());
        }
    }

    /**
     * navigation
     * 获取菜单列表
     * @return void
     */
    public function menu()
    {
        //获取用户权限列表
        $adminModel = new Admin();
        $rules = $adminModel->rules();
        if (!$rules) {
            $this->error('您暂时没有操作权限，请向管理员申请！');
        }
        if ($this->user->group_id == 1) {
            $menurules = (new AuthRule())->where(['deleted_time'=>0])->order('order','desc')->select();
        }else{
            $adminrules = explode(',', $rules);
            foreach ($adminrules as $key => $val) {
                $rule = (new AuthRule())->rule($val);
                $menurules[] = $rule;
            }
        }
        // dump($this->getAllCate($menurules,'0'));exit;
        $this->assign('menu', $this->getAllCate($menurules, '0'));
    }

    /**
     * getAllCate
     * 无限级分类显示(递归获取分类）
     * @param [array] $cates
     * @param [int] $pid
     * @return void
     */
    public function getAllCate($cates, $pid)
    {
        //获取pid为$pid的分类
        $menus = array();
        foreach ($cates as $k => $v) {
            if ($v['pid'] == $pid) {
                $menus[] = $v;
            }
        }
        //遍历分类
        $res = array();
        foreach ($menus as $kk => $vv) {
            $vv['list'] = $this->getAllCate($cates, $vv['rule_id']);
            $res[] = $vv;
        }
        return $res;
    }


}