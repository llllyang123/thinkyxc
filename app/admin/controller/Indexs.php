<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Db;
use think\facade\Session;
use think\facade\Config;


class Indexs
{
    public function index()
    {
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $thread = Db::name('thread')->where('status', 1)->count();
        $threadnew = Db::name('thread')->where('status', 1)->whereDay('create_date')->count();
        $threads = Db::name('thread')->where('status', 2)->count();
        $threadnews = Db::name('thread')->where('status', 2)->whereDay('create_date')->count();
        
        $usernum = Db::name('user')->where('status', 1)->count();
        $usersum = Db::name('user')->count();
        $usernew = Db::name('user')->where('status', 1)->whereDay('create_date')->count();
        
        $fangwena = Db::name('thread')->where('status','>',1)->whereDay('create_date')->sum('views');
        $fangwenb = Db::name('thread')->where('status','>',1)->whereDay('create_dateup')->sum('views');
        $fangwen = $fangwena+$fangwenb;
        $fangwens = Db::name('thread')->where('status','>',0)->sum('views');  
        
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'fangwen' => number_format($fangwen),
            'fangwens' => number_format($fangwens),
            'thread' => $thread,
            'threadnew' => $threadnew,
            'threads' => $threads,
            'threadnews' => $threadnews,
            'usernum' => $usernum,
            'usernew' => $usernew,
            'usersum' => $usersum,
            
        ]);
        return View::fetch('index');
    }
    
    
}
