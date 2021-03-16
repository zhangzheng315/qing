<?php
namespace app\admin\controller;

use app\admin\serve\AboutUsService;
use app\admin\serve\ArticleService;
use app\admin\serve\ArticleTypeService;
use app\admin\serve\ConsultingService;
use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class Consulting extends Common{
    public $consulting_service;
    public function __construct(ConsultingService $consulting_service)
    {
        parent::__construct();
        $this->consulting_service = $consulting_service;
    }

    public function index()
    {
        $add_url = '';
        $edit_url = '';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getConsultingList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = ConsultingService::data_paging($data,'consulting',['status'=>'asc','created_time'=>'desc']);
        foreach ($str['data'] as &$value) {
            $value['status_c'] = $value['status'] == 1 ? '已处理' : '未处理';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 预约详情
     * @return mixed
     */
    public function consultingInfo($id){
        $res = $this->consulting_service->consultingInfo($id);
        if($res){
            return show($this->ok,$this->consulting_service->message,$res);
        }else{
            return show($this->fail,$this->consulting_service->error);
        }
    }

    public function checkPro()
    {
        $param = input('post.');
        $rules = [
            'id' => 'require'
        ];
        $msg = [
            'id.require' => '缺少参数@id'
        ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($param)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->consulting_service->checkPro($param['id']);
        if($res){
            return show($this->ok,$this->consulting_service->message);
        }else{
            return show($this->fail,$this->consulting_service->error);
        }
    }
}