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

    /**
     * 导航栏列表
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function navigationList()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $navigation_list = $this->navigation->where($where)->select();
        return $navigation_list;
    }

    /**
     * 导航栏详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function navigationInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->navigation->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 导航栏修改
     * @param $data
     * @return bool
     */
    public function navigationEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;

        $where = ['id' => $data['id']];
        $res = $this->navigation->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 文章删除
     * @param $param
     * @return bool
     */
    public function navigationDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->navigation->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

}