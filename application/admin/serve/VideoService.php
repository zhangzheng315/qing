<?php
namespace app\admin\serve;
use app\admin\model\Video;
use app\admin\model\VideoType;
use think\Request;


class VideoService extends Common{

    public $video;
    public $videoType;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->video = new Video();
        $this->videoType = new VideoType();
        $this->labelService = new LabelService();
    }

    /**
     * 创建视频
     * @param $data
     * @return bool
     */
    public function videoCreate($param){
        if ($param['pid'] == 0) {
            $this->setError('请选择父类');
            return false;
        }
        if(!isset($param['status'])) $param['status'] = 0;
        $video_selected = isset($param['video_selected']) ? $param['video_selected'] : 0;
        $label = '';
        if (isset($param['label'])) {
            $label = implode(',', $param['label']);
        }
        $pid_name = $this->videoType->where(['id' => $param['pid']])->value('name');
        $data = [
            'video_url' => $param['video_url'],
            'cover_img_url' => $param['cover_img_url'],
            'pid' => $param['pid'],
            'pid_name' => $pid_name,
            'title' => $param['title'] ?: '',
            'video_selected' => $video_selected,
            'label' => $label,
            'status' => $param['status'],
            'order' => $param['order'] ?: 0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];

        $add_id = $this->video->insertGetId($data);
        if(!$add_id){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }


    /**
     * 视频详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function videoInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->video->find($where);
        $info->label = explode(',', $info->label);
        $info->video_type_list = $this->videoType->where(['deleted_time' => 0,'status'=>1])->select();
        $info->label_list = $this->labelService->labelList();
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        //前端点击的 增加浏览量
        if (isset($param['browse'])) {
            $this->video->where($where)->setInc('browse');
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 视频修改
     * @param $data
     * @return bool
     */
    public function videoEdit($data)
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
        if (isset($data['video_up'])) {
            //layui富文本自带file参数
            unset($data['video_up']);
        }
        if (isset($data['label'])) {
            $data['label'] = implode(',', $data['label']);
        }else{
            $data['label'] = '';
        }
        if(!isset($data['video_selected'])) $data['video_selected'] = 0;

        $where = ['id' => $data['id']];
        $pid_name = $this->videoType->where(['id' => $data['pid']])->value('name');
        $data['pid_name'] = $pid_name;
        $data['updated_time'] = time();
        $add_id = $this->video->update($data,$where);
        if(!$add_id){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 视频删除
     * @param $param
     * @return bool
     */
    public function videoDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->video->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    /**
     * 视频数量
     * @return int|string
     * @throws \think\Exception
     */
    public function videoCount()
    {
        $where = ['deleted_time' => 0,];
        $count = $this->video->where($where)->count('id');
        return $count;
    }

    /**
     * 轻学院视频首页修改
     * @param $data
     * @return bool
     */
    public function videoHomeEdit($param)
    {
        $where = ['id' => $param['id']];
        $data['selected_order'] = $param['selected_order'];
        $data['updated_time'] = time();
        $add_id = $this->video->update($data,$where);
        if(!$add_id){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    public function videoHomeFirst()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $info = $this->video->where($where)->order('selected_order', 'desc')->limit(1)->find();
        if (!$info) {
            $this->setError('查询有误');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    public function getVideoListByWhere($param)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
            'pid' => $param['pid'],
        ];
        $list = $this->video->where($where)->order('order', 'desc')->limit(0, 8)->select();
        foreach ($list as &$item) {
            $item['time'] = date('Y-m-d', $item['created_time']);
        }
        if (!$list) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $list;
    }

}