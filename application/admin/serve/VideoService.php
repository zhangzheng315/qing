<?php
namespace app\admin\serve;
use app\admin\model\Theme;
use app\admin\model\Video;
use app\admin\model\VideoType;
use think\Request;


class VideoService extends Common{

    public $video;
    public $videoType;
    public $labelService;
    public $theme;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->video = new Video();
        $this->videoType = new VideoType();
        $this->labelService = new LabelService();
        $this->theme = new Theme();
    }

    /**
     * 创建视频
     * @param $data
     * @return bool
     */
    public function videoCreate($param){
        if ($param['pid'] == 0 || $param['theme_id'] == 0) {
            $this->setError('请选择父类或主题');
            return false;
        }
        if(!isset($param['status'])) $param['status'] = 0;
        if(!isset($param['recommend'])) $param['recommend'] = 0;
        $video_selected = isset($param['video_selected']) ? $param['video_selected'] : 0;
        $label = '';
        if (isset($param['label'])) {
            $label = implode(',', $param['label']);
        }
        $pid_name = $this->videoType->where(['id' => $param['pid']])->value('name');
        $theme_name = $this->theme->where(['id' => $param['theme_id']])->value('theme_name');
        $data = [
            'video_url' => $param['video_url'],
            'cover_img_url' => $param['cover_img_url'],
            'pid' => $param['pid'],
            'pid_name' => $pid_name,
            'theme_id' => $param['theme_id'],
            'theme_name' => $theme_name,
            'title' => $param['title'] ?: '',
            'describe' => $param['describe'] ?: '',
            'content' => $param['content'] ?: '',
            'video_selected' => $video_selected,
            'label' => $label,
            'status' => $param['status'],
            'recommend' => $param['recommend'],
            'order' => $param['order'] ?: 0,
            'created_time' => time(),
            'updated_time' => time(),
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
        $info->video_theme = $this->theme->where(['deleted_time' => 0, 'status' => 1])->select();
        $info->label_list = $this->labelService->labelList();
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        //前端点击的 增加浏览量
        if (isset($param['browse'])) {
            $this->video->where($where)->setInc('browse');
            $info->catalog = $this->videoCatalog($info['theme_id']);
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
        if(!isset($data['recommend'])) $data['recommend'] = 0;
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
        $theme_name = $this->theme->where(['id' => $data['theme_id']])->value('theme_name');
        $data['pid_name'] = $pid_name;
        $data['theme_name'] = $theme_name;
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

    /**
     * 视频学院首页显示
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
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

    /**
     * 按条件获取视频列表
     * @param $param
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getVideoListByWhere($param)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
            'pid' => $param['pid'],
        ];
        if (isset($param['all'])) {
            $list = $this->video->where($where)->order('order', 'desc')->select();
        }else{
            $list = $this->video->where($where)->order('order', 'desc')->limit(0, 8)->select();
        }
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

    /**
     * 分页视频列表
     * @param $param
     * @return array|bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function videoListByPage($param)
    {
        $limit = 12;
        $offset = ($param['curr'] - 1) * $limit;
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        if (isset($param['word']) && $param['word']) {
            $where['title'] = ['like', '%' . $param['word'] . '%'];
        }
        if (isset($param['pid']) && $param['pid']) {
            $where['pid'] = $param['pid'];
        }
        $res = $this->video->where($where)->limit($offset,$limit)->select();
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }

        $count = $this->video->where($where)->count('id');
        $this->setMessage('查询成功');
        return ['data'=>$res,'count'=>$count,'index'=>$param['pid'],'word'=>$param['word'],'curr'=>$param['curr']];
    }

    /**
     * 获取视频详情页视频目录
     * @param $theme_id
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function videoCatalog($theme_id)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
            'theme_id' => $theme_id,
        ];
        $catalog = $this->video->where($where)->order('order', 'desc')->select();
        $all_dur = 0;
        foreach ($catalog as $item) {
            $data = $this->liveCurl($item['video_url'].'?avinfo');
            $json = json_decode($data);
            $duration = (int)$json->format->duration;
            $all_dur += $duration;
            $item['duration'] = $this->getDuration($duration);
        }
        $all_dur = $this->getDuration($all_dur);
        return ['catalog'=>$catalog,'all_dur'=>$all_dur];
    }


    /**
     * 将秒数转换为时长  格式：00:00:00
     * @param $duration
     * @return string
     */
    public function getDuration($duration)
    {
        $result = '00:00:00';
        if ($duration>0) {
            $hour = floor($duration/3600);
            $minute = floor(($duration-3600 * $hour)/60);
            $second = floor((($duration-3600 * $hour) - 60 * $minute) % 60);


            $hour = strlen($hour) == 1 ? '0' . $hour : $hour;
            $minute = strlen($minute) == 1 ? '0' . $minute : $minute;
            $second = strlen($second) == 1 ? '0' . $second : $second;
            $result = $hour.':'.$minute.':'.$second;
        }
        return $result;
    }


    public function videoWordSearch($param)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        if (isset($param['word']) && $param['word']) {
            $where['title'] = ['like', '%' . $param['word'] . '%'];
        }
        $video_list = $this->video->where($where)->order('order', 'desc')->select();
        if (!$video_list) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $video_list;
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function liveCurl($url)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); //  如果不是https的就注释 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作

        curl_close($curl); // 关闭CURL会话
        return $res;
    }

    /**
     * 更多直播功能  视频
     * @return array|bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function moreLiveVideo()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $new_video = $this->video->where($where)->order('created_time')->limit(0, 4)->select();
        if (!$new_video) {
            $new_video = [];
        }
        $where['recommend'] = 1;
        $recommend_video = $this->video->where($where)->order('updated_time')->limit(0,8)->select();
        if (!$recommend_video) {
            $recommend_video = [];
        }
        $more_list_video = [
            'new_video' => $new_video,
            'recommend_video' => $recommend_video,
        ];
        return $more_list_video;
    }

}