<?php

namespace app\index\controller;

use app\admin\serve\LogoWallService;
use think\Controller;
use think\Request;
use think\Validate;

class LogoWall extends Controller
{
    /**
     * logo墙
     * @return mixed
     */
    public function getLogoByWhere(Request $request){
        $rules =
            [
                'pid' => 'require',
                'type_id' => 'require',
            ];
        $msg =
            [
                'pid' => '缺少参数@pid',
                'type_id' => '缺少参数@type_id',
            ];
        $validate = new Validate($rules,$msg);
        if(!$validate->check($request->param())){
            return show(401,$validate->getError());
        }
        $logoWallService = new LogoWallService();
        $res = $logoWallService->getLogoByWhere($request->param());
        if($res){
            return show(200,$logoWallService->message,$res);
        }else{
            return show(401,$logoWallService->error);
        }
    }
}