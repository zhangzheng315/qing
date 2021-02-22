<?php
namespace app\admin\controller;

use app\admin\serve\CaseService;
use app\admin\serve\CaseTypeService;
use app\admin\serve\LabelService;
use think\Request;
use think\Validate;

class CaseM extends Common{
    public $caseService;
    public $caseTypeService;
    public $labelService;
    public function __construct(CaseService $caseService, CaseTypeService $caseTypeService, LabelService $labelService)
    {
        parent::__construct();
        $this->caseService = $caseService;
        $this->caseTypeService = $caseTypeService;
        $this->labelService = $labelService;
    }

    public function index()
    {
        $case_type_list = $this->caseTypeService->caseTypeList();
        $label_list = $this->labelService->labelList();
        $add_url = '/admin/case_m/caseCreate';
        $edit_url = '/admin/case_m/caseEdit';
        return $this->fetch('',compact('case_type_list','label_list','add_url','edit_url'));
    }

    /**
     * 分页获取文章列表
     */
    public function getCaseList(){
        $data = input('get.');
        $data['deleted_time'] = 0;
        $str = CaseService::data_paging($data,'case','order');
        $case_type_list = $this->caseTypeService->caseTypeList();
        foreach ($str['data'] as &$value) {
            $value['status'] = $value['status'] == 1 ? '显示' : '不显示';
            $value['case_selected'] = $value['case_selected'] == 1 ? '是' : '否';
            foreach ($case_type_list as $item) {
                if ($item['id'] == $value['pid']) {
                    $value['belong'] = $item['name'];
                }
            }
        }
        return layshow($this->code,'ok',$str['data'],$str['count']);
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function caseCreate(Request $request){
        $rules =
            [
                'title' => 'require',
                'content' => 'require',
                'pid' => 'require',
            ];
        $msg =
            [
                'title' => '缺少参数@title',
                'content' => '缺少参数@content',
                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show($this->fail,$validate->getError());
        }
        $res = $this->caseService->caseCreate($request->param());
        if($res){
            return show($this->ok,$this->caseService->message);
        }else{
            return show($this->fail,$this->caseService->error);
        }
    }

    /**
     * 文章详情
     * @return mixed
     */
    public function caseInfo(Request $request){
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
        $res = $this->caseService->caseInfo($request->param());
        if($res){
            return show($this->ok,$this->caseService->message,$res);
        }else{
            return show($this->fail,$this->caseService->error);
        }
    }

    /**
     * 文章修改
     * @return mixed
     */
    public function caseEdit(){
        $data = input('post.');
        $rules =
            [
                'title' => 'require',
                'content' => 'require',
                'pid' => 'require',
            ];
        $msg =
            [
                'title' => '缺少参数@title',
                'content' => '缺少参数@content',
                'pid' => '缺少参数@pid',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show($this->fail,$validate->getError());
        }
        $res = $this->caseService->caseEdit($data);
        if($res){
            return show($this->ok,$this->caseService->message,$res);
        }else{
            return show($this->fail,$this->caseService->error);
        }
    }

    /**
     * 文章删除
     * @return mixed
     */
    public function caseDelete(){
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
        $res = $this->caseService->caseDelete($data);
        if($res){
            return show($this->ok,$this->caseService->message,$res);
        }else{
            return show($this->fail,$this->caseService->error);
        }
    }
}