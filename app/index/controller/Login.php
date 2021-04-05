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


class Login
{
    public function index()
    {
        $template = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->cache('template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $site_info = Db::name('setting')->where('set_name', 'site_info')->cache('site_info',Config::get('cache.expire'))->value('set_value');
        $site_seo = Db::name('setting')->where('set_name', 'site_seo')->cache('site_seo',Config::get('cache.expire'))->value('set_value');
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
        ]);
        
        return View::fetch('index');
    }
    
    public function login(){
        $template = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->cache('template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $data = input('data');
        $username = $data['username'];
        $password = $data['password'];
        $value = $data['captcha'];
        $user;
        // 检测输入的验证码是否正确，$value为用户输入的验证码字符串
        if( !captcha_check($value ))
        {
        	// 验证失败
        		return json([ 'code' =>0, 'data' =>'', 'msg' => '验证码不正确' ]);
        }
        if (preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $username)) {
                $user = Db::name('user')->where('email', $username)->find();
                $this->user($user);
                
        } else if (preg_match("/^1[3456789]\d{9}$/", $username)) {
                $user = Db::name('user')->where('mobile', $username)->find();
                $this->user($user);
          } else{
              $user = Db::name('user')->where('username', $username)->find();
              $this->user($user);
          }  
            if($user['status'] ==2 ){
                 return json([ 'code' =>0, 'data' =>'', 'msg' => '抱歉，账号因违规操作被列入黑名单' ]);
            } else if($user['status'] ==3 ){
                 return json([ 'code' =>0, 'data' =>'', 'msg' => '账号异常，请联系管理员' ]);
            }
        $pass = $user['password'];
        $loginpass =  md5(md5($password).$user['salt']);
        
        if($pass == $loginpass){
            Db::name('user')->where('email', $user['email'])->update(['login_ip' => $_SERVER["REMOTE_ADDR"], 'login_date' => time() ]);
              Session::set('userid', $user['uid']);
            // echo("登录成功");
                return json([ 'code' =>1, 'data' =>'/index', 'msg' => '登录成功' ]);
                return redirect('/index');
        } else{
            
            return json([ 'code' =>0, 'data' =>'', 'msg' => '账号或密码不正确' ]);
        }
        
    }
    
     public function adminindex()
    {
        $template = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->cache('template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $site_info = Db::name('setting')->where('set_name', 'site_info')->cache('site_info',Config::get('cache.expire'))->value('set_value');
        $site_seo = Db::name('setting')->where('set_name', 'site_seo')->cache('site_seo',Config::get('cache.expire'))->value('set_value');
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
        ]);
        
        return View::fetch('admin');
    }
    
    public function adminlogin(){
        $template = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->cache('template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $data = input('data');
        $username = $data['username'];
        $password = $data['password'];
        $value = $data['captcha'];
        $user;
        // 检测输入的验证码是否正确，$value为用户输入的验证码字符串
        if( !captcha_check($value ))
        {
        	// 验证失败
        		return json([ 'code' =>0, 'data' =>'', 'msg' => '验证码不正确' ]);
        }
        if (preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $username)) {
                $user = Db::name('user')->where('email', $username)->find();
                $this->user($user);
                
        } else if (preg_match("/^1[3456789]\d{9}$/", $username)) {
                $user = Db::name('user')->where('mobile', $username)->find();
                $this->user($user);
          } else{
              $user = Db::name('user')->where('username', $username)->find();
              $this->user($user);
          }  
            
        $pass = $user['password'];
        $loginpass =  md5(md5($password).$user['salt']);
        
        if($pass == $loginpass){
            if($user['gid']>=1&&$user['gid']<=5){
                 Session::set('admin.id', $user['uid']);
            }
            Db::name('user')->where('email', $user['email'])->update(['login_ip' => $_SERVER["REMOTE_ADDR"], 'login_date' => time() ]);
              Session::set('userid', $user['uid']);
            // echo("登录成功");
            $admin_id=Session::get('admin.id');
            $userid=Session::get('userid');
                if(!$admin_id&&$userid){
                        return json(['code' => 0, 'data' => '', 'msg' => '该账户不是管理员']);  
                }
                // echo json_encode(['code' =>1, 'data' =>'/admin', 'msg' => '登录成功'],JSON_UNESCAPED_UNICODE);
                     Session::delete('backurl');
                return json([ 'code' =>1, 'data' =>'/admin', 'msg' => '登录成功' ]);
                
                // return redirect('/admin');
        } else{
            
            return json([ 'code' =>0, 'data' =>'', 'msg' => '账号或密码不正确' ]);
        }
        
        
    }
    
    public function user($user){
        if(!$user){
                return json([ 'code' =>0, 'data' =>'', 'msg' => '未找到相关账号' ]);
            } else{
                if($user['status'] == 2){
                    return json([ 'code' =>0, 'data' =>'', 'msg' => '该账号已被列入黑名单' ]);
                } else if($user['status'] == 3){
                    return json([ 'code' =>0, 'data' =>'', 'msg' => '该账号异常，请联系管理员' ]);
                } else if($user['status'] == 0){
                    return json([ 'code' =>0, 'data' =>'', 'msg' => '该账号已注销或未找到相关账号' ]);
                }
            }
        
    }
    
    
    
    public function chak(){
        
        
        
    }
   
    
}
