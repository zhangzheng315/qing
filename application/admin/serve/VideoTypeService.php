<?php
namespace app\admin\serve;
use app\admin\model\Navigation;
use app\admin\model\VideoType;
use think\Request;


class VideoTypeService extends Common{

    public $videoType;
    public $navigation;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->videoType = new VideoType();
        $this->navigation = new Navigation();
    }

    public function videoTypeList()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $video_type_list = $this->videoType->where($where)->select();
        return $video_type_list;
    }

    /**
     * 创建视频分类
     * @param $data
     * @return bool
     */
    public function videoTypeCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'name' => $param['name'],
            'status' => $status,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->videoType->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 视频分类详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function videoTypeInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->videoType->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 视频分类修改
     * @param $data
     * @return bool
     */
    public function videoTypeEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->videoType->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 视频分类删除
     * @param $param
     * @return bool
     */
    public function videoTypeDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->videoType->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }
}