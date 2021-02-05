<?php
namespace app\admin\serve;
use think\Controller;
use app\admin\model\ModelAdministrators;

class Common extends Controller{

    public $error;
    public $message;
    /**
     * 获取权限数据
     * 分页处理
     */
    public static function data_paging($data,$model,$order=null){
        $contion = [];
        if (isset($data['status']) && $data['status'] != "") {
            $contion['status'] = $data['status'];
        }
        if (isset($data['deleted_time'])) {
            $contion['deleted_time'] = $data['deleted_time'];
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

     /* 设置错误信息
     * @param $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * 设置返回信息
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}