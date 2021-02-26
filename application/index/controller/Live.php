<?php

namespace app\index\controller;

use think\Controller;
use think\Validate;

class Live extends Controller
{
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
        $validate = new Validate($rules,$msg);
        if(!$validate->check($data)){
            return show(401,$validate->getError());
        }
        $data = [
            'mobile' => $data['phone'],
            'password' => $data['password'],
        ];
        $url = 'https://login.lighos.com/api/v1/admin/login';
        $res = $this->liveCurl($data, $url);
        $res = json_decode($res, true);
        if ($res['code'] == '200') {
            return show(200, $res['message'],$res['data']);
        }
        return show(401, $res['error']);
    }

    public function liveCurl($data,$url)
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

        curl_close($curl); // 关闭CURL会话
        return $res;
    }
}
