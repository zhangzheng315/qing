<?php
namespace app\admin\serve;
use app\admin\model\Contact;
use think\Request;


class ContactService extends Common{

    public $contact;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->contact = new Contact();
    }

    /**
     * 创建发联系方式
     * @param $data
     * @return bool
     */
    public function contactCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'type_name' => $param['type_name'],
            'num_en' => $param['num_en'],
            'icon_url' => $param['icon_url'],
            'status' => $status,
            'order' => $param['order'],
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->contact->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 发联系方式详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  contactInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->contact->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 发联系方式修改
     * @param $data
     * @return bool
     */
    public function contactEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->contact->allowField(true)->save($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 发联系方式删除
     * @param $param
     * @return bool
     */
    public function contactDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->contact->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    public function contactList()
    {
        $where = [
            'status' => 1,
            'deleted_time' => 0,
        ];
        $res = $this->contact->where($where)->order('order','desc')->select();
        if(!$res){
            $this->setError('暂无数据');
            return [];
        }
        $this->setMessage('查询成功');
        return $res;
    }
}