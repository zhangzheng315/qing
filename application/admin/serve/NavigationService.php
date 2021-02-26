<?php
namespace app\admin\serve;
use app\admin\model\Navigation;
use app\admin\serve\Common;
use think\Log;
use think\Request;


class NavigationService extends Common{

    public $navigation_list;
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
        $pid_name = $this->navigation->where(['id' => $param['pid']])->value('menu_name');
        $status = isset($param['status']) ? $param['status'] : 0;
        $route = isset($param['route']) ? $param['route'] : '';
        $data = [
            'menu_name' => $param['menu_name'],
            'route' => $route,
            'pid' => $param['pid'],
            'pid_name' => $pid_name,
            'status' => $status,
            'order' => $param['order'] ?:0,
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
        $info->nav_list = $this->navigationListClass();
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
        if ($data['pid'] == 0) {
            $data['pid_name'] = '根目录';
        }else{
            $data['pid_name'] = $this->navigation->where(['id' => $data['pid']])->value('menu_name');
        }
        $data['status'] = isset($data['status']) ? $data['status'] : 0;
        $data['route'] = isset($data['route']) ? $data['route'] : '';

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

    public function navigationListClass()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $this->navigation_list = $this->navigation->where($where)->order('order','desc')->select();
        $nav_tree = $this->tree();
        return $nav_tree;
    }

    public function tree($id = 0) {
        # 根据ID找出所有下级
        $childs = $this->child($this->navigation_list, $id);
        if (!$childs) {
            return [];
        }

        # 遍历下级数据
        foreach ($childs as $key => $value) {
            # 用下级ID找出下级自身下级数据
            $tree = $this->tree($value['id']);
            if ($tree) {
                $childs[$key]['sub'] = $tree;
            }else{
                $childs[$key]['sub'] = [];
            }
        }
        return $childs;
    }
    public function child($res, $pid) {
        $childs = [];
        foreach ($res as $key => $value) {
            if ($value['pid'] == $pid) {
                $childs[] = $value;
            }
        }
        return $childs;
    }

    public function getActive($controller)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
            'route'=>['like','%'.$controller.'%']
        ];
        $info = $this->navigation->where($where)->find();
        if ($info['pid'] == 0) {
            $pid = $info['id'];
        }else{
            $pid = $this->getPid($info['pid']);
        }
        return ['pid'=>$pid,'id'=>$info['id']];
    }

    public function getPid($pid)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
            'id'=>$pid,
        ];
        $info = $this->navigation->where($where)->find();
        if ($info['pid'] != 0) {
            return $this->getPid($info['pid']);
        }
        return $info['id'];
    }

}