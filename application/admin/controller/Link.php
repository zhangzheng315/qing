<?php
namespace app\admin\controller;

use app\admin\model\ModelAdministrators;
use app\admin\serve\LinkService;
use app\admin\serve\NavigationService;
use think\Db;
use think\Request;
use think\Validate;

class Link extends Common{
    public $linkService;
    public $navigationService;
//    public function __construct(LinkService $linkService, NavigationService $navigationService)
//    {
//        parent::__construct();
//        $this->linkService = $linkService;
//        $this->navigationService = $navigationService;
//    }

    public function index()
    {
        $add_url = '/admin/link/linkCreate';
        $edit_url = '/admin/link/linkEdit';
        //返回加载模板输出
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function linkList(){
        $page = $this->request->get('page',1);
        $pagesize = $this->request->get('limit',10);
        $form = ($page - 1) * $pagesize;
        $str= ModelAdministrators::data_model_paging(['status'=>1],$form,$pagesize,'link','order');
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }
    /**
     * 添加文章
     * @return mixed
     */
    public function linkCreate(Request $request){
        $rules =
            [
                'linkname' => $_POST['name'],
                'link_url' => $_POST['link_url'],
                'order' =>    $_POST['order'],
                'status' =>   $_POST['status']
            ];
        $msg =
            [
                'name' => '缺少参数@name',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = Db::name('link')->insertGetId($rules);
        if($res){
            return show($this->ok,'添加友链成功！');
        }else{
            return show($this->fail,'添加友链失败！');
            return false;
        }
    }

    /**
     * 友链详情
     * @return mixed
     */
    public function linkInfo(Request $request){
        $rules =
            [
                'id' => 'require',
            ];
        $msg =
            [
                'id' => '缺少参数@id',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $id = $request->param('id');
//        $where = ['id' => $id];
        $info = Db::table('think_link')->where('id',$id)->find();
//        $navigation =Db::table('think_navigation')->where(['deleted_time' => 0])->select();
//        $info['navigation_list'] = $navigation;
        if(!$info){
            return show($this->ok,'ok',$info);
        }else{
            return show($this->ok,'ok',$info);

        }
    }

    /**
     * 友链修改
     * @return mixed
     */
    public function linkEdit(){
        $rules =
            [
                'id' => $_POST["id"],
            ];
        $data =[
                'linkname' => $_POST["linkname"],
                'link_url' => $_POST["linkurl"],
                'order' =>  $_POST['order'],
                'status' => $_POST['status']
        ];
        $msg =
            [
                'linkname' => '缺少参数@name',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = Db::name('link') ->where('id',$_POST['id']) -> setField($data);
        if($res){
            return show($this->ok,'修改成功',$res);
        }else{
            return show($this->fail,'修改失败',error);
        }
    }


    /**
     * 友链删除
     * @return mixed
     */
    public function linkDelete(){
        $data = input('post.');
        $rules =
            [
                'id' => 'require',
            ];
        $msg =
            [
                'id' => '缺少参数@id',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $datas = [
            'status' => 0,
        ];
        $res = Db::table('think_link')->where(['id'=>$data['id']])->update($datas);
        if($res){
            return show($this->ok,'删除成功');
        }else{
            return show($this->fail,'删除失败');
        }
    }
}