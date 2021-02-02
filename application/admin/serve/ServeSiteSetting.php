<?php
namespace app\admin\serve;
use app\admin\serve\Common;
use think\Log;


class ServeSiteSetting extends Common{
    
    public static function saveSiteSettingServe($data){
        try{
            $save = db('site_setting')->where('id',1)->update($data);
            if($save){
                return true;
            }else{
                return false;
            }
        }catch(\Exception $e){
            Log::error('/admin/serve/ServeSiteSetting:'.$e->getMessage());
            return false;
        }
    }

}