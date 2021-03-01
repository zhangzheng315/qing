<?php
namespace app\admin\serve;
use app\admin\model\ArticleType;
use app\admin\model\ContentCenter;
use app\admin\model\HotArticle;
use think\Db;
use think\Request;


class HotArticleService extends Common{

    public $hotArticle;
    public $articleType;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->hotArticle = new HotArticle();
        $this->articleType = new ArticleType();
        $this->labelService = new LabelService();
    }

    /**
     * 热门文章详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  hotArticleInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->hotArticle->find($where);
        $article_type = $this->articleType->where(['deleted_time' => 0,'status'=>1])->select();
        $info->article_type = $article_type;
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 热门文章修改
     * @param $data
     * @return bool
     */
    public function hotArticleEdit($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'order' => $param['order'] ?: 0,
            'updated_time' => time(),
        ];
        $rs = $this->hotArticle->update($data,$where);
        if(!$rs){
            $this->setError('修改失败');
            Db::rollback();
            return false;
        }
        Db::commit();
        $this->setMessage('修改成功');
        return true;
    }
}