<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\facade\Request;
use think\facade\Cache;
use think\facade\Config;

class Setting
{
    public function index()
    {
        
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->find();
        $templatepc = Db::name('template')->where(['templatestatus' => 1, 'status' =>1])->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $site_info = Db::name('setting')->where('set_name', 'site_info')->value('set_value');
        $site_seo = Db::name('setting')->where('set_name', 'site_seo')->value('set_value');
        $site_cdn = Db::name('setting')->where('set_name', 'site_cdn')->value('set_value');
        $site_logon = Db::name('setting')->where('set_name', 'site_logon')->value('set_value');
        $this->traverseDir('template');
        // $data = json_decode($data,true);
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'site_info' => json_decode($site_info,true),
            'site_seo' => json_decode($site_seo,true),
            'site_cdn' => json_decode($site_cdn,true),
            'site_logon' => json_decode($site_logon,true),
            'viewpath' => $template['template'],
            'viewpathpc' => $templatepc['template'],
        ]);
        return View::fetch('index');
    }
    
    public function edit(){
        $data = input('data');
        $set_name = input('set_name');
            if(!empty($data['template'])){
                Db::name('template')->where(['templatestatus' => 2, 'status' => 1])->update(['template' => $data['templateadmin']]);
                Db::name('template')->where(['templatestatus' => 1, 'status' => 1])->update(['template' => $data['template']]);
            } 
        $data = json_encode($data);
            
        $ok = Db::name('setting')->where('set_name', $set_name)->cache('setting',Config::get('cache.expire'))->update(['set_value' => $data]);
        if($ok){
            Cache::delete('site_info'); 
            Cache::delete('site_seo');
            Cache::delete('site_logon');
            Cache::delete('template');
            Cache::delete('admin_template');
            return json(['code' => 1, 'data' => '', 'msg' => '成功' ]);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '失败，请重试' ]);
        }
        
        
    }
    
    public function traverseDir($dir,$array = array(),$arrayadmin = array() ){
        if($dir_handle = @opendir($dir)){
            while($filename = readdir($dir_handle)){
                if($filename != "." && $filename != ".."){
                $subFile = $dir.DIRECTORY_SEPARATOR.$filename; //要将bai源目录du及子文zhi件相连
                    if(is_dir($subFile)){ //若子文件是个目录
                        if(preg_match('/^admin/',$filename)){
                            //返回0
                            $arrayadmin[] = $filename;
                        } else{
                            $array[] = $filename;
                        }
                        
                        // echo $filename.'<br>'; //输出该目录名称
                        // traverseDir($subFile); //递归找出下级目录名称
                    }
                }
            }
            closedir($dir_handle);
        }
        
        View::assign([
            'template'  => $array,
            'templateadmin'  => $arrayadmin,
        ]);
        // print_r($array);
        // return $array;
    }
    
}
