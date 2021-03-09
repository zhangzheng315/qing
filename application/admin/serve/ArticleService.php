<?php
namespace app\admin\serve;
use app\admin\model\Article;
use app\admin\model\ArticleType;
use app\admin\model\ContentCenter;
use app\admin\model\HotArticle;
use think\Db;
use think\Request;


class ArticleService extends Common{

    public $article;
    public $hotArticle;
    public $content_center;
    public $articleType;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->article = new Article();
        $this->hotArticle = new HotArticle();
        $this->content_center = new ContentCenter();
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
        $content_center = isset($param['content_center']) ? $param['content_center'] : 0;
        $pid_name = $this->articleType->where(['id' => $param['pid']])->value('name');
        $label = '';
        if (isset($param['label'])) {
            $label = implode(',', $param['label']);
        }
        $data = [
            'title' => $param['title'],
            'second_title' => $param['second_title'] ?: '',
            'cover_img_url' => $param['cover_img_url'] ?: '',
            'pid' => $param['pid'],
            'pid_name'=>$pid_name,
            'content' => $param['content'],
            'status' => $status,
            'label' => $label,
            'hot_article' => $hot_article,
            'content_center' => $content_center,
            'order' => $param['order'] ?:0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        Db::startTrans();
        $id = $this->article->insertGetId($data);
        unset($data['hot_article']);
        unset($data['content_center']);
        $data['id'] = $id;
        //热门文章
        if ($hot_article) {
            $res = $this->hotArticle->save($data);
            if(!$res){
                $this->setError('添加失败');
                Db::rollback();
                return false;
            }
        }
        //内容中心
        if ($content_center) {
            $re = $this->content_center->save($data);
            if(!$re){
                $this->setError('添加失败');
                Db::rollback();
                return false;
            }
        }
        Db::commit();
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
        if(!isset($data['content_center'])) $data['content_center'] = 0;
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
        Db::startTrans();
        $res = $this->article->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            Db::rollback();
            return false;
        }
        //查询是否是热门文章
        $hot_info = $this->hotArticle->where($where)->find();
        if ($hot_info && !$data['hot_article']) {
            $rs = $this->hotArticle->where($where)->delete();
            if(!$rs){
                $this->setError('修改失败');
                Db::rollback();
                return false;
            }
        }
        if ($data['hot_article']) {
            $rt = $this->hotArticle->allowField(true)->save($data,$where);
            if(!$rt){
                $this->setError('修改失败');
                Db::rollback();
                return false;
            }
        }
        //查询是否属于内容中心
        $content_info = $this->content_center->where($where)->find();
        if ($content_info && !$data['content_center']) {
            $rs = $this->content_center->where($where)->delete();
            if(!$rs){
                $this->setError('修改失败');
                Db::rollback();
                return false;
            }
        }
        if ($data['content_center']) {
            $rt = $this->content_center->allowField(true)->save($data,$where);
            if(!$rt){
                $this->setError('修改失败');
                Db::rollback();
                return false;
            }
        }
        Db::commit();
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

    /**
     * 内容中心文章
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function articleContentCenter()
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
        ];
        $content_center = new ContentCenter();
        $res = $content_center->where($where)->order('order', 'desc')->select();
        foreach ($res as &$item) {
            $item['browse'] = $this->article->where(['id' => $item['id']])->value('browse');
            $item['label'] = explode(',', $item['label']);
            $item['time'] = $item['updated_time'] ? date('Y-m-d', $item['updated_time']) : date('Y-m-d', $item['created_time']);
        }
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $res;
    }

    /**
     * 热门文章
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function hotArticleList()
    {
        $hotArticle = new HotArticle();
        $hotArticleList = $hotArticle->order('order', 'desc')->select();
        foreach ($hotArticleList as &$item) {
            $item['time'] = $item['updated_time'] ? date('Y-m-d', $item['updated_time']) : date('Y-m-d', $item['created_time']);
        }
        $recommendList = $this->article->where(['deleted_time' => 0, 'status' => 1])->order('order', 'desc')->limit(0, 5)->select();
        foreach ($recommendList as &$item) {
            $item['time'] = $item['updated_time'] ? date('Y-m-d', $item['updated_time']) : date('Y-m-d', $item['created_time']);
        }
        $res = ['hot_article' => $hotArticleList, 'recommend' => $recommendList];
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $res;

    }

    /**
     * 分类文章列表
     * @param $pid
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function articleByPid($pid)
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
            'pid' => $pid,
        ];
        $res = $this->article->where($where)->order('order', 'desc')->select();
        foreach ($res as &$item) {
            $item['browse'] = $this->article->where(['id' => $item['id']])->value('browse');
            $item['label'] = explode(',', $item['label']);
            $item['time'] = $item['updated_time'] ? date('Y-m-d', $item['updated_time']) : date('Y-m-d', $item['created_time']);
        }
        if (!$res) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $res;
    }

    /**
     * 获取文章详情
     * @param $id
     * @param $pid
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function articleInfoById($id,$pid)
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
            'id' => $id,
        ];
        $info = $this->article->where($where)->find();
        if (!$info) {
            $this->setError('查询有误');
            return false;
        }
        //浏览量自增1
        $this->article->where($where)->setInc('browse');

        $info['label'] = explode(',', $info['label']);
        $info['time'] = date('Y-m-d', $info['created_time']);

        $page_where = [
            'status' => 1,
            'deleted_time' => 0,
        ];
        //查询上一篇
        //内容中心
        $pre_btn = 0;
        $nex_btn = 0;
        $pre_article = [];
        $nex_article = [];
        if ($pid == 0) {
            $article_list = $this->content_center->where($page_where)->order('order', 'desc')->select();
        }else{
            $page_where['pid'] = $pid;
            $article_list = $this->article->where($page_where)->order('order', 'desc')->select();
        }
        $last_index = count($article_list) - 1;
        $index = 0;
        foreach ($article_list as $key => $value) {
            if ($value['id'] == $id) {
                $index = $key;
            }
        }

        if ($index - 1  >= 0) {
            $pre_btn = 1;
            $pre_article = $article_list[$index - 1];
        }
        if ($index + 1 <= $last_index) {
            $nex_btn = 1;
            $nex_article = $article_list[$index + 1];
        }
        $data = [
            'info' => $info,
            'pre_nex' => [
                'pre_btn' => $pre_btn,
                'pre_article' => $pre_article,
                'nex_btn' => $nex_btn,
                'nex_article' => $nex_article,
            ],
        ];
        $this->setMessage('查询成功');
        return $data;
    }

    public function articleSearch($param)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        if ($param['pid'] == 0) {
            $where['content_center'] = $param['pid'];
        }else{
            $where['pid'] = $param['pid'];
        }
        if (isset($param['word']) && $param['word']) {
            $where['title'] = ['like','%'.$param['word'].'%'];
        }
        if (isset($param['label']) && $param['label']) {
            $where['label'] = ['like', '%' . $param['label'] . '%'];
        }
        $search_list = $this->article->where($where)->order('order', 'desc')->select();
        if (!$search_list) {
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $search_list;
    }
}