<?php
namespace app\admin\serve;
use app\admin\model\Menu;
use app\admin\serve\Common;
use think\Log;
use think\Request;


class MenuService extends Common{

    public $menuModel;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->menuModel = new Menu();

    }

    /**
     * 创建菜单
     * @param $data
     * @return bool
     */
    public function menuCreate($param){

        $data = [
            'menu_name' => $param['menu_name'],
            'route' => $param['route'],
            'pid' => $param['pid'],
            'status' => $param['status'],
            'order' => $param['order'],
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];

        $add_id = $this->menuModel->insertGetId($data);
        if(!$add_id){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

}