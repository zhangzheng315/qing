<?php
namespace app\admin\controller;

use app\admin\serve\NavigationService;
use think\Request;
use think\Validate;

class Navigation extends Common{
    public $navigationService;
    public function __construct(NavigationService $navigationService)
    {
        parent::__construct();
        $this->navigationService = $navigationService;
    }

    public function index()
    {
//        $navigation_list = db('navigation')->where(['deleted_time'=>0,'status'=>1])->field('menu_name,id')->select();
        return $this->fetch();
    }

    /**
     * 分页获取角色列表
     */
    public function getNavigationList(){
        $data = input('get.');
        $data['status'] = 1;
        $str = NavigationService::data_paging($data,'navigation','order');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加菜单
     * @return mixed
     */
    public function navigationCreate(Request $request){
        $rules =
            [
                'menu_name' => 'require',
//                'route' => 'require',
//                'pid' => 'require',
            ];
        $msg =
            [
                'menu_name' => '缺少参数@menu_name',
//                'route' => '缺少参数@route',
//                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->navigationService->navigationCreate($request->param());
        if($res){
            return show($this->ok,$this->navigationService->message);
        }else{
            return show($this->fail,$this->navigationService->error);
        }
    }
}