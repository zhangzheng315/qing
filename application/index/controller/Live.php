<?php

namespace app\index\controller;

use think\Controller;
use think\Validate;

class Live extends Controller
{
    public function liveCurl($data, $url)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); //  如果不是https的就注释 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作
        if(curl_errno($curl)) {
            return false;
        }
        curl_close($curl); // 关闭CURL会话
        return json_decode($res, true);
    }

    /**
     * 登陆
     * @return array|\think\response\Json
     */
    public function liveLogin()
    {
        $data = input('post.');
        $rules =
            [
                'phone' => 'require|regex:/^1[3456789][0-9]{9}$/',
                'password' => 'require',
            ];
        $msg =
            [
                'phone.require' => '手机号不能为空',
                'phone.regex' => '手机号类型不正确',
                'password.require' => '密码不能为空',
            ];
        $validate = new Validate($rules, $msg);
        if (!$validate->check($data)) {
            return show(401, $validate->getError());
        }
        $data = [
            'mobile' => $data['phone'],
            'password' => $data['password'],
            'type' => 1,
        ];
        $url = 'https://login.lighos.com/api/v1/admin/login';
        $res = $this->liveCurl($data, $url);
        if ($res['code'] == '200') {
            return show(200,$res['message'],$res['data']);
        }
        return show(401,$res['error']);
    }

    /**
     * 试用
     * @return array|\think\response\Json
     */
    public function liveRegister()
    {
        $data = input('post.');
        $rules = [
            'name' => 'require|max:20',
            'mobile' => 'require|regex:/^1[3456789][0-9]{9}$/',
            'password' => 'require|min:6',
            'company' => 'require|max:64',
            'email' => 'email',
            'code' => 'require|max:4|min:4',
        ];
        $message = [
            'name.require' => '姓名不能为空',
            'name.max' => '姓名过长',
            'mobile.require' => '手机号不能为空',
            'mobile.regex' => '手机号类型不正确',
            'password.min' => '密码最小六位数',
            'password.require' => '密码不能为空',
            'company.require' => '公司名称不能为空',
            'company.max' => '公司名称过长',
            'email.email' => '邮箱不正确',
            'code.require' => '验证码不能为空！',
            'code.max' => '验证码长度为4位！',
            'code.min' => '验证码长度为4位！',
        ];
        $validate = new Validate($rules, $message);
        if (!$validate->check($data)) {
            return show(401, $validate->getError());
        }
        $url = 'https://login.lighos.com/api/v1/admin/register';
        $res = $this->liveCurl($data, $url);
        if ($res['code'] == '200') {
            return show(200,$res['message'],$res['data']);
        }
        return show(401,$res['error']);
    }

    /**
     * 发送手机验证码
     * @return \think\response\Json
     */
    public function liveSendCode()
    {
        $data = input('post.');
        $rules = [
            'mobile' => 'require|regex:/^1[3456789][0-9]{9}$/',
        ];
        $messages = [
            'mobile.require' => '请输入手机号',
            'mobile.regex' => '手机号格式有误',
        ];
        // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $validate = new Validate($rules, $messages);
        if (!$validate->check($data)) {
            return show(401, $validate->getError());
        }
        $data['type'] = 0;
        $url = 'https://test-login.lighos.com//api/v1/common/send_captcha';
        $res = $this->liveCurl($data, $url);
        if ($res['code'] == '200') {
            return show(200,$res['message']);
        }
        return show(401,$res['message']);
    }

    /**
     * 验证验证码
     * @return \think\response\Json
     */
    public function checkCode()
    {
        $data = input('post.');
        $rules = [
            'mobile' => 'require|regex:/^1[3456789][0-9]{9}$/',
            'code' => 'require|max:4|min:4',
        ];
        $message = [
            'mobile.require' => '手机号不能为空',
            'mobile.regex' => '手机号类型不正确',
            'code.require' => '验证码不能为空！',
            'code.max' => '验证码长度为4位！',
            'code.min' => '验证码长度为4位！',
        ];
        $validate = new Validate($rules, $message);
        if (!$validate->check($data)) {
            return show(401, $validate->getError());
        }
        $data['type'] = 0;
        $url = 'https://test-login.lighos.com/api/v1/common/check_code';
        $res = $this->liveCurl($data, $url);
        if ($res['code'] == '200') {
            return show(200,$res['message']);
        }
        return show(401,$res['error']);
    }
}
