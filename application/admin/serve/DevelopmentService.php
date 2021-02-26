<?php
namespace app\admin\serve;
use think\Request;
use app\admin\model\Development;


class DevelopmentService extends Common{

    public $development;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->development = new Development();
    }

    /**
     * 创建发展历程
     * @param $data
     * @return bool
     */
    public function developmentCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'content' => $param['content'],
            'time' => $param['time'],
            'status' => $status,
            'order' => $param['order'] ?: 0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->development->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 发展历程详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  developmentInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->development->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 发展历程修改
     * @param $data
     * @return bool
     */
    public function developmentEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->development->allowField(true)->save($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 发展历程删除
     * @param $param
     * @return bool
     */
    public function developmentDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->development->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }
}