<?php

namespace app\index\controller;

use app\admin\serve\ConsultingService;
use think\Controller;
use think\Validate;

class Consulting extends Controller
{
    /**
     * 添加预约
     * @return \think\response\Json
     */
    public function consultingAdd()
    {
        $data = input('post.');
        $rules =
            [
                'phone' => 'require|regex:/^1[3456789][0-9]{9}$/',
                'name' => 'require',
                'email' => 'require|email',
                'company' => 'require',
                'industry' => 'require',
                'describe' => 'require',
            ];
        $msg =
            [
                'phone.require' => '请填写手机号',
                'phone.regex' => '手机号类型不正确',
                'name.require' => '请填写姓名',
                'email.require' => '请填写邮箱',
                'email.email' => '邮箱格式不正确',
                'company.require' => '请填写公司名称',
                'industry.require' => '请填写所在行业',
                'describe.require' => '请填写描述',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show(401,$validate->getError());
        }
        $consulting_service = new ConsultingService();
        $res = $consulting_service->consultingCreate($data);
        if (!$res) {
            return show(401,$consulting_service->error);
        }
        return show(200,$consulting_service->message);
    }
}