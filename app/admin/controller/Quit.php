<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Event;
use think\captcha\facade\Captcha;
use think\facade\Config;


class Quit
{
    public function index()
    {
        
        Session::delete('userid');
        Session::delete('admin.id');
        Session::delete('backurl');
        echo("退出成功");
        return redirect('/index');
        // return json([ 'code' =>1, 'data' =>'', 'msg' => '退出成功' ]);
        
    }
    
    
    
}