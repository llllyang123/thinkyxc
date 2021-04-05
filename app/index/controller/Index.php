<?php
declare (strict_types = 1);

namespace app\index\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\facade\Cache;
use phpmailer\PHPMailer;
use phpmailer\SMTP;
use think\facade\Config;

class Index
{
    protected $middleware = [\app\index\middleware\Usernum::class];
    
    public function index()
    {
        $this->G('begin');
        $template = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->cache('template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $site_info = Db::name('setting')->where('set_name', 'site_info')->cache('site_info',Config::get('cache.expire'))->value('set_value');
        $site_seo = Db::name('setting')->where('set_name', 'site_seo')->cache('site_seo',Config::get('cache.expire'))->value('set_value');
        $site_logon = Db::name('setting')->where('set_name', 'site_logon')->cache('site_logon',Config::get('cache.expire'))->value('set_value');
        $uid = Session::get('userid');
         $newest = Db::name('thread')->where(['status'=> 1, 'type' => 1, 'delete_time' => 0 ])->order('create_dateup', 'desc')->limit(4)->cache('index_newest',Config::get('cache.list_expire'))->select();
         $friendlink = Db::name('friendlink')->where('status', 1)->order('weight')->cache('friendlink',Config::get('cache.expire'))->select();
        if(empty($uid)){
            $user = '';
            $avatar = '';
            $username = '';
        } else{
            $user = Db::name('user')->where(['uid' => $uid, 'status' =>1 ])->find();
            $avatar = $user['avatar'];
            $username = $user['username'];
        }
        $banner = Db::name('banner')->where('status',1)->order('weight')->cache('banner',Config::get('cache.expire'))->select();
        $forum = Db::name('forum')->where('status',1)->cache('forum',Config::get('cache.expire'))->select(); 
        $ok = $this->getTree1($forum);
        $this->G('end');
        // $overtime = $this->G('begin','end',3).'s';
        $overtime = $this->G('begin','end',3);
         View::assign([
             'banner' => $banner,
            'name'  => 'ThinkYXC',
            'email' => '673011635@qq.com',
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
            'avatar' => $avatar,
            'username' => $username,
            'uid' =>$uid,
             'newest' =>$newest,
             'forum' =>$ok,
             'friendlink' =>$friendlink,
             'site_logon' => json_decode($site_logon,true),
             'overtime' => $overtime,
        ]);
        return View::fetch('index');
        // return '您好！这是一个[index]示例应用';
    }
    
    
    public function mailcode(){
        $toemail = input('data');
        $event = input('event');
        if (!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $toemail)) {
                
                return json(['code' => 0, 'data' => '', 'msg' => '邮箱格式不正确' ]);
        } 
        $site_email = Db::name('setting')->where('set_name', 'site_email')->cache(Config::get('cache.expire'))->value('set_value');
        $site_email_code = Db::name('setting')->where('set_name', 'site_email_code')->cache(Config::get('cache.expire'))->value('set_value');
        $email = json_decode($site_email,true);
        $emailcode = json_decode($site_email_code,true);
        $code = $this->number();
        if($event != '测试邮件'){
            $title = $emailcode['mail_title'];
            $content = str_ireplace('{$code}', '【'.$code.'】', $emailcode['mail_template']); 
             $data = ['event' => $event, 'email' => $toemail, 'code' => $code, 'ip' => $_SERVER["REMOTE_ADDR"], 'createtime' => time()];
            Db::name('email_code')->insert($data);
        } else if($event == '测试邮件'){
             $title = '测试邮件'.$emailcode['mail_title'];
            $content = str_ireplace('{$code}', '【'.$code.'】', $emailcode['mail_template']); 
        }
          
        $ok = $this->email($toemail,$email,$emailcode,$title,$content);
        if($ok == '发送成功'){
            return json(['code' => 1, 'data' => '', 'msg' => '发送成功' ]);
        } else{
            return json(['code' => 0, 'data' => $ok, 'msg' => '发送失败' ]);
        }
        
    }
    
    //发送邮箱验证码  
        public function email($toemail,$email,$emailcode,$title,$content)  
        {  
            $toemail = $toemail;//定义收件人的邮箱  
            
            $mail = new Phpmailer();
  
            $mail->isSMTP();// 使用SMTP服务  
            $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码  
            $mail->Host = $email['mail_smtp'];// 发送方的SMTP服务器地址  
            $mail->SMTPAuth = true;// 是否使用身份验证  
            $mail->Username = $email['mail_username'];// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱</span><span style="color:#333333;">  
            $mail->Password = $email['mail_password'];// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！</span><span style="color:#333333;">  
            $mail->SMTPSecure = $email['mail_secure'];// 使用ssl协议方式</span><span style="color:#333333;">  
            $mail->Port = $email['mail_port'];// 163邮箱的ssl协议方式端口号是465/994  
  
            $mail->setFrom($email['mail_email'],$email['mail_from']);// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示  
            $mail->addAddress($toemail,$toemail);// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)  
            $mail->addReplyTo($email['mail_email'],$email['mail_email']);// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址  
            //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)  
            //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)  
            //$mail->addAttachment("bug0.jpg");// 添加附件  
  
            $mail->isHTML(true);
            $mail->Subject = $title;// 邮件标题  
            $mail->Body = $content;// 邮件正文  
            //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用  
            
            if(!$mail->send()){// 发送邮件  
                return "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息  
                echo "Message could not be sent.";  
                echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息  
            }else{  
                return '发送成功';  
                echo '发送成功';  
            }  
        }  
        
    public function number(){
        $code = '';
        for ($i=1;$i<7;$i++) {         //通过循环指定长度
            $randcode = mt_rand(0,9);     //指定为数字
            $code .= $randcode;
        }
         
        return $code;
    }
    
     public function clear(){
        Session::delete('userid');
        return redirect('/index');
    }
    
    function getTree1($data, $parent_id = 0)
{
    $tree = array();
    foreach ($data as $k => $v) {
        if ($v["fup"] == $parent_id) {
            unset($data[$k]);
            if (!empty($data)) {
                $children = $this->getTree1($data, $v["fid"]);
                if (!empty($children)) {
                    $v["children"] = $children;
                } else{
                    $v["children"] = [];
                }
            }
            $tree[] = $v;
        }
    }
    return $tree;
}


    

    function G($start,$end='',$dec=4) {
        static $_info       =   array(); //记录的是执行时间的信息    
        // static $_mem        =   array();//记录的是当前的程序执行使用了多少内存
            if(is_float($end)) { // 记录时间
                $_info[$start]  =   $end;
            }elseif(!empty($end)){ // 统计时间和内存使用
                if(!isset($_info[$end])) $_info[$end]       =  microtime(TRUE);
                // if(MEMORY_LIMIT_ON && $dec=='m'){
                    // if(!isset($_mem[$end])) $_mem[$end]     =  memory_get_usage();
                    // return number_format(($_mem[$end]-$_mem[$start])/1024);          
                // }else{
                    return number_format(($_info[$end]-$_info[$start]),$dec);
                // }       
                    
            }else{ // 记录时间和内存使用
                $_info[$start]  =  microtime(TRUE);
                // if(MEMORY_LIMIT_ON) $_mem[$start]           =  memory_get_usage();
            }
    }
    
    
}
