<?php
namespace app\admin\serve;
use app\admin\model\CommonFile;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Request;


class CommonFileService extends Common{

    public $commonFile;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->commonFile = new CommonFile();

    }

    /**
     * 创建菜单
     * @param $data
     * @return bool
     */
    public function uploadFile($file){
        if (!$file) {
            $this->setError('请上传文件');
            return false;
        }

        $m = 1024 * 1024;
        if ($file->getinfo('size') > (10 * $m)) {
            $this->setError('上传文件不能超过10M');
            return false;
        }
        $filePath = $file->getRealPath(); // 文件流image_info

        $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
        $ext = strtolower($ext); // 后缀转小写
        $ext_array = ['png','jpeg','jpg','gif','svg','pdf'];

        if (!in_array($ext, $ext_array)) {
            $this->setError('上传失败，只支持'.implode('、',$ext_array).'格式的文件');
            return false;
        }
        // 上传到七牛后保存的文件名
        $key_name = substr(md5($file->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999);
        $key = $key_name . '.' . $ext;
        if (!$token = $this->getToken()){
            $this->setError('上传失败!');
            return false;
        }
        $domain  = config('qiniu.domain');

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        list($ret, $err) = $uploadMgr->putFile($token['token'], $key, $filePath);
        if ($err !== null) {
            $this->setError('上传失败1');
            return false;
        }
        $this->setMessage('上传成功!');
        $img_url = 'https://' . $domain.'/'.$ret['key'];

        $data = [
            'img_url' => $img_url,
            'ext' => $ext,
            'status' => 1,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];

        $add_id = $this->commonFile->insertGetId($data);
        if(!$add_id){
            $this->setError('上传失败2');
            return false;
        }
        $this->setMessage('上传成功');
        return ['src'=>$img_url];
    }

    /**
     * 生成七牛云token
     * @return bool|string
     */
    public function getToken(){
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = config('qiniu.AccessKey');
        $secretKey = config('qiniu.SecretKey');
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 要上传的空间
        $bucket = config('qiniu.bucket');
        $domain  = config('qiniu.domain');
        if (!$token = $auth->uploadToken($bucket)){
            $this->setError('上传失败3');
            return false;
        }
        return ['token'=>$token,'domain'=>$domain];
    }



}