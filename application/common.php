<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//layui框架table输出
function layshow($code,$msg,$data=[],$count){
	$result = [
		'code'  => $code,
		'msg' => $msg,
		'count' => $count,
		'data'    => $data,
		
	];
	
	return json($result);

}

/**
 * josn输出
 */
function show($status,$message,$data=[]){
    $data = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];
    return json($data);
}

/**
 * 格式化打印数据
 */
function dd($array) {
    echo '<pre>';
    var_dump($array);
    echo '<pre>';
    die;
}