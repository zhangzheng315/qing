<?php
namespace app\admin\serve;
use app\admin\model\Banner;
use app\admin\model\Navigation;
use app\admin\serve\NavigationService;
use think\Request;


class BannerService extends Common{

    public $banner;
    public $navigation;
    public $navigationService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->banner = new Banner();
        $this->navigation = new Navigation();
        $this->navigationService = new NavigationService();
    }

    /**
     * 创建轮播图
     * @param $data
     * @return bool
     */
    public function bannerCreate($param){
        if(!isset($param['status'])) $param['status'] = 0;
        $pid_name = $this->navigation->where(['id' => $param['pid']])->value('menu_name');
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

        $add_id = $this->banner->insertGetId($data);
        if(!$add_id){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }


    /**
     * 导航轮播图
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function bannerInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->banner->find($where);
        $info->navigation_list = $this->navigationService->navigationList();
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 轮播图修改
     * @param $data
     * @return bool
     */
    public function bannerEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $where = ['id' => $data['id']];
        $pid_name = $this->navigation->where(['id' => $data['pid']])->value('menu_name');
        $data['pid_name'] = $pid_name;
        $data['updated_time'] = time();

        $add_id = $this->banner->update($data,$where);
        if(!$add_id){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 轮播图删除
     * @param $param
     * @return bool
     */
    public function bannerDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->banner->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

}