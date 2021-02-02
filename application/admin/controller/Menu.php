<?php
namespace app\admin\controller;

use app\admin\serve\MenuService;
use think\Request;
use think\Validate;

class Menu extends Common{
    public $menuService;
    public function __construct(MenuService $menuService)
    {
        parent::__construct();
        $this->menuService = $menuService;
    }

    public function menuList()
    {

        return $this->fetch();
    }

    /**
     * 分页获取角色列表
     */
    public function getMenuList(){
        $data = input('get.');
        $data['status'] = 1;
        $str = MenuService::data_paging($data,'menu','order');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加菜单
     * @return mixed
     */
    public function menuCreate(Request $request){
        $rules =
            [
                'menu_name' => 'require',
                'route' => 'require',
                'pid' => 'require',
            ];
        $msg =
            [
                'menu_name' => '缺少参数@menu_name',
                'route' => '缺少参数@route',
                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->menuService->menuCreate($request->param());
        if($res){
            return show($this->ok,$this->menuService->message);
        }else{
            return show($this->fail,$this->menuService->error);
        }
    }

}