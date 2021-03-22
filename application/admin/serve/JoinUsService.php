<?php
namespace app\admin\serve;
use app\admin\model\JoinUs;
use app\admin\model\Resume;
use PHPMailer\PHPMailer\PHPMailer;
use Qiniu\Storage\UploadManager;
use think\Request;


class JoinUsService extends Common{

    public $joinUs;
    public $resume;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->joinUs = new JoinUs();
        $this->resume = new Resume();
    }

    /**
     * 创建招聘
     * @param $data
     * @return bool
     */
    public function joinUsCreate($param){
        $status = isset($param['status']) ? $param['status'] : 0;
        $data = [
            'position' => $param['position'],
            'salary' => $param['salary'],
            'region' => $param['region'],
            'education' => $param['education'],
            'years' => $param['years'],
            'status' => $status,
            'order' => $param['order'] ?:0,
            'created_time' => time(),
            'updated_time' => time(),
            'deleted_time' => 0,
        ];
        $res = $this->joinUs->insertGetId($data);
        if(!$res){
            $this->setError('添加失败');
            return false;
        }
        $this->setMessage('添加成功');
        return true;
    }

    /**
     * 招聘详情
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  joinUsInfo($param)
    {
        $id = $param['id'];
        $where = ['id' => $id];
        $info = $this->joinUs->find($where);
        if(!$info){
            $this->setError('查询失败');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 招聘修改
     * @param $data
     * @return bool
     */
    public function joinUsEdit($data)
    {
        if(!isset($data['status'])) $data['status'] = 0;
        $where = ['id' => $data['id']];
        $data['updated_time'] = time();
        $res = $this->joinUs->allowField(true)->update($data,$where);
        if(!$res){
            $this->setError('修改失败');
            return false;
        }
        $this->setMessage('修改成功');
        return true;
    }

    /**
     * 招聘删除
     * @param $param
     * @return bool
     */
    public function joinUsDelete($param)
    {
        $where = ['id' => $param['id']];
        $data = [
            'updated_time' => time(),
            'deleted_time' => time(),
        ];
        $res = $this->joinUs->update($data,$where);
        if(!$res){
            $this->setError('删除失败');
            return false;
        }
        $this->setMessage('删除成功');
        return true;
    }

    /**
     * 加入我们列表
     * @param $param
     * @return array|bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function joinUsList($param)
    {
        $where = [
            'status' => 1,
            'deleted_time' => 0,
        ];
        if (isset($param['address']) && $param['address']) {
            switch ($param['address']) {
                case 1:
                    $address = '上海';
                    break;
                case 2:
                    $address = '北京';
                    break;
                case 3:
                    $address = '深圳';
                    break;
            }
        }
        $res = $this->joinUs->where($where)->order('order','desc')->select();
        foreach ($res as &$item) {
            $item['time'] = date('Y-m-d', $item['created_time']);
        }
        if(!$res){
            $this->setError('暂无数据');
            return [];
        }
        $this->setMessage('查询成功');
        return $res;
    }

    /**
     * 简历投递
     * @param $file
     * @param $data
     * @return bool|\think\response\Json
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendResume($file, $data)
    {
        //上传简历至七牛
        $resume_bool = $this->resumeQiNiu($file, $data);
        if (!$resume_bool) {
            $this->setError('投递失败');
            return false;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);  //后缀
        $ext = strtolower($ext); // 后缀转小写
        $ext_array = ['jpg','jpeg','png','pdf','doc','docx'];

        if (!in_array($ext, $ext_array)) {
            $this->setError('上传失败，只支持'.implode('、',$ext_array).'格式的文件');
            return false;
        }

        $file_name = $file['name'];
        $new_path = ROOT_PATH . 'public' . DS . 'upload' . DS . $file_name;
        $upload = move_uploaded_file($file['tmp_name'], $new_path);

        if (!$upload) {
            $this->setError('投递失败');
            return false;
        }

        $mail = new PHPMailer();
        //服务器配置
        $mail->CharSet ="UTF-8";                     //设定邮件编码
        $mail->SMTPDebug = 0;                        // 调试模式输出
        $mail->isSMTP();                             // 使用SMTP
        $mail->Host = 'smtp.qq.com';                // SMTP服务器
        $mail->SMTPAuth = true;                      // 允许 SMTP 认证
        $mail->Username = '1711149525@qq.com'; // SMTP 用户名  即邮箱的用户名
        $mail->Password = 'tcolrmsdoxnhjdai';             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
        $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

        $mail->setFrom('1711149525@qq.com', '行邦总管');  //发件人
        $mail->addAddress("lisa.wang@sicbon.com", "");  //收件人（用户输入的邮箱）
        $mail->addAddress("qinxiaoyun@sicbon.com.com", "");  //收件人（用户输入的邮箱）
        $mail->addAddress("Alina.xu@sicbon.com", "");  //收件人（用户输入的邮箱）
//        $mail->addAddress("zhangzheng315324@163.com", "");  //收件人（用户输入的邮箱）

        //发送附件
        $mail->addAttachment($new_path);         // 添加附件

        //Content
        $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
        $mail->Subject = '官网有新的预约';
        $mail->Body    = '姓名:'.$data['name'].
            ',<br>手机号:'.$data['mobile'].
            ',<br>邮箱:'.$data['email'].
            ',<br>手机号:'.$data['position'].
            ',<br>该访客投递了简历,请及时处理';

        $mail->send();
        unlink($new_path);
        $this->setMessage('投递成功');
        return true;
    }

    /**
     * 简历上传至七牛云
     * @param $file
     * @param $param
     * @return bool
     * @throws \Exception
     */
    public function resumeQiNiu($file, $param)
    {
        $common_file_service = new CommonFileService();
        if (!$file) {
            $this->setError('请上传文件');
            return false;
        }

        $m = 1024 * 1024;
        if ($file['size'] > (10 * $m)) {
            $this->setError('上传文件不能超过10M');
            return false;
        }
        $filePath = $file['tmp_name']; // 文件流image_info

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);  //后缀
        $ext = strtolower($ext); // 后缀转小写
        $ext_array = ['png','jpeg','jpg','pdf','doc','docx'];

        if (!in_array($ext, $ext_array)) {
            $this->setError('上传失败，只支持'.implode('、',$ext_array).'格式的文件');
            return false;
        }
        // 上传到七牛后保存的文件名
        $key = date('YmdHis') . pathinfo($file['name'], PATHINFO_BASENAME );
        if (!$token = $common_file_service->getToken()){
            $this->setError('上传失败!');
            return false;
        }
        $domain  = config('qiniu.domain');

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        list($ret, $err) = $uploadMgr->putFile($token['token'], $key, $filePath);
        if ($err !== null) {
            $this->setError('上传失败');
            return false;
        }
        $this->setMessage('上传成功!');
        $resume_url = 'https://' . $domain.'/'.$ret['key'];

        $data = [
            'name' => $param['name'],
            'mobile' => $param['mobile'],
            'email' => $param['email'] ?: '',
            'position' => $param['position'],
            'resume' => $resume_url,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];

        $add_id = $this->resume->insertGetId($data);
        if(!$add_id){
            $this->setError('上传失败');
            return false;
        }
        $this->setMessage('上传成功');
        return true;
    }
}