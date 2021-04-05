<?php
declare (strict_types = 1);

namespace app\index\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Event;
use think\captcha\facade\Captcha;
use MD\src\MDAvatars;
use think\facade\Config;


class Reg
{
    public function index()
    {
         $template = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->cache('template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $site_info = Db::name('setting')->where('set_name', 'site_info')->cache('site_info',Config::get('cache.expire'))->value('set_value');
        $site_seo = Db::name('setting')->where('set_name', 'site_seo')->cache('site_seo',Config::get('cache.expire'))->value('set_value');
        $site_logon = Db::name('setting')->where('set_name', 'site_logon')->cache('site_logon',Config::get('cache.expire'))->value('set_value');
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
            'site_logon' => json_decode($site_logon,true),
        ]);
        
        return View::fetch('index');
        
        
    }
    
    
    public function reg(){
        $data = input('data');
        $username = $data['username'];
        $password = $data['password'];
        $passwords = $data['passwords'];
        $email = $data['email'];
        $value = $data['captcha'];
         // 检测输入的验证码是否正确，$value为用户输入的验证码字符串
        if( !captcha_check($value ))
        {
        	// 验证失败
        		return json([ 'code' =>0, 'data' =>'', 'msg' => '验证码不正确' ]);
        }
        
        $useremailov = Db::name('user')->where('email', $email)->find();
        if($useremailov){
            return json([ 'code' =>0, 'data' =>'', 'msg' => '该邮箱已经被注册' ]);
        }
        $userov = Db::name('user')->where('username', $username)->find();
        if($userov){
            return json([ 'code' =>0, 'data' =>'', 'msg' => '该用户名已经被注册' ]);
        }
        if($password != $passwords){
            	return json([ 'code' =>0, 'data' =>'', 'msg' => '两次输入密码不一致' ]);
        }
        $site_logon = Db::name('setting')->where('set_name', 'site_logon')->value('set_value');
        $logon = json_decode($site_logon,true);
        if($logon['logon_user'] == 1){
                $mailcaptcha = $data['mailcaptcha'];
                $codeov = Db::name('email_code')->where(['email' => $email, 'code' => $mailcaptcha ])->find();
            if(!$codeov){
                return json([ 'code' =>0, 'data' =>'', 'msg' => '邮箱验证码不正确' ]);
            }
            if($codeov['num'] > 0){
                return json([ 'code' =>0, 'data' =>'', 'msg' => '邮箱验证码已失效' ]);
            }
        }
        
        
        $create_ip = $_SERVER["REMOTE_ADDR"];
        $create_date = time();
        $salt = $this->salt();
        $pass = md5(md5($password).$salt);
        $data = ['gid' => 101, 'email' => $email, 'username' => $username, 'password' => $pass, 'salt' => $salt, 'create_ip' => $create_ip, 'create_date' => $create_date ];
        $ok = Db::name('user')->insertGetId($data);
        
        //头像生成
        $Avatar = new MDAvatars($username, 512);
		$OutputSize = 256;
		$Avatar->Output2Browser($OutputSize);
		// Output Base64 encoded image data.
		$Avatar->Output2Base64($OutputSize);
		// Get an image resource identifier.
		$Avatar->Output2ImageResource($OutputSize);
		$Avatar->Save('../public/uploads/avatar/Avatar'.$ok.'.png', 128);
		
        if($ok){
            Db::name('user')->where('uid',$ok)->update(['avatar' => '/uploads/avatar/Avatar'.$ok.'.png']);
            if($logon['logon_user'] == 1){
                Db::name('email_code')->where(['email' => $email, 'code' => $mailcaptcha ])->inc('num')->update();
            }
            
            return json(['code' => 1, 'data' => '/login', 'msg' => '注册成功' ]);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '注册失败，请重试' ]);
        }
        
    }
    
    
    public function salt(){
        $string = '';
        for ($i=1;$i<13;$i++) {    
             $randstr = chr(rand(65,90));    //指定为字母
            $string .= $randstr; 
        }
        return $string;
    }
    
    public function number(){
        $code = '';
        for ($i=1;$i<7;$i++) {         //通过循环指定长度
            $randcode = mt_rand(0,9);     //指定为数字
            $code .= $randcode;
        }
         
        return $code;
    }
    
    
}