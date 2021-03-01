<?php
namespace app\admin\controller;

use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class Label extends Common{
    public $labelService;
    public function __construct(LabelService $labelService)
    {
        parent::__construct();
        $this->labelService = $labelService;
    }

    public function index()
    {
        $add_url = '/admin/label/labelCreate';
        $edit_url = '/admin/label/labelEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取列表
     */
    public function getLabelList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = LabelService::data_paging($data,'label','order');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
            $value['hot_label'] = $value['hot_label'] == 1 ? '是' : '否';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function labelCreate(Request $request){
        $rules =
            [
                'name' => 'require',
            ];
        $msg =
            [
                'name' => '缺少参数@name',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->labelService->labelCreate($request->param());
        if($res){
            return show($this->ok,$this->labelService->message);
        }else{
            return show($this->fail,$this->labelService->error);
        }
    }

    /**
     * 文章详情
     * @return mixed
     */
    public function labelInfo(Request $request){
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
        $res = $this->labelService->labelInfo($request->param());
        if($res){
            return show($this->ok,$this->labelService->message,$res);
        }else{
            return show($this->fail,$this->labelService->error);
        }
    }

    /**
     * 文章修改
     * @return mixed
     */
    public function labelEdit(){
        $data = input('post.');
        $rules =
            [
                'name' => 'require',
            ];
        $msg =
            [
                'name' => '缺少参数@name',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->labelService->labelEdit($data);
        if($res){
            return show($this->ok,$this->labelService->message,$res);
        }else{
            return show($this->fail,$this->labelService->error);
        }
    }

    /**
     * 文章删除
     * @return mixed
     */
    public function labelDelete(){
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
        $res = $this->labelService->labelDelete($data);
        if($res){
            return show($this->ok,$this->labelService->message,$res);
        }else{
            return show($this->fail,$this->labelService->error);
        }
    }
}