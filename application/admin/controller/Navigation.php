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
        $add_url = '/admin/navigation/navigationCreate'; //添加
        $edit_url = '/admin/navigation/navigationEdit';  //修改
        $del_url = '/admin/navigation/navigationDelete'; //删除
        return $this->fetch('',compact('add_url','edit_url','del_url'));
    }

    /**
     * 分页获取角色列表
     */
    public function getNavigationList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = NavigationService::data_paging($data,'navigation','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加导航栏
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

    /**
     * 导航栏详情
     * @return mixed
     */
    public function navigationInfo(Request $request){
        $rules =
            [
                'id' => 'require',
        ];
        $msg =
            [
                'id' => '缺少参数@id',
        ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->navigationService->navigationInfo($request->param());
        if($res){
            return show($this->ok,$this->navigationService->message,$res);
        }else{
            return show($this->fail,$this->navigationService->error);
        }
    }

    /**
     * 修改导航栏
     * @return mixed
     */
    public function navigationEdit(){
        $data = input('post.');
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
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->navigationService->navigationEdit($data);
        if($res){
            return show($this->ok,$this->navigationService->message);
        }else{
            return show($this->fail,$this->navigationService->error);
        }
    }

    /**
     * 删除导航栏
     * @return mixed
     */
    public function navigationDelete(Request $request){
        $rules =
            [
                'id' => 'require',
        ];
        $msg =
            [
                'id' => '缺少参数@id',
        ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->navigationService->navigationDelete($request->param());
        if($res){
            return show($this->ok,$this->navigationService->message);
        }else{
            return show($this->fail,$this->navigationService->error);
        }
    }
}