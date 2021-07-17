<?php
namespace app\admin\controller;

use app\admin\serve\ContactService;
use think\Request;
use think\Validate;

class Contact extends Common{
    public $contactService;
    public function __construct(ContactService $contactService)
    {
        parent::__construct();
        $this->contactService = $contactService;
    }

    public function index()
    {
        $add_url = '/admin/contact/contactCreate';
        $edit_url = '/admin/contact/contactEdit';
        return $this->fetch('',compact('add_url','edit_url'));
    }

    /**
     * 分页获取联系方式列表
     */
    public function getContactList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = ContactService::data_paging($data,'contact','id');
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加联系方式
     * @return mixed
     */
    public function contactCreate(Request $request){
        $rules =
            [
                'type_name' => 'require',
                'num_en' => 'require',
            ];
        $msg =
            [
                'type_name' => '缺少参数@type_name',
                'num_en' => '缺少参数@num_en',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->contactService->contactCreate($request->param());
        if($res){
            return show($this->ok,$this->contactService->message);
        }else{
            return show($this->fail,$this->contactService->error);
        }
    }

    /**
     * 联系方式详情
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function contactInfo(Request $request){
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
        $res = $this->contactService->contactInfo($request->param());
        if($res){
            return show($this->ok,$this->contactService->message,$res);
        }else{
            return show($this->fail,$this->contactService->error);
        }
    }

    /**
     * 联系方式修改
     * @return mixed
     */
    public function contactEdit(){
        $data = input('post.');
        $rules =
            [
                'type_name' => 'require',
                'num_en' => 'require',
            ];
        $msg =
            [
                'type_name' => '缺少参数@type_name',
                'num_en' => '缺少参数@num_en',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->contactService->contactEdit($data);
        if($res){
            return show($this->ok,$this->contactService->message,$res);
        }else{
            return show($this->fail,$this->contactService->error);
        }
    }

    /**
     * 联系方式删除
     * @return mixed
     */
    public function contactDelete(){
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
        $res = $this->contactService->contactDelete($data);
        if($res){
            return show($this->ok,$this->contactService->message,$res);
        }else{
            return show($this->fail,$this->contactService->error);
        }
    }
}