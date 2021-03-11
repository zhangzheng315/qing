<?php
namespace app\admin\serve;
use app\admin\model\ArticleType;
use app\admin\model\CommonArticle;
use app\admin\model\CommonArticleType;
use app\admin\model\ContentCenter;
use app\admin\model\HotArticle;
use think\Db;
use think\Request;


class CommonArticleService extends Common{

    public $commonArticle;
    public $hotArticle;
    public $content_center;
    public $articleType;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->commonArticle = new CommonArticle();
        $this->hotArticle = new HotArticle();
        $this->content_center = new ContentCenter();
        $this->articleType = new CommonArticleType();
        $this->labelService = new LabelService();
    }

    /**
     * 创建公共文章
     * @param $data
     * @return bool
     */
    public function commonArticleCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $hot= isset($param['hot']) ? $param['hot'] : 0;
        $pid_name = $this->articleType->where(['id' => $param['pid']])->value('name');
        $label = '';
        if (isset($param['label'])) {
            $label = implode(',', $param['label']);
        }
        $data = [
            'title' => $param['title'],
            'second_title' => $param['second_title'] ?: '',
            'icon_img_url' => $param['icon_img_url'] ?: '',
            'pid' => $param['pid'],
            'pid_name'=>$pid_name,
            'content' => $param['content'],
            'status' => $status,
            'label' => $label,
            'hot' => $hot,
            'order' => $param['order'] ?:0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $id = $this->commonArticle->insertGetId($data);
        if(!$id){
            $this->setError('添加失败');
            Db::rollback();
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 公共文章详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  commonArticleInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->commonArticle->find($where);
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
            $this->commonArticle->where($where)->setInc('browse');
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 公共文章修改
     * @param $data
     * @return bool
     */
    public function commonArticleEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if(!isset($data['hot'])) $data['hot'] = 0;
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
        $res = $this->commonArticle->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 公共文章删除
     * @param $param
     * @return bool
     */
    public function commonArticleDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->commonArticle->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    /**
     * 公共文章数量
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

    /**
     * 前端获取文章列表
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function commonArticleList()
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
        ];
        $all_article = $this->commonArticle->where($where)->order('order', 'desc')->select();
        $article_type = $this->articleType->order('order', 'desc')->select();
        foreach ($article_type as $key => $value) {
            $where['pid'] = $value['id'];
            $bool = $this->commonArticle->where($where)->find();
            if (!$bool) {
                unset($article_type[$key]);
            }
        }
        foreach ($all_article as $article_item) {
            $article_item['created_time'] = date('Y-m-d', $article_item['created_time']);
        }
        $list = ['all_article' => $all_article, 'article_type' => $article_type];
        if (!$list) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $list;
    }

    /**
     * 前端文章详情
     * @param $id
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException\
     */
    public function getArticleInfo($id)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
            'id' => $id,
        ];
        $info = $this->commonArticle->where($where)->find();
        if (!$info) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    public function newAdd()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $new_add = $this->commonArticle->field('title')->where($where)->order('created_time','desc')->limit(0,10)->select();
        if (!$new_add) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $new_add;
    }

    public function articleByWhere($param)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        if (isset($param['pid'])) {
            $where['pid'] = $param['pid'];
        }
        if (isset($param['word']) && $param['word']) {
            $where['title'] = ['like', ['%' . $param['word'] . '%']];
        }
        $article_list = $this->commonArticle->field(['id','icon_img_url','title'])->where($where)->order('order', 'desc')->select();
        if (!$article_list) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $article_list;
    }
}