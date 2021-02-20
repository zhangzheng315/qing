<?php
namespace app\admin\serve;
use app\admin\model\CaseM;
use app\admin\model\CaseType;
use app\admin\model\Navigation;
use think\Request;


class CaseTypeService extends Common{

    public $caseType;
    public $navigation;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->caseType = new CaseType();
        $this->navigation = new Navigation();
    }

    public function caseTypeList()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $case_type_list = $this->caseType->where($where)->select();
        return $case_type_list;
    }

    /**
     * 创建案例分类
     * @param $data
     * @return bool
     */
    public function caseTypeCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'name' => $param['name'],
            'status' => $status,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->caseType->insertGetId($data);
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
    public function caseTypeInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->caseType->find($where);
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
    public function caseTypeEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->caseType->update($data,$where);
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
    public function caseTypeDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->caseType->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }
}