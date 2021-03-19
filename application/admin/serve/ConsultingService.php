<?php
namespace app\admin\serve;
use app\admin\model\AboutUs;
use app\admin\model\Consulting;
use think\Request;


class ConsultingService extends Common{

    public $consulting;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->consulting = new Consulting();
    }

    /**
     * 添加预约
     * @param $data
     * @return bool
     */
    public function consultingCreate($param){
        $data = [
            'name' => $param['name'],
            'phone' => $param['phone'],
            'email' => $param['email'],
            'company' => $param['company'],
            'industry' => $param['industry'],
            'describe' => $param['describe'],
            'status' => 0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $id = $this->consulting->insertGetId($data);

        if(!$id){
            $this->setError('预约失败');
            return false;
        }
        $this->setMessage('预约成功');
        return true;
    }

    /**
     * 预约详情
     * @param $id
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  consultingInfo($id)
    {
        $where = ['id' => $id];
        $info = $this->consulting->find($where);
        if(!$info){
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 后台首页统计预约数量
     * @return array
     * @throws \think\Exception
     */
    public function consultingStatistics()
    {
        $all_num = $this->consulting->count('id');
        $un_pro_num = $this->consulting->where(['status' => 0])->count();
        $pro_num = $all_num - $un_pro_num;
        return ['all_num' => $all_num, 'un_pro_num' => $un_pro_num, 'pro_num' => $pro_num];
    }

    /**
     * 更改预约状态
     * @param $id
     * @return bool
     */
    public function checkPro($id)
    {
        $where = ['id' => $id,'status'=>1];
        $info = $this->consulting->where($where)->value('id');
        if ($info) {
            $this->setError('该预约已处理');
            return false;
        }
        $res = $this->consulting->where(['id' => $id])->update(['status' => 1]);
        if (!$res) {
            $this->setError('操作失败');
            return false;
        }
        $this->setMessage('操作成功');
        return true;
    }

}