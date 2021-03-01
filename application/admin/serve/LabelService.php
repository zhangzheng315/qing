<?php
namespace app\admin\serve;
use app\admin\model\Label;
use think\Request;


class LabelService   extends Common{

    public $label;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->label = new Label();
    }

    public function labelList()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $label_list = $this->label->where($where)->select();
        return $label_list;
    }

    /**
     * 创建标签
     * @param $data
     * @return bool
     */
    public function labelCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $hot_label = isset($param['hot_label']) ? $param['hot_label'] : 0;
        $data = [
            'name' => $param['name'],
            'status' => $status,
            'hot_label' => $hot_label,
            'order'=> $param['order']?:0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->label->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 标签详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function labelInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->label->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 标签修改
     * @param $data
     * @return bool
     */
    public function labelEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if(!isset($data['hot_label'])) $data['hot_label'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->label->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 标签删除
     * @param $param
     * @return bool
     */
    public function labelDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->label->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }
}