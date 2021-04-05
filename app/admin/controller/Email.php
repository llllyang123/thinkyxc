<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Config;


class Email
{
    public function index()
    {
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $site_email = Db::name('setting')->where('set_name', 'site_email')->value('set_value');
        $site_email_code = Db::name('setting')->where('set_name', 'site_email_code')->value('set_value');
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'fangwen' => number_format(169856420),
            'code' => '{$code}',
            'site_email' => json_decode($site_email,true),
            'site_email_code' => json_decode($site_email_code,true),
        ]);
        return View::fetch('index');
    }
    
    
    public function smtp(){
        $data = input('data');
        $set_name = input('set_name');
        $data = json_encode($data);
        $ok = Db::name('setting')->where('set_name', $set_name)->update(['set_value' => $data]);
        if($ok){
            return json(['code' => 1, 'data' => '', 'msg' => '成功' ]);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '失败，请重试' ]);
        }
        
    }
    
    
}
