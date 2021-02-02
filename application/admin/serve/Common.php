<?php
namespace app\admin\serve;
use think\Controller;
use app\admin\model\ModelAdministrators;

class Common extends Controller{

    /**
     * 获取权限数据
     * 分页处理
     */
    public static function data_paging($data,$model,$order=null){
        $contion = [];
        if (isset($data['status']) && $data['status'] != "") {
            $contion['status'] = $data['status'];
        }
        $page = !empty($data['page']) ? $data['page'] : 1;
        $size = !empty($data['limit']) ? $data['limit'] : 20;
        $form = ($page - 1) * $size;
        return ModelAdministrators::data_model_paging($contion,$form,$size,$model,$order);
    }

    /**
     * 获取指定数据
     */
    public static function data_one_info($map=[],$model){
        $info = db("$model")->where($map)->find();
        return $info;
    }

}