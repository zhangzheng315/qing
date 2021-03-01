<?php
namespace app\admin\serve;
use app\admin\model\QBanner;
use think\Request;


class QBannerService extends Common{

    public $QBanner;
    public $pid_arr = [
        0 => '内容中心',
        1 => '案例解析',
        2 => '产品动态',
        3 => '直播资讯',
    ];
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->QBanner = new QBanner();
    }

    /**
     * 创建轻学院轮播图
     * @param $data
     * @return bool
     */
    public function QBannerCreate($param){
        if(!isset($param['status'])) $param['status'] = 0;
        $pid_name = $this->pid_arr[$param['pid']];
        $data = [
            'img_url' => $param['img_url'],
            'pid' => $param['pid'],
            'pid_name' => $pid_name,
            'title' => $param['title'] ?: '',
            'introduction' => $param['introduction'],
            'status' => $param['status'],
            'order' => $param['order'] ?: 0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];

        $add_id = $this->QBanner->insertGetId($data);
        if(!$add_id){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }


    /**
     * 导航轻学院轮播图
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function QBannerInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->QBanner->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $info->pid_list = $this->pid_arr;
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 轻学院轮播图修改
     * @param $data
     * @return bool
     */
    public function QBannerEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $where = ['id' => $data['id']];
        $pid_name = $this->pid_arr[$data['pid']];
        $data['pid_name'] = $pid_name;
        $data['updated_time'] = time();

        $add_id = $this->QBanner->update($data,$where);
        if(!$add_id){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 轻学院轮播图删除
     * @param $param
     * @return bool
     */
    public function QBannerDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->QBanner->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    public function QBannerListByPid($pid)
    {
        $where = [
            'status' => 1,
            'deleted_time' => 0,
            'pid' => $pid,
        ];
        $res = $this->QBanner->where($where)->select();
        return $res ? $res : false;
    }

}