<?php
namespace app\admin\serve;
use app\admin\model\Article;
use think\Request;


class ArticleService extends Common{

    public $article;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->article = new Article();

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
            'status' => $param['status'] ?:1,
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
}