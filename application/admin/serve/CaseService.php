<?php
namespace app\admin\serve;
use app\admin\model\CaseM;
use app\admin\model\CaseType;
use think\Request;


class CaseService extends Common{

    public $case;
    public $caseType;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->case = new CaseM();
        $this->caseType = new CaseType();
        $this->labelService = new LabelService();
    }

    /**
     * 创建文章
     * @param $data
     * @return bool
     */
    public function caseCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $case_selected = isset($param['case_selected']) ? $param['case_selected'] : 0;
        $label = '';
        if (isset($param['label'])) {
            $label = implode(',', $param['label']);
        }
        $data = [
            'title' => $param['title'],
            'second_title' => $param['second_title'] ?: '',
            'pid' => $param['pid'],
            'content' => $param['content'],
            'status' => $status,
            'label' => $label,
            'case_selected' => $case_selected,
            'order' => $param['order'] ?:0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->case->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 文章详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function caseInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->case->find($where);
        $case_type = $this->caseType->where(['deleted_time' => 0,'status'=>1])->select();
        $label_list = $this->labelService->labelList();
        $info->label = explode(',', $info->label);
        $info->case_type = $case_type;
        $info->label_list = $label_list;
        if(!$info){
            $this->setError('暂无数据');
            return false;
        }
        //前端点击的 增加浏览量
        if (isset($param['browse'])) {
            $this->case->where($where)->setInc('browse');
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 文章修改
     * @param $data
     * @return bool
     */
    public function caseEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if(!isset($data['case_selected'])) $data['case_selected'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        if (isset($data['label'])) {
            $data['label'] = implode(',', $data['label']);
        }else{
            $data['label'] = '';
        }
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->case->update($data,$where);
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
    public function caseDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->case->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }
}