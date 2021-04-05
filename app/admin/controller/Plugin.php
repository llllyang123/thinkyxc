<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;

class Plugin
{
    
    
    
    public function index()
    {
        $forum = Db::name('forum')->where('status',1)->order(['fup','weight'=>'desc'])->select(); 
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'fangwen' => number_format(169856420),
            
        ]);
        return View::fetch('index');
        
    }
    
    
    public function pluginlista(){
        $filename = $this->traverseDir(root_path()."addons");
        $data = Db::name('plugin')->where('status',1)->select()->toArray();
        $array = array();
            foreach($filename as $key => $value){
                $name = get_addons_info($value);
                if(!in_array($value,$data)){
                    $array[] = $name;
                }
                
            }
            return json(['code' => 1, 'data' => $array, 'msg' => '获取成功']);
    }
    
    public function pluginlist(){
        $filename = $this->traverseDir(root_path()."addons");
        $array = array();
            foreach($filename as $key => $value){
                 $file_pointer = root_path()."addons/".$value."/conf.json";
                $jsons = file_get_contents($file_pointer);
                $array[] = json_decode($jsons,true);
                // $array[] = json_decode($this->trimall($jsons),true);
            }
        // 	$file_pointer = fopen(root_path()."plugin/ceshi/conf.json","r+"); 
        // 	$file_pointer = root_path()."plugin/ceshi/conf.json";
        	
        // 	$jsons = file_get_contents($file_pointer);
            // var_dump($filename);
            // print_r($array);
            // return(json($array));
            return json(['code' => 1, 'data' => $array, 'msg' => '获取成功']);
    }
        
     public function traverseDir($dir,$array = array(),$arrayadmin = array() ){
        if($dir_handle = @opendir($dir)){
            while($filename = readdir($dir_handle)){
                if($filename != "." && $filename != ".."){
                $subFile = $dir.DIRECTORY_SEPARATOR.$filename; //要将bai源目录du及子文zhi件相连
                    if(is_dir($subFile)){ //若子文件是个目录
                        $array[] = $filename;
                    }
                }
            }
            closedir($dir_handle);
        }
        
        // var_dump($array);
        return($array);
    }  
    
    
    public function install(){
        $data = input('data');
        if(empty($data)){
            return json(['code' => 0, 'data' => '', 'msg' => '内容不能为空']);
        }
        $ok = Db::name('plugin')->where('name',$data['name'])->find();
        $datas = ['has_admin' => $data['hasAdmin'], 'create_time' => time(), 'name' => $data['name'], 'title' => $data['title'], 'demo_url' => $data['demo_url'], 'author' => $data['author'], 'author_url' => $data['author_url'], 'version' => $data['version'], 'description' => $data['description'], "status" => 1  ];
        if($ok){
            Db::name('plugin')->where('name',$data['name'])->update($datas);
            // return json(['code' => 0, 'data' => '', 'msg' => '该插件已存在，无法重复安装']);
        } else{
            Db::name('plugin')->insert($datas);
        }
        
        $data['status'] = 1;
        $data['hasAdmin'] = $data['hasAdmin'];
        $file = root_path()."addons/".$data['name']."/conf.json";
        
        file_put_contents($file,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        
        return json(['code' => 1, 'data' => '', 'msg' => '安装成功']);
        
    }
    
    public function uninstall(){
        $data = input('data');
        if(empty($data)){
            return json(['code' => 0, 'data' => '', 'msg' => '内容不能为空']);
        }
        $ok = Db::name('plugin')->where('name',$data['name'])->find();
        if($ok){
            Db::name('plugin')->where('name',$data['name'])->update(['status' => 0]);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '该插件不存在，无法卸载']);
        }
        
        $data['status'] = 0;
        $file = root_path()."addons/".$data['name']."/conf.json";
        
        file_put_contents($file,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        
        return json(['code' => 1, 'data' => '', 'msg' => '插件已卸载']);
        
    }
    
    public function updata(){
        $data = input('data');
        if(empty($data)){
            return json(['code' => 0, 'data' => '', 'msg' => '内容不能为空']);
        }
        $ok = Db::name('plugin')->where('name',$data['name'])->find();
        $datas = ['has_admin' => $data['hasAdmin'], 'create_time' => time(), 'name' => $data['name'], 'title' => $data['title'], 'demo_url' => $data['demo_url'], 'author' => $data['author'], 'author_url' => $data['author_url'], 'version' => $data['version'], 'description' => $data['description'], "status" => $data['status']  ];
        
        Db::name('plugin')->where('name',$data['name'])->update($datas);
        
        
        return json(['code' => 1, 'data' => '', 'msg' => '更新成功']);
        
    }
    
    public function admin(){
        $data = input('data');
        if(empty($data)){
            return json(['code' => 0, 'data' => '', 'msg' => '内容不能为空']);
        }
        $file_pointer = root_path()."addons/".$data."/view/admin/index.html";
        $plugin = Db::name('plugin')->where('name',$data)->find();
        $config = $plugin['config'];
        if(!empty($config)){
            $config = json_decode($config,true);
        }
        View::assign([
            'pluginname'  => $data,
            'plugin' => $plugin,
            'config' => $config,
        ]);
        return View::fetch($file_pointer);
        
    }
    
    
    public function admin_updata(){
        $data = input('data');
        $pluginname = input('pluginname');
        if(empty($data)||empty($pluginname)){
            return json(['code' => 0, 'data' => '', 'msg' => '内容不能为空']);
        }
        $config = json_encode($data);
        Db::name('plugin')->where('name',$pluginname)->update(['config' => $config]);
        return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
    }
    
    
    // public function installa(){
    //     $data = input('data');
    //     $status = input('status');
    //     if(!$data){
    //         return json(['code' => 0, 'data' => '', 'msg' => '内容不能为空']);
    //     }
    //     $addons = get_addons_info($data['name']);
    //     if($addons['status'] ==1 ){
    //         return json(['code' => 0, 'data' => '', 'msg' => '插件已安装']);
    //     }
    //     hook($data['name'].'hook', ['status'=>$status]);
    //     // return json(['code' => 0, 'data' => '', 'msg' => '插件已安装']);
        
    // }
    
    // public function uninstall(){
    //     $data = input('data');
    //     $status = input('status');
    //     if(!$data){
    //         return json(['code' => 0, 'data' => '', 'msg' => '内容不能为空']);
    //     }
    //     $addons = get_addons_info($data['name']);
       
    //     hook($data['name'].'hook', ['status'=>$status]);
    //     return json(['code' => 0, 'data' => '', 'msg' => '插件已卸载']);
        
    // }
 
    
    
 
}       