<?php
declare (strict_types = 1);

namespace app\index\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use phpmailer\PHPMailer;
use phpmailer\SMTP;
use think\facade\Route;
use think\facade\Config;


class Thread
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
        $fid = input('fid');
        if(empty($fid)){
            $fid = 1;
        }
        $page = intval(input('page'));
        $limit = intval(input('limit'));
        if(empty($page)){
            $page = 1;
        }
        if(empty($limit)){
            $limit = 10;
        }
        $data = Db::name('thread')->where(['status'=> 1, 'type' => 1, 'fid' => $fid, 'delete_time' => 0 ])->page($page,$limit)->cache(Config::get('cache.expire'))->select();
        $count = Db::name('thread')->where(['status'=> 1, 'type' => 1, 'fid' => $fid, 'delete_time' => 0 ])->cache(Config::get('cache.expire'))->count();
        $newest = Db::name('thread')->where(['status'=> 1, 'type' => 1])->order('create_dateup', 'desc')->page($page,$limit)->cache('newest',Config::get('cache.list_expire'))->select();
        $hot = Db::name('thread')->where(['status'=> 1, 'type' => 1, 'delete_time' => 0 ])->order(['views'=>'desc', 'favorites'=>'desc', 'likes'=>'desc' ])->page($page,$limit)->cache('hot',Config::get('cache.list_expire'))->select();
          $uid = Session::get('userid');
        if(empty($uid)){
            $user = '';
            $avatar = '';
            $username = '';
        } else{
            $user = Db::name('user')->where(['uid' => $uid, 'status' =>1 ])->find();
            $avatar = $user['avatar'];
            $username = $user['username'];
        }
        $forumname = Db::name('forum')->where(['status' => 1, 'fid' => $fid])->cache('forum',Config::get('cache.expire'))->find();
        $forum = Db::name('forum')->where('status',1)->select(); 
        $ok = $this->getTree1($forum);
        $friendlink = Db::name('friendlink')->where('status', 1)->order('weight')->cache('friendlink',Config::get('cache.expire'))->select();
        $this->G('end');
        $overtime = $this->G('begin','end',3);
         View::assign([
            'name'  => 'ThinkYXC',
            'email' => '673011635@qq.com',
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
            'data' => $data,
            'count' => $count,
            'newest' =>$newest,
            'hot' =>$hot,
            'avatar' => $avatar,
            'username' => $username,
            'uid' =>$uid,
            'fid' => $fid,
            'forum' =>$ok,
            'forumname' => $forumname,
            'friendlink' =>$friendlink,
            'site_logon' => json_decode($site_logon,true),
            'overtime' => $overtime,
        ]);
        return View::fetch('index');
        
        
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