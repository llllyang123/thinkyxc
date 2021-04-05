<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Cache;
use think\facade\Config;

class Link
{
    public function index()
    {
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
        ]);
        return View::fetch('index');
    }
    
    
        public function lists(){
        $page = intval(input('page'));
        $limit = intval(input('limit'));
        $data = Db::name('friendlink')->where('status','>', 0)->page($page,$limit)->order('weight')->select();
        $count = Db::name('friendlink')->where('status','>', 0)->count();
        if($data){
            return json(['code' => 1, 'data' => $data, 'msg' => '','count' => $count]);
            
        } else{
            
           return json(['code' => 0, 'data' => '', 'msg' => '暂无数据','count' => 0]); 
        }
        
        
    }
    
        public function add(){
            $data = input('data');
            $name = $data["name"];
            $url = $data["url"];
            $target = $data['target'];
            $thumbnail = input('thumbnail');
            $create_date = time();
            $datas = ['name' => $name, 'url' => $url, 'thumbnail' => $thumbnail, 'create_date' => $create_date, 'target' => $target ];
            $ok = Db::name('friendlink')->insertGetId($datas);
            if($ok){
                Cache::delete('friendlink'); 
                return json(['code' => 1, 'data' => '', 'msg' => '成功' ]);
            } else{
                return json(['code' => 0, 'data' => '', 'msg' => '失败，请重试' ]);
            }
            
        }
    
       public function edit(){
        $data = input('data');
        $linkid = input('linkid');
        $updata = ['name' => $data["name"], 'url' => $data["url"], 'thumbnail' => $data["thumbnail"], 'status' => $data["status"], 'weight' => $data["weight"]  ];
        $ok = Db::name('friendlink')->where('linkid', $linkid)->cache('friendlink',Config::get('cache.expire'))->update($updata);
        if($ok){
            Cache::delete('friendlink'); 
            return json(['code' => 1, 'data' => '', 'msg' => '成功' ]);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '失败，请重试' ]);
        }
        
        
    }
    
        //修改状态
    public function status(){
        $status = input('status');
        $linkid = input('linkid');
        if($status == 1){
            $status = 2;
        } else if($status == 2){
            $status = 1;
        }
        $ok = Db::name('friendlink')->where('linkid', $linkid)->cache('friendlink',Config::get('cache.expire'))->update(['status' => $status]);
        if($ok){
            Cache::delete('friendlink'); 
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '修改失败，请重试']);
            
        }
    }
    
     //删除友链
    public function del($linkid){
        $dela = Db::name('friendlink')->where('linkid',$linkid)->delete();
        if($dela){
            Cache::delete('friendlink'); 
            return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '删除失败，请重试']);
        }
        
    }
    
    
    //接收缩略图
    public function image(){
        $file = request()->file('file');
        $savename = \think\facade\Filesystem::disk('public')->putFile( 'topic', $file);
        Db::name('file')->insert(['file_route' => '/storage/'.$savename, 'creat_time' => time()]);
        return json(["code" => '1', "data" =>"", "msg" => "成功", "image" =>'/storage/'.$savename]);
    }
    
     //接收内容上传文件
    public function files(){
        $file = request()->file('file');
        $savename = \think\facade\Filesystem::disk('public')->putFile( 'topic', $file);
        Db::name('file')->insert(['file_route' => '/storage/'.$savename, 'creat_time' => time()]);
        return json(["location" =>'/storage/'.$savename]);
    }
    
    
}
