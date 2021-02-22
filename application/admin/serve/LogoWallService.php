<?php
namespace app\admin\serve;
use app\admin\model\CaseType;
use app\admin\model\LogoWall;
use app\admin\model\Navigation;
use app\admin\model\Video;
use app\admin\model\VideoType;
use think\Request;


class LogoWallService extends Common{

    public $logoWall;
    public $navigation;
    public $caseType;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->logoWall = new LogoWall();
        $this->navigation = new Navigation();
        $this->caseType = new CaseType();
    }

    /**
     * 创建logo
     * @param $data
     * @return bool
     */
    public function logoWallCreate($param){
        if(!isset($param['status'])) $param['status'] = 0;
        $pid_name = $this->navigation->where(['id' => $param['pid']])->value('menu_name');
        $type_name = $this->caseType->where(['id'=>$param['type_id']])->value('name');
        $data = [
            'img_url' => $param['img_url'],
            'pid' => $param['pid'],
            'pid_name' => $pid_name,
            'type_id' => $param['type_id'],
            'type_name' => $type_name,
            'status' => $param['status'],
            'order' => $param['order'] ?: 0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];

        $add_id = $this->logoWall->insertGetId($data);
        if(!$add_id){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }


    /**
     * logo详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function logoWallInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->logoWall->find($where);
        $info->navigation_list = $this->navigation->where(['deleted_time' => 0,'status'=>1])->select();
        $info->type_list = $this->caseType->where(['deleted_time' => 0,'status'=>1])->select();
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * logo修改
     * @param $data
     * @return bool
     */
    public function logoWallEdit($data)
    {
        if ($data['pid'] == 0) {
            $this->setError('请选择父类');
            return false;
        }
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $pid_name = $this->navigation->where(['id' => $data['pid']])->value('menu_name');
        $type_name = $this->caseType->where(['id'=>$data['type_id']])->value('name');

        $where = ['id' => $data['id']];
        $data['pid_name'] = $pid_name;
        $data['type_name'] = $type_name;
        $data['updated_time'] = time();
        $add_id = $this->logoWall->update($data,$where);
        if(!$add_id){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * logo删除
     * @param $param
     * @return bool
     */
    public function logoWallDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->logoWall->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    /**
     * 查询各种logo
     * @param $param
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLogoByWhere($param)
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
            'pid' => $param['pid'],
            'type_id'=>$param['type_id']
        ];
        if ($param['type_id'] == 0) {
            unset($where['type_id']);
        }
        $res = $this->logoWall->where($where)->select();
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $res;
    }

}