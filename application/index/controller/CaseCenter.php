<?php

namespace app\index\controller;

use app\admin\model\Banner;
use app\admin\serve\CaseService;
use think\Controller;
use think\Request;
use think\Validate;

class CaseCenter extends Controller
{
    public function index()
    {
        //精选案例
        $case_service = new CaseService();
        $case_selected = $case_service->getCaseSelected();
        //案例轮播图
        $banner_model = new Banner();
        $where = [
            'status' => 1,
            'deleted_time' => 0,
            'pid' => 4,
        ];
        $banner_list = $banner_model->where($where)->select();
        return $this->fetch('',compact('case_selected', 'banner_list'));
    }

    public function getCaseByWhere(Request $request){
        $rules =
            [
                'pid' => 'require',
                'curr' => 'require',
            ];
        $msg =
            [
                'pid' => '缺少参数@pid',
                'curr' => '缺少参数@curr',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show(401,$validate->getError());
        }
        $case_service = new CaseService();
        $res = $case_service->getCaseByWhere($request->param());
        if($res){
            return show(200,$case_service->message,$res);
        }else{
            return show(401,$case_service->error);
        }
    }
}