<?php
namespace app\admin\serve;
use app\admin\model\Article;
use app\admin\model\Navigation;
use think\Request;


class ArticleService extends Common{

    public $article;
    public $navigation;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->article = new Article();
        $this->navigation = new Navigation();
    }

    /**
     * 创建文章
     * @param $data
     * @return bool
     */
    public function articleCreate($param){
        $data = [
            'title' => $param['title'],
            'second_title' => $param['second_title'] ?: '',
            'pid' => $param['pid'],
            'content' => $param['content'],
            'status' => $param['status'] ?:0,
            'order' => $param['order'] ?:0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $res = $this->article->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 文章详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function articleInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->article->find($where);
        $navigation = $this->navigation->where(['deleted_time' => 0])->select();
        $info->navigation_list = $navigation;
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 文章修改
     * @param $data
     * @return bool
     */
    public function articleEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        $where = ['id' => $data['id']];
        $res = $this->article->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 文章删除
     * @param $param
     * @return bool
     */
    public function articleDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->article->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }
}