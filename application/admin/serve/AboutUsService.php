<?php
namespace app\admin\serve;
use app\admin\model\AboutUs;
use think\Request;


class AboutUsService extends Common{

    public $aboutUs;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->aboutUs = new AboutUs();
    }

    /**
     * 创建或编辑关于我们
     * @param $data
     * @return bool
     */
    public function aboutUsCreateOrEdit($param){
        $data = [
            'title' => $param['title'],
            'second_title' => $param['second_title'] ?: '',
            'content' => $param['content'],
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $id = $this->aboutUs->where(['id' => 1])->value('id');
        if ($id) {
            unset($data['created_time']);
            unset($data['deleted_time']);
            $data['updated_time'] = time();
            $res = $this->aboutUs->update($data, ['id' => 1]);
        }else{
            $res = $this->aboutUs->insertGetId($data);
        }
        if(!$res){
            $this->setError('编辑失败');
            return false;
        }
        $this->setMessage('编辑成功');
        return true;
    }

    /**
     * 关于我们详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  aboutUsInfo()
    {
        $where = ['id' => 1];
        $info = $this->aboutUs->find($where);
        if(!$info){
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

}