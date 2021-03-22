<?php

namespace app\index\controller;

use app\admin\serve\JoinUsService;
use PHPMailer\PHPMailer\PHPMailer;
use think\Controller;
use think\Validate;

class Join extends Controller
{
    
    public function joinUs()
    {
        $param = request()->param();
        $joinUsService = new JoinUsService();
        $join_list = $joinUsService->joinUsList($param);
        return $this->fetch('',compact('join_list'));
    }

    /**
     * 简历投递
     * @return \think\response\Json
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendResume()
    {
        $file = $_FILES['file'];
        $data = input('post.');
        if (!$file) {
            return show(401, '请上传文件');
        }
        $rules = [
            'mobile' => 'require|regex:/^1[3456789][0-9]{9}$/',
            'name' => 'require',
            'position' => 'require',
        ];
        $message = [
            'mobile.require' => '手机号不能为空',
            'mobile.regex' => '手机号类型不正确',
            'name.require' => '姓名不能为空！',
            'position.require' => '姓名不能为空！',
        ];
        $validate = new Validate($rules, $message);
        if (!$validate->check($data)) {
            return show(401, $validate->getError());
        }
        $joinUsService = new JoinUsService();
        $res = $joinUsService->sendResume($file, $data);
        if (!$res) {
            return show(401,$joinUsService->error);
        }
        return show(200,$joinUsService->message);
    }
}