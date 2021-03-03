<?php
namespace app\admin\serve;
use app\admin\model\ArticleType;
use app\admin\model\CommonArticleType;
use think\Request;


class CommonArticleTypeService extends Common{

    public $commonArticleTyle;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->commonArticleTyle = new CommonArticleType();
    }

    public function articleTypeList()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $article_type_list = $this->commonArticleTyle->where($where)->select();
        return $article_type_list;
    }

    /**
     * 创建公共文章分类
     * @param $data
     * @return bool
     */
    public function commonArticleTypeCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'name' => $param['name'],
            'status' => $status,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->commonArticleTyle->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 公共文章分类详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function commonArticleTypeInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->commonArticleTyle->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 公共文章分类修改
     * @param $data
     * @return bool
     */
    public function commonArticleTypeEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->commonArticleTyle->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 公共文章分类删除
     * @param $param
     * @return bool
     */
    public function commonArticleTypeDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->commonArticleTyle->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }
}