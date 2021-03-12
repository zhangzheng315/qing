<?php
namespace app\admin\serve;
use app\admin\model\Theme;
use think\Request;


class ThemeService extends Common{

    public $theme;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->theme = new Theme();
    }

    /**
     * 创建视频主题
     * @param $data
     * @return bool
     */
    public function themeCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'theme_name' => $param['theme_name'],
            'status' => $status,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->theme->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 视频主题详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function themeInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->theme->find($where);
        $info->time = date('Y-m-d', $info->created_time);
        if(!$info){
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 视频主题修改
     * @param $data
     * @return bool
     */
    public function themeEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->theme->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 视频主题删除
     * @param $param
     * @return bool
     */
    public function themeDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->theme->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    /**
     * 主题列表
     * @param $param
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function themeList()
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
        ];
        $res = $this->theme->field(['id','theme_name'])->where($where)->select();
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $res;
    }
}