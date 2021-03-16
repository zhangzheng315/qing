<?php
namespace app\admin\model;
use think\Model;

class Common extends Model{

    /**
     * 分页查询
     */
    public static function data_model_paging($map,$form,$size,$model,$order=null){
        $order = $order ? $order : ['id'=>'desc'];
        $data = db("$model")
        ->where($map)
        ->order($order)
        ->limit($form,$size)
        ->select();
        $count = $count = db("$model")->where($map)->count();
        return ['data'=>$data,'count'=>$count];
    }

    /**
     * 获取指定字段数据列表
     */
    public static function getFieldList($model,$field,$map){
        $data = db("$model")->where($map)->field("$field")->select();
        return $data;
    }

}