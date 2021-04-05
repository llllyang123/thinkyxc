<?php
declare (strict_types = 1);

namespace app\index\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use phpmailer\PHPMailer;
use phpmailer\SMTP;
use think\facade\Config;


class Post
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
        // $pid = input('pid');
        $tid = input('tid');
        Db::name('thread')->where('tid', $tid)->inc('views')->cache(Config::get('cache.expire'))->update();
        $dataa = Db::name('thread')->where('tid', $tid)->where('delete_time', 0)->cache(Config::get('cache.expire'))->find();
        if(empty($dataa)){
            echo '该文章走丢了……';
        }
        $datab = Db::name('post')->where('tid', $dataa['tid'])->cache('post_tid_'.$dataa['tid'],Config::get('cache.expire'))->find();
        $datac = Db::name('user')->where(['uid' => $datab['uid']])->cache(Config::get('cache.expire'))->field('uid,username,avatar')->find();
        if(empty($datac)){
           $datac = Db::name('user')->where(['gid' => 1])->field('uid,username,avatar')->cache(Config::get('cache.expire'))->find();
        }
        $data = array_merge($dataa,$datab,$datac);
        
        $newest = Db::name('thread')->where(['status'=> 1, 'type' => 1, 'delete_time' => 0])->order('create_dateup', 'desc')->limit(10)->cache('newest',Config::get('cache.list_expire'))->select();
        $hot = Db::name('thread')->where(['status'=> 1, 'type' => 1, 'delete_time' => 0])->order(['views'=>'desc', 'favorites'=>'desc', 'likes'=>'desc' ])->limit(10)->cache('hot',Config::get('cache.list_expire'))->select();
         $uid = Session::get('userid');
        if(empty($uid)){
            $user = '';
            $avatar = '';
            $username = '';
            $uid = 0;
            $collect = '';
            $likes = '';
        } else{
            $user = Db::name('user')->where(['uid' => $uid, 'status' =>1 ])->find();
            $avatar = $user['avatar'];
            $username = $user['username'];
            $collect = Db::name('collect')->where(['uid' => $uid, 'tid' =>$tid ])->cache(Config::get('cache.expire'))->find();
            $likes = Db::name('likes')->where(['uid' => $uid, 'tid' =>$tid ])->cache(Config::get('cache.expire'))->find();
        }
        $forum = Db::name('forum')->where('status',1)->cache(Config::get('cache.expire'))->select(); 
        $ok = $this->getTree1($forum);
        $friendlink = Db::name('friendlink')->where(['status'=> 1])->order('weight')->cache('friendlink',Config::get('cache.expire'))->select();
        $this->G('end');
        $overtime = $this->G('begin','end',3);
         View::assign([
            'name'  => 'ThinkYXC',
            'email' => '673011635@qq.com',
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
            'data'   => $data,
            'content' => html_entity_decode($datab['content']),
            'newest' =>$newest,
            'hot' =>$hot,
            'avatar' => $avatar,
            'username' => $username,
            'uid' =>$uid,
            'forum' =>$ok,
            'friendlink' =>$friendlink,
            'site_logon' => json_decode($site_logon,true),
            'collect'  => $collect,
            'likes' => $likes,
            'overtime' => $overtime,
        ]);
        if($data['type'] == 2){
            return View::fetch('page');
        } else{
            return View::fetch('index');
        }
        
        
        
    }
    
    public function zan(){
        $pid = input('pid');
        $tid = input('tid');
        // $uid = input('uid');
        $uid = Session::get('userid');
        $create_date = time();
        if(empty($uid)){
             return json(['code' =>0 ,'data' => '', 'msg' => '登录后才可以点赞哦']);
        }
        $ok = Db::name('likes')->where(['tid' => $tid, 'uid' => $uid])->whereDay('create_date')->find();
        if($ok){
            return json(['code' =>0 ,'data' => '', 'msg' => '亲，你今天已经点过赞了哦']);
        }
        $data = [ 'tid' => $tid, 'create_date' => $create_date, 'uid' => $uid ];
        $link = Db::name('likes')->insert($data);
        if($link){
            Db::name('thread')->where('tid', $tid)->where('delete_time', 0)->inc('likes')->cache(Config::get('cache.expire'))->update();
            return json(['code' =>1 ,'data' => '', 'msg' => '点赞成功']);
        } else{
            
            return json(['code' =>0 ,'data' => '', 'msg' => '点赞失败，请联系客服']);
            
        }
        
        
    }
    
    //收藏文章
    public function collect(){
        $pid = input('pid');
        $tid = input('tid');
        $uid = Session::get('userid');
        if(empty($uid)){
             return json(['code' =>0 ,'data' => '', 'msg' => '登录后才可以收藏哦']);
        }
        $create_date = time();
        $collect = Db::name('collect')->where(['uid' => $uid, 'tid' => $tid])->find();
        if($collect){
            return json(['code' =>0 ,'data' => '', 'msg' => '已经收藏过了哦']);
        } else{
            $data = [ 'tid' => $tid, 'create_date' => $create_date, 'uid' => $uid ];
              Db::name('collect')->insert($data);
              Db::name('thread')->where('tid', $tid)->where('delete_time', 0)->inc('favorites')->cache(Config::get('cache.expire'))->update();
            return json(['code' =>1 ,'data' => '', 'msg' => '已收藏']);
        }
        
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