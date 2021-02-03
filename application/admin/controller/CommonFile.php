<?php
namespace app\admin\controller;

use app\admin\serve\CommonFileService;
use app\admin\serve\NavigationService;
use think\Request;
use think\Validate;

class CommonFile extends Common{
    public $commonFileService;
    public function __construct(CommonFileService $commonFileService)
    {
        parent::__construct();
        $this->commonFileService = $commonFileService;
    }

    /**
     * 文件上传
     * @return \think\response\Json
     */
    public function uploadFile()
    {
        $file = request()->file('file');
        $res = $this->commonFileService->uploadFile($file);
        if($res){
//            return show($this->ok,$this->commonFileService->message,['img_url'=>$res]);
            return ['code' => 0, 'msg' => $this->commonFileService->message, 'data' => $res];
        }else{
            return show($this->fail,$this->commonFileService->error);
        }
    }
}