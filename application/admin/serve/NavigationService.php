<?php
namespace app\admin\serve;
use app\admin\model\Navigation;
use app\admin\serve\Common;
use think\Log;
use think\Request;


class NavigationService extends Common{

    public $navigation;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->navigation = new Navigation();

    }

    /**
     * 创建菜单
     * @param $data
     * @return bool
     */
    public function navigationCreate($param){

        $data = [
            'menu_name' => $param['menu_name'],
//            'route' => $param['route'],
//            'pid' => $param['pid'],
            'status' => $param['status'],
            'order' => $param['order'],
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];

        $add_id = $this->navigation->insertGetId($data);
        if(!$add_id){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    public function navigationList()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $navigation_list = $this->navigation->where($where)->select();
        return $navigation_list;
    }

}