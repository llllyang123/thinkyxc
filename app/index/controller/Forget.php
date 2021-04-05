<?php
declare (strict_types = 1);

namespace app\index\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Event;
use think\captcha\facade\Captcha;
use think\facade\Config;


class Forget
{
    protected $middleware = [\app\index\middleware\Usernum::class];
    
    public function index()
    {
        $this->G('begin');
         $template = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->cache('template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $site_info = Db::name('setting')->where('set_name', 'site_info')->cache('site_info',Config::get('cache.expire'))->value('set_value');
        $site_seo = Db::name('setting')->where('set_name', 'site_seo')->cache('site_seo',Config::get('cache.expire'))->value('set_value');
        $this->G('end');
        $overtime = $this->G('begin','end',3);
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
            'overtime' => $overtime,
        ]);
        
        return View::fetch('index');
        
        
    }
    
    
    public function forget(){
        $data = input('data');
        $email = $data['email'];
        $password = $data['password'];
        $passwords = $data['passwords'];
        $mailcaptcha = $data['mailcaptcha'];
        $value = $data['captcha'];
        $user = Db::name('user')->where(['email' => $email])->find();
        $codeov = Db::name('email_code')->where(['email' => $email, 'code' => $mailcaptcha ])->find();
         // 检测输入的验证码是否正确，$value为用户输入的验证码字符串
        if( !captcha_check($value ))
        {
        	// 验证失败
        		return json([ 'code' =>0, 'data' =>'', 'msg' => '验证码不正确' ]);
        }
        if(!$user){
            return json([ 'code' =>0, 'data' =>'', 'msg' => '该账户不存在，请重新确认' ]);
        } elseif($user['gid'] == 1){
            return json([ 'code' =>0, 'data' =>'', 'msg' => '该账户属于管理员，您无权修改密码' ]);
        }
        if(!$codeov){
            return json([ 'code' =>0, 'data' =>'', 'msg' => '邮箱验证码不正确' ]);
        }
        if($codeov['num'] > 0){
            return json([ 'code' =>0, 'data' =>'', 'msg' => '邮箱验证码已失效' ]);
        }
        if($password != $passwords){
            	return json([ 'code' =>0, 'data' =>'', 'msg' => '两次输入密码不一致' ]);
        }
        $create_ip = $_SERVER["REMOTE_ADDR"];
        $create_date = time();
        $reg = new Reg();
        $index = new Index();
        $salt = $reg->salt();
        $pass = md5($password);
        $datas = ['email' => $email, 'create_ip' => $create_ip, 'create_date' => $create_date, 'passwordpast' => $user['password'], 'password' => $pass,  'saltpast' => $user['salt'], 'salt' => $salt,];
            Db::name('forget_log')->insert($datas);
        
        if($datas){
            $ok = Db::name('user')->where('email', $email)->update(['password' => $pass, 'salt' =>$salt ]);
            Db::name('email_code')->where(['email' => $email, 'code' => $mailcaptcha ])->inc('num')->update();
            
            $toemail = $email;
            $site_email = Db::name('setting')->where('set_name', 'site_email')->cache(Config::get('cache.expire'))->value('set_value');
            $site_email_code = Db::name('setting')->where('set_name', 'site_email_code')->cache(Config::get('cache.expire'))->value('set_value');
            $emails = json_decode($site_email,true);
            $emailcode = json_decode($site_email_code,true);
            $title = $emailcode['mail_title'].'--找回密码';
            $content = '您的账号密码于'.date("Y 年 m 月 d 日 H 点 i 分 s 秒").'已经被修改，如不是本人操作，请立即联系客服，以免造成损失';
            $okmail = $index->email($toemail,$emails,$emailcode,$title,$content);
            
            return json(['code' => 1, 'data' => '', 'msg' => '修改密码成功' ]);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '修改密码失败，请重试' ]);
        }
        
    }
    
    public function ceshi(){
        // R("index/number");  //控制器名/方法名
        $menus=new Index();
        echo $menus->email($toemail,$email,$emailcode,$title,$content);
        
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