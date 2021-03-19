<?php
namespace app\admin\model;
use app\admin\model\Common;

class Admin extends Common{

    public function rules(){
        $map['a.id']= session('uid');
        $map['ag.status'] = 1;
        $rules = $this->alias('a')
                ->join("auth_group ag", 'a.group_id = ag.group_id', 'right')
                ->where($map)
                ->value('ag.rules');
        return $rules;
    }
    
}