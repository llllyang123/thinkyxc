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
    
    
    public function datalist(){
        $page = intval(input('page'));
        $limit = intval(input('limit'));
        $data = Db::name('plugin')->where('status',1)->page($page,$limit)->select();
        $count = Db::name('plugin')->where('status',1)->count();
        
        return json(['code' => 1, 'data' => $data, 'msg' => '获取成功','count' => $count]);
        
        
    }
    
    public function pluginlist(){
        $filename = $this->traverseDir(root_path()."plugin");
        $array = array();
            foreach($filename as $key => $value){
                 $file_pointer = root_path()."plugin/".$value."/conf.json";
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
        if(!$data){
            return json(['code' => 0, 'data' => '', 'msg' => '内容不能为空']);
        }
        $ok = Db::name('plugin')->where('name',$data['name'])->find();
        if($ok){
            return json(['code' => 0, 'data' => '', 'msg' => '该插件已存在，无法重复安装']);
        }
        $datas = ['has_admin' => $data['hasAdmin'], 'create_time' => time(), 'name' => $data['name'], 'title' => $data['title'], 'demo_url' => $data['demo_url'], 'author' => $data['author'], 'author_url' => $data['author_url'], 'version' => $data['version'], 'description' => $data['description'], "status" => 1, "config" => []  ];
        Db::name('plugin')->insert($datas);
        
        $data['status'] = 1;
        $data['hasAdmin'] = intval($data['hasAdmin']);
        $file = root_path()."plugin/".$data['name']."/conf.json";
        
        file_put_contents($file,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        
        return json(['code' => 1, 'data' => '', 'msg' => '安装成功']);
        
    }
    
            //删除空格 和 回车
        function trimall($str){
            $oldchar=array("","　","\t","\n","\r","    "," ");
            $newchar=array("","","","","","","");
            return str_replace($oldchar,$newchar,$str);
        }
    
}