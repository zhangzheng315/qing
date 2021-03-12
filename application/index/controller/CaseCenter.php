<?php

namespace app\index\controller;

use app\admin\serve\BannerService;
use app\admin\serve\ThemeService;
use app\admin\serve\LabelService;
use think\Controller;
use think\Request;
use think\Validate;

class CaseCenter extends Controller
{
    public $case_service;
    public $labelService;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->case_service = new ThemeService();
        $this->labelService = new LabelService();
        $hot_and_rem = $this->case_service->hotAndRem();
        $label_list = $this->labelService->labelList();
        $this->assign('hot_and_rem', $hot_and_rem);
        $this->assign('label_list', $label_list);
    }

    public function index()
    {
        //精选案例
        $case_selected = $this->case_service->getCaseSelected();
        //案例轮播图
        $banner_service = new BannerService();
        $data = request()->param();
        $pid = isset($data['id']) ? $data['id'] : 4;
        $banner_list = $banner_service->bannerListByPid($pid);
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

        $res = $this->case_service->getCaseByWhere($request->param());
        if($res){
            return show(200,$this->case_service->message,$res);
        }else{
            return show(401,$this->case_service->error);
        }
    }
    /* 医疗案例 */
    public function medical()
    {
        $pid = 1;
        $param = request()->param();
        $param['pid'] = $pid;
        $case_list = $this->case_service->caseByWhere($param);
        $action = 'medical';
        return $this->fetch('',compact('case_list','pid','action'));
    }
    /* 案例详情 */
    /**
     * $type_id  1精选案例  2热门案例   3全部案例
     * @param $id
     * @param $type_id
     * @return mixed
     */
    public function caseDetail()
    {
        $param = request()->param();
        $id = $param['id'];
        $type_id = $param['type_id'];
        $param['browse'] = 1;

        $info = $this->case_service->caseInfo($param);
        switch ($info['pid']) {
            case 1:
                $pid_name = '医疗';
                $pid_url = '/index/case_center/medical';
                $action = 'medical';
                break;
            case 2:
                $pid_name = '教育';
                $pid_url = '/index/case_center/education';
                $action = 'education';
                break;
            case 3:
                $pid_name = '金融';
                $pid_url = '/index/case_center/finance';
                $action = 'finance';
                break;
            case 4:
                $pid_name = '汽车';
                $pid_url = '/index/case_center/car';
                $action = 'car';
                break;
            case 5:
                $pid_name = '科技';
                $pid_url = '/index/case_center/technology';
                $action = 'technology';
                break;
            case 6:
                $pid_name = '地产';
                $pid_url = '/index/case_center/property';
                $action = 'property';
                break;
        }
        $pid = ['pid_name' => $pid_name, 'pid_url' => $pid_url,'action'=>$action];
        $pre_and_nex = $this->case_service->preAndNext($id, $type_id, $info['pid']);

        return $this->fetch('',compact('info','pid','pre_and_nex'));
    }
    /* 金融案例 */
    public function finance()
    {
        $pid = 3;
        $param = request()->param();
        $param['pid'] = $pid;
        $case_list = $this->case_service->caseByWhere($param);
        $action = 'finance';
        return $this->fetch('',compact('case_list','pid','action'));
    }
    /* 教育案例 */
    public function education()
    {
        $pid = 2;
        $param = request()->param();
        $param['pid'] = $pid;
        $case_list = $this->case_service->caseByWhere($param);
        $action = 'education';
        return $this->fetch('',compact('case_list','pid','action'));
    }
    /* 汽车案例 */
    public function car()
    {
        $pid = 4;
        $param = request()->param();
        $param['pid'] = $pid;
        $case_list = $this->case_service->caseByWhere($param);
        $action = 'car';
        return $this->fetch('',compact('case_list','pid','action'));
    }
    /* 科技案例*/
    public function technology()
    {
        $pid = 5;
        $param = request()->param();
        $param['pid'] = $pid;
        $case_list = $this->case_service->caseByWhere($param);
        $action = 'technology';
        return $this->fetch('',compact('case_list','pid','action'));
    }
    /* 地产案例*/
    public function property()
    {
        $pid = 6;
        $param = request()->param();
        $param['pid'] = $pid;
        $case_list = $this->case_service->caseByWhere($param);
        $action = 'property';
        return $this->fetch('',compact('case_list','pid','action'));
    }
}