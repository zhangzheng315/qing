<?php
namespace app\admin\serve;
use app\admin\model\JoinUs;
use think\Request;


class JoinUsService extends Common{

    public $joinUs;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->joinUs = new JoinUs();
    }

    /**
     * 创建招聘
     * @param $data
     * @return bool
     */
    public function joinUsCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'position' => $param['position'],
            'salary' => $param['salary'],
            'region' => $param['region'],
            'education' => $param['education'],
            'years' => $param['years'],
            'status' => $status,
            'order' => $param['order'] ?:0,
            'created_time' => time(),
            'updated_time' => time(),
            'deleted_time' => 0,
        ];
        $res = $this->joinUs->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 招聘详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  joinUsInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->joinUs->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 招聘修改
     * @param $data
     * @return bool
     */
    public function joinUsEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->joinUs->allowField(true)->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 招聘删除
     * @param $param
     * @return bool
     */
    public function joinUsDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->joinUs->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    public function joinUsList($param)
    {
        $where = [
            'status' => 1,
            'deleted_time' => 0,
        ];
        if (isset($param['address']) && $param['address']) {
            switch ($param['address']) {
                case 1:
                    $address = '上海';
                    break;
                case 2:
                    $address = '北京';
                    break;
                case 3:
                    $address = '深圳';
                    break;
            }
        }
        $res = $this->joinUs->where($where)->order('order','desc')->select();
        foreach ($res as &$item) {
            $item['time'] = date('Y-m-d', $item['created_time']);
        }
        if(!$res){
            $this->setError('暂无数据');
            return [];
        }
        $this->setMessage('查询成功');
        return $res;
    }
}