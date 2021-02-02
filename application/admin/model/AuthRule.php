<?php
namespace app\admin\model;
use app\admin\model\Common;

class AuthRule extends Common{

    public  function rule($val){
        $rule = $this->where('rule_id',$val)->where('status',1)->order('sort')->field('href,title,pid,rule_id,nav_id')->find()->toArray();;
        return $rule;
    }
    
}