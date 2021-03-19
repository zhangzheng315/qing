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
            'cover_img_url' => $param['cover_img_url'] ?:'https://images.innocomn.com/036d5202102231449286918.png',
            'status' => $status,
            'label' => $label,
            'case_selected' => $case_selected,
            'order' => $param['order'] ?:0,
            'created_time' => time(),
            'updated_time' => time(),
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
        $info->time = date('Y-m-d', $info->created_time);
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
        if (!$data['cover_img_url']) {
            $data['cover_img_url'] = 'https://images.innocomn.com/036d5202102231449286918.png';
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

    /**
     * @param $param
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCaseByWhere($param)
    {
        $limit = 12;
        $offset = ($param['curr'] - 1) * $limit;
        $where = [
            'status'=>1,
            'deleted_time' => 0,
        ];
        if ($param['pid'] > 0) {
            $where['pid'] = $param['pid'];
        }
        $res = $this->case->where($where)->limit($offset,$limit)->select();
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        foreach ($res as &$item) {
            $item->label = explode(',', $item->label);
        }
        $count = $this->case->where($where)->count('id');
        $this->setMessage('查询成功');
        return ['data'=>$res,'count'=>$count,'index'=>$param['pid'],'curr'=>$param['curr']];
    }

    /**
     * 精选案例
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCaseSelected()
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
            'case_selected' => 1,
        ];
        $res = $this->case->where($where)->limit(0,6)->select();
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $res;
    }

    /**
     * 案例数量（后台首页展示）
     * @return int|string
     * @throws \think\Exception
     */
    public function caseCount()
    {
        $where = ['deleted_time' => 0];
        $count = $this->case->where($where)->count('id');
        return $count;
    }

    /**
     * 关于轻直播--我们的优势--案例
     * @param $pid
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function caseListByPid($pid)
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
            'pid' => $pid,
        ];
        $res = $this->case->where($where)->limit(0,6)->select();
        foreach ($res as &$item) {
            $item['time'] = $item['updated_time'] == 0 ? date('Y-m-d H:i', $item['updated_time']) : date('Y-m-d H:i', $item['created_time']);
        }
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $res;
    }

    /**
     * $type_id  1精选案例  2热门案例   3推荐案例   4全部案例
     * @param $id
     * @param $type_id
     */
    public function preAndNext($id,$type_id,$pid)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $pre_btn = 0;
        $nex_btn = 0;
        $pre_case = [];
        $nex_case = [];
        if ($type_id == 1) {
            $where['case_selected'] = 1;
            $case_list = $this->case->where($where)->order('order', 'desc')->select();
        } elseif ($type_id == 2) {
            $case_list = $this->case->where($where)->order('browse', 'desc')->limit(0, 5)->select();
        } elseif ($type_id == 3) {
            $where['case_recommend'] = 1;
            $case_list = $this->case->where($where)->order('recommend_order', 'desc')->limit(0, 5)->select();
        } else{
            $where['pid'] = $pid;
            $case_list = $this->case->where($where)->order('order', 'desc')->select();
        }
        $last_index = count($case_list) - 1;
        $index = 0;
        foreach ($case_list as $key => $value) {
            if ($value['id'] == $id) {
                $index = $key;
            }
        }

        if ($index - 1  >= 0) {
            $pre_btn = 1;
            $pre_case = $case_list[$index - 1];
        }
        if ($index + 1 <= $last_index) {
            $nex_btn = 1;
            $nex_case = $case_list[$index + 1];
        }
        $pre_nex = [
            'type_id' => $type_id,
            'pre_btn' => $pre_btn,
            'pre_case' => $pre_case,
            'nex_btn' => $nex_btn,
            'nex_case' => $nex_case,
        ];
        return $pre_nex;
    }

    /**
     * 热门案例   推荐案例
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function hotAndRem()
    {
        $where = [
            'deleted_time' => 0,
            'status'=>1
        ];
        $data['hot_case'] = $this->case->where($where)->order('browse', 'desc')->limit(0, 5)->select();
        foreach ($data['hot_case'] as &$val) {
            $val['time'] = date('Y-m-d', $val['created_time']);
        }

        $where['case_recommend'] = 1;
        $data['recommend_case'] = $this->case->where($where)->order('recommend_order', 'desc')->limit(0, 5)->select();
        foreach ($data['recommend_case'] as &$item) {
            $item['time'] = date('Y-m-d', $item['created_time']);
        }
        return $data;
    }

    /**
     * 案例列表
     * @param $pid
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function caseByPid($pid)
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
            'pid' => $pid,
        ];
        $res = $this->case->where($where)->order('order','desc')->select();
        foreach ($res as &$item) {
            $item['time'] = $item['updated_time'] == 0 ? date('Y-m-d H:i', $item['updated_time']) : date('Y-m-d H:i', $item['created_time']);
            $item['label'] = explode(',', $item['label']);
        }
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $res;
    }

    /**
     * 案例列表  by where
     * @param $param
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function caseByWhere($param)
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
            'pid'=>$param['pid'],
        ];
        if (isset($param['word']) && $param['word']) {
            $where['title'] = ['like', '%' . $param['word'] . '%'];
        }
        if (isset($param['label']) && $param['label']) {
            $where['label'] = ['like', '%' . $param['label'] . '%',];
        }
        $res = $this->case->where($where)->order('order','desc')->select();
        foreach ($res as &$item) {
            $item['time'] = $item['updated_time'] == 0 ? date('Y-m-d H:i', $item['updated_time']) : date('Y-m-d H:i', $item['created_time']);
            $item['label'] = explode(',', $item['label']);
        }
        if (!$res) {
            $this->setError('暂无数据');
            return $res = [];
        }
        $this->setMessage('查询成功');
        return $res;
    }

    /**
     * 我们的优势--案例
     * @return array|bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function caseAdvantage()
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
        ];
        $res = $this->case->where($where)->order('browse','desc')->limit(0,4)->select();
        if (!$res) {
            return [];
        }
        foreach ($res as &$item) {
            $item['time'] = date('Y-m-d H:i:s', $item['updated_time']);
        }
        return $res;
    }
}