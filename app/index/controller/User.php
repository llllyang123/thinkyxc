<?php
declare (strict_types = 1);

namespace app\index\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use phpmailer\PHPMailer;
use phpmailer\SMTP;
use think\facade\Config;


class User
{
    
    protected $middleware = [\app\index\middleware\Check::class,\app\index\middleware\Usernum::class];
    
    public function index()
    {
       
        
         $this->data();
         
         View::assign('indexname','个人中心');
        
        return View::fetch('index');
        
 
    }
    
    public function edit()
    {
       
        
         $this->data();
         
         View::assign('indexname','修改资料');
        
        return View::fetch('edit');
        
 
    }
    
    public function collect()
    {
       
        
         $this->data();
         
         View::assign('indexname','我的收藏');
        
        return View::fetch('collect');
        
 
    }
    
    public function password(){
        
        $this->data();
        
        View::assign('indexname','修改密码');
        
        return View::fetch('password');
        
    }
    
     public function avatar(){
        
        $this->data();
        
        View::assign('indexname','头像设置');
        
        return View::fetch('avatar');
        
    }
    

    
    public function data(){
        $this->G('begin');
         $template = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->cache('template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $site_info = Db::name('setting')->where('set_name', 'site_info')->cache('site_info',Config::get('cache.expire'))->value('set_value');
        $site_seo = Db::name('setting')->where('set_name', 'site_seo')->cache('site_seo',Config::get('cache.expire'))->value('set_value');
        $site_logon = Db::name('setting')->where('set_name', 'site_logon')->cache('site_logon',Config::get('cache.expire'))->value('set_value');
        $uid = Session::get('userid');
         $newest = Db::name('thread')->where(['status'=> 1, 'type' => 1])->order('create_dateup', 'desc')->limit(4)->cache('newest',Config::get('cache.list_expire'))->select();
         $friendlink = Db::name('friendlink')->where('status', 1)->order('weight')->cache('friendlink',Config::get('cache.expire'))->select();
        if(empty($uid)){
            $user = '';
            $avatar = '';
            $username = '';
        } else{
            $user = Db::name('user')->where(['uid' => $uid, 'status' =>1 ])->withoutField(['password','password_sms','salt'])->find();
            $avatar = $user['avatar'];
            $username = $user['username'];
        }
        $forum = Db::name('forum')->where('status',1)->cache('forum',Config::get('cache.expire'))->select(); 
        $ok = $this->getTree1($forum);
        $this->G('end');
        $overtime = $this->G('begin','end',3);
        View::assign([
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
             'user' => $user,
             'overtime' => $overtime,
        ]);
        
    }
    
     public function collectlist()
    {
       $uid = Session::get('userid');
       if(empty($uid)){
             return json(['code' =>0 ,'data' => '', 'msg' => '请登录']);
        }
        $page = intval(input('page'));
        $limit = intval(input('limit'));
        $collect = Db::name('collect')->where(['uid' => $uid])->page($page,$limit)->select();
        $count = Db::name('collect')->where(['uid' => $uid])->count();
        $collect_list = [];
        if(!empty($collect)){
            foreach($collect as $k => $v){
                $thread = Db::name('thread')->where(['tid' => $v['tid'] ])->find();
                if(!empty($thread)){
                    if(empty($thread['subject'])){
                        $thread['subject'] = '该文章已走丢……';
                    }
                    $collect_list[$k] = array_merge($v,$thread);
                }
                
            }
            return json(['code' =>1 ,'data' => $collect_list, 'msg' => '成功','count' => $count]);
        } else{
            return json(['code' =>0 ,'data' => '', 'msg' => '无数据']);
        }
        
    }
    
    public function collectdeil(){
         $uid = Session::get('userid');
       if(empty($uid)){
             return json(['code' =>0 ,'data' => '', 'msg' => '请登录']);
        }
        $id = input('id');
        $collect = Db::name('collect')->where(['id' => $id])->find();
        if($collect){
            Db::name('collect')->where('id',$id)->delete();
            return json(['code' =>1 ,'data' => '', 'msg' => '取消收藏成功']);
        } else{
            return json(['code' =>0 ,'data' => '', 'msg' => '无数据']);
        }
        
    }
    
    
    public function upuser(){
        $uid = Session::get('userid');
        $data = input('data');
        $birthday = strtotime($data['birthday']);
        Db::name('user')->where('uid', $uid)->update(['username' => $data['username'], 'sex' => $data['sex'], 'birthday' => $birthday, 'signature' => $data['signature'] ]);
        return json(['code' => 1, 'data' => '', 'msg' => '成功'] );
        
    }
    
     public function uppassword(){
        $uid = Session::get('userid');
        $data = input('data');
        if($data['password'] != $data['uppassword']){
            return json(['code' => 0, 'data' => '', 'msg' => '新密码两次输入不一致'] );
        }
        
        $newpas = Db::name('user')->where('uid', $uid)->find();
        $inpass = $newpas['password'];
        $uppass = md5(md5($data['password']).$newpas['salt']);
        if($uppass != $inpass){
            return json(['code' => 0, 'data' => $uppass, 'datas' => $inpass, 'msg' => '原始密码不一致，请重新输入'] );
        }
        
        $salt = $this->salt();
        $pass = md5(md5($data['password']).$salt);
        
        Db::name('user')->where('uid', $uid)->update(['password' => $pass, 'salt' => $salt ]);
        return json(['code' => 1, 'data' => '', 'msg' => '成功'] );
        
    }
    
    public function upavatar(){
         $file = request()->file('file');
        $savename = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $file);
        $uid = Session::get('userid');
        $avatar = '/storage/'.$savename;
        Db::name('user')->where('uid', $uid)->update(['avatar' => $avatar, 'avatartime' => time() ]);
        return json(["code" => '1', "data" =>"", "msg" => "成功", "image" => $avatar ]);
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
     public function salt(){
        $string = '';
        for ($i=1;$i<13;$i++) {    
             $randstr = chr(rand(65,90));    //指定为字母
            $string .= $randstr; 
        }
        return $string;
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
       