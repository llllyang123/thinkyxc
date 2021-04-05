<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Event;
use think\facade\Cache;
use think\facade\Config;

class Index
{
    public function index()
    {
        
        
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        //获取当前用户
        // Session::set('admin.id', '2');
        $admin_id = Session::get('admin.id');
        if($admin_id){
            $user = Db::name('user')->where('uid', $admin_id)->field('uid,username,avatar')->find();
        } else{
            // $user =  '';
            // return redirect((string) url('/index/login'));
            return;
        }
        if($admin_id['status'] ==2 ){
                 return json([ 'code' =>0, 'data' =>'', 'msg' => '抱歉，账号因违规操作被列入黑名单' ]);
            } else if($admin_id['status'] ==3 ){
                 return json([ 'code' =>0, 'data' =>'', 'msg' => '账号异常，请联系管理员' ]);
            }
        
        $site_info = Db::name('setting')->where('set_name', 'site_info')->cache(true)->value('set_value');
        $site_seo = Db::name('setting')->where('set_name', 'site_seo')->cache(true)->value('set_value');
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'fangwen' => number_format(169856420),
            'admin_id' => $admin_id,
            'user' =>$user,
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
        ]);
        
        return View::fetch('index');
    }
    
    public function clear(){
        Cache::clear(); 
        
        return json(['code' => 1, 'data' => '', 'msg' => '成功']);
        
    }
    
    
    public function ceshi(){
        // event('UserLogin');
        echo('ss');
        // event('UserLogin');
        // $value= 'ceshi';
        // // 直接使用事件类触发
        // // event('app\event\UserLogin');
        event('Ceshi');
        // event(root_path()."plugin/".$value."/controller/Admin");
    }
    
}
