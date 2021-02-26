<?php
namespace app\admin\serve;
use app\admin\model\Article;
use app\admin\model\ArticleType;
use think\Request;


class ArticleService extends Common{

    public $article;
    public $articleType;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->article = new Article();
        $this->articleType = new ArticleType();
        $this->labelService = new LabelService();
    }

    /**
     * 创建文章
     * @param $data
     * @return bool
     */
    public function articleCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $hot_article = isset($param['hot_article']) ? $param['hot_article'] : 0;
        $pid_name = $this->articleType->where(['id' => $param['pid']])->value('name');
        $label = '';
        if (isset($param['label'])) {
            $label = implode(',', $param['label']);
        }
        $data = [
            'title' => $param['title'],
            'second_title' => $param['second_title'] ?: '',
            'pid' => $param['pid'],
            'pid_name'=>$pid_name,
            'content' => $param['content'],
            'status' => $status,
            'label' => $label,
            'hot_article' => $hot_article,
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
    public function  articleInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->article->find($where);
        $article_type = $this->articleType->where(['deleted_time' => 0,'status'=>1])->select();
        $label_list = $this->labelService->labelList();
        $info->label = explode(',', $info->label);
        $info->article_type = $article_type;
        $info->label_list = $label_list;
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        //前端点击的 增加浏览量
        if (isset($param['browse'])) {
            $this->article->where($where)->setInc('browse');
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
        if(!isset($data['hot_article'])) $data['hot_article'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        if (isset($data['label'])) {
            $data['label'] = implode(',', $data['label']);
        }else{
            $data['label'] = '';
        }
        $data['pid_name'] = $this->articleType->where(['id' => $data['pid']])->value('name');

        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
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

    /**
     * 文章数量
     * @return int|string
     * @throws \think\Exception
     */
    public function articleCount()
    {
        $where = [
            'deleted_time'=>0
        ];
        $count = $this->article->where($where)->count('id');
        return $count;
    }
}