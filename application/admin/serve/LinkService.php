<?php
namespace app\admin\serve;
use think\Db;
use app\admin\model\Link;
use app\admin\model\Navigation;
use think\Request;


class LinkService extends Common{
    public $link;
    public $navigation;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->link = new Link();
        $this->navigation = new Navigation();
    }

    public function linkList()
    {
        $where = [ 'status' => 1 ];
        $link_list = Db::name('think_link')->where($where)->select();
        return $link_list;
    }

    /**
     * 创建案例分类
     * @param $data
     * @return bool
     */
    public function linkCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'name' => $param['name'],
            'linkurl' => $param['linkurl'],
            'status'  => $status,
            'order'   => 0,
        ];
        $res =Db::name('think_link')->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 案例分类详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function linkInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->link->find($where);
        $navigation = $this->navigation->where(['deleted_time' => 0])->select();
        $info->navigation_list = $navigation;
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 案例分类修改
     * @param $data
     * @return bool
     */
    public function linkEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->link->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 案例分类删除
     * @param $param
     * @return bool
     */
    public function linkDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->link->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }
}