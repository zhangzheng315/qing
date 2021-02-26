<?php
namespace app\admin\serve;
use app\admin\model\Solution;
use think\Request;


class SolutionService extends Common{

    public $solution;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->solution = new Solution();
    }

    /**
     * 创建解决方案
     * @param $data
     * @return bool
     */
    public function solutionCreate($param){
        if(!isset($param['status'])) $param['status'] = 0;
        if(!isset($param['type'])) $param['type'] = 0;
        $data = [
            'img_url' => $param['img_url'],
            'title' => $param['title'] ?: '',
            'introduction' => $param['introduction'],
            'type' => $param['type'],
            'link' => $param['link'] ?: '',
            'status' => $param['status'],
            'order' => $param['order'] ?: 0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];

        $add_id = $this->solution->insertGetId($data);
        if(!$add_id){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }


    /**
     * 导航解决方案
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function solutionInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->solution->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 解决方案修改
     * @param $data
     * @return bool
     */
    public function solutionEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if(!isset($data['type'])) $data['type'] = 0;
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();

        $add_id = $this->solution->allowField(true)->save($data,$where);
        if(!$add_id){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 解决方案删除
     * @param $param
     * @return bool
     */
    public function solutionDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->solution->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    public function solutionList()
    {
        $where = [
            'status' => 1,
            'deleted_time' => 0,
            'type' => 0,
        ];
        //行业解决方案
        $ind_res = $this->solution->where($where)->select();
        //场景解决方案
        $where['type'] = 1;
        $sce_res = $this->solution->where($where)->select();
        $res = [
            'ind_res' => $ind_res ? $ind_res : [],
            'sce_res' => $sce_res ? $sce_res : [],
        ];
        return $res;
    }

}