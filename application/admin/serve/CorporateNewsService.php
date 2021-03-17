<?php
namespace app\admin\serve;
use app\admin\model\CorporateNews;
use think\Request;


class CorporateNewsService extends Common{

    public $corporate;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->corporate = new CorporateNews();
        $this->labelService = new LabelService();
    }

    /**
     * 创建企业新闻
     * @param $data
     * @return bool
     */
    public function newCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $label = '';
        if (isset($param['label'])) {
            $label = implode(',', $param['label']);
        }
        $data = [
            'title' => $param['title'],
            'second_title' => $param['second_title'] ?: '',
            'cover_img_url' => $param['cover_img_url'] ?: '',
            'content' => $param['content'],
            'status' => $status,
            'label' => $label,
            'order' => $param['order'] ?:0,
            'created_time' => time(),
            'updated_time' => time(),
            'deleted_time' => 0,
        ];
        $id = $this->corporate->insertGetId($data);
        if(!$id) {
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 企业新闻详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  newInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->corporate->find($where);
        $label_list = $this->labelService->labelList();
        $info->label = explode(',', $info->label);
        $info->label_list = $label_list;
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        //前端点击的 增加浏览量
        if (isset($param['browse'])) {
            $this->corporate->where($where)->setInc('browse');
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 企业新闻修改
     * @param $data
     * @return bool
     */
    public function newEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        if (isset($data['file'])) {
            //layui富文本自带file参数
            unset($data['file']);
        }
        if (isset($data['label'])) {
            $data['label'] = implode(',', $data['label']);
        }else{
            $data['label'] = '';
        }

        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->corporate->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 企业新闻删除
     * @param $param
     * @return bool
     */
    public function newDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->corporate->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    /**
     * 获取企业新闻详情
     * @param $id
     * @param $pid
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function newInfoById($id)
    {
        $where = [
            'status'=>1,
            'deleted_time' => 0,
            'id' => $id,
        ];
        $info = $this->corporate->where($where)->find();
        if (!$info) {
            $this->setError('查询有误');
            return false;
        }
        //浏览量自增1
        $this->corporate->where($where)->setInc('browse');

        $info['label'] = explode(',', $info['label']);
        $info['time'] = date('Y-m-d', $info['updated_time']);

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
        $article_list = $this->corporate->where($page_where)->order('order', 'desc')->select();
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

    /**
     * 企业新闻搜索
     * @param $param
     * @return bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function newByWhere($param)
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        if (isset($param['word']) && $param['word']) {
            $where['title'] = ['like','%'.$param['word'].'%'];
        }
        if (isset($param['label']) && $param['label']) {
            $where['label'] = ['like', '%' . $param['label'] . '%'];
        }
        $search_list = $this->corporate->where($where)->order('order', 'desc')->select();
        if (!$search_list) {
            $this->setError('暂无数据');
            return false;
        }
        foreach ($search_list as &$item) {
            $item['browse'] = $this->corporate->where(['id' => $item['id']])->value('browse');
            $item['label'] = explode(',', $item['label']);
            $item['time'] = date('Y-m-d', $item['updated_time']);
        }
        $this->setMessage('查询成功');
        return $search_list;
    }

    /**
     * 企业新闻
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function corporateLimit()
    {
        $where = [
            'deleted_time' => 0,
            'status' => 1,
        ];
        $top_list = $this->corporate->where($where)->order('order', 'desc')->limit(0,2)->select();
        $bottom_list = $this->corporate->where($where)->order('order', 'desc')->limit(2,4)->select();
        foreach ($top_list as &$value) {
            $value['label'] = explode(',', $value['label']);
            $value['time'] = date('Y-m-d', $value['updated_time']);
        }
        foreach ($bottom_list as &$item) {
            $item['day'] = date('d', $item['updated_time']);
            $item['month'] = date('M', $item['updated_time']);
        }
        return ['top_list' => $top_list, 'bottom_list' => $bottom_list];
    }

}