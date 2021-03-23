<?php
namespace app\admin\serve;
use app\admin\model\AboutUs;
use app\admin\model\Consulting;
use PHPMailer\PHPMailer\PHPMailer;
use think\Exception;
use think\Request;


class ConsultingService extends Common{

    public $consulting;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->consulting = new Consulting();
    }

    /**
     * 添加预约
     * @param $data
     * @return bool
     */
    public function consultingCreate($param){
        $data = [
            'name' => $param['name'],
            'phone' => $param['phone'],
            'email' => $param['email'] ?: '',
            'company' => $param['company'] ?: '',
            'industry' => '',
            'describe' => $param['describe'] ?: '',
            'status' => 0,
            'created_time' => time(),
            'updated_time' => 0,
            'deleted_time' => 0,
        ];
        $id = $this->consulting->insertGetId($data);

        if(!$id){
            $this->setError('预约失败');
            return false;
        }

        //发送邮件
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
        $mail->addAddress("frank.fu@sicbon.com", "付总");  //收件人（用户输入的邮箱）
        $mail->addAddress("neal.tang@sicbon.com", "小唐总");  //收件人（用户输入的邮箱）
        $mail->addAddress("collen@sicbon.com", "Collen总");  //收件人（用户输入的邮箱）
        $mail->addAddress("steven@sicbon.com", "朱总");  //收件人（用户输入的邮箱）

        //发送附件
        // $mail->addAttachment('../xy.zip');         // 添加附件
        // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名

        //Content
        $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
        $mail->Subject = '官网有新的预约';
        $mail->Body    = '姓名:'.$param['name'].
            ',<br>手机号:'.$param['phone'].
            ',<br>邮箱:'.$param['email']?:'暂无'.
            ',<br>公司名:'.$param['company']?:'暂无'.
//            ',<br>行业:'.$param['industry'].
            ',<br>描述:'.$param['describe']?:'暂无'.
            ',<br>该访客有新的资讯消息,请及时处理';

        $mail->send();

        $this->setMessage('预约成功');
        return true;
    }

    /**
     * 预约详情
     * @param $id
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  consultingInfo($id)
    {
        $where = ['id' => $id];
        $info = $this->consulting->find($where);
        if(!$info){
            $this->setError('暂无数据');
            return false;
        }
        $this->setMessage('查询成功');
        return $info;
    }

    /**
     * 后台首页统计预约数量
     * @return array
     * @throws \think\Exception
     */
    public function consultingStatistics()
    {
        $all_num = $this->consulting->count('id');
        $un_pro_num = $this->consulting->where(['status' => 0])->count();
        $pro_num = $all_num - $un_pro_num;
        return ['all_num' => $all_num, 'un_pro_num' => $un_pro_num, 'pro_num' => $pro_num];
    }

    /**
     * 更改预约状态
     * @param $id
     * @return bool
     */
    public function checkPro($id)
    {
        $where = ['id' => $id,'status'=>1];
        $info = $this->consulting->where($where)->value('id');
        if ($info) {
            $this->setError('该预约已处理');
            return false;
        }
        $res = $this->consulting->where(['id' => $id])->update(['status' => 1]);
        if (!$res) {
            $this->setError('操作失败');
            return false;
        }
        $this->setMessage('操作成功');
        return true;
    }

}