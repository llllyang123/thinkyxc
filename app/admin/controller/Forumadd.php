<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Cache;
use think\facade\Config;

class Forumadd
{
    
    public function index()
    {
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $fid = input('fid');
        // $fup = input('fup');
         $pagedata = Db::name('thread')->where(['type' => 2, 'status' =>1])->select();
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            // 'fup' =>$fup,
            'fid' =>$fid,
            'pagedata' =>$pagedata,
        ]);
        return View::fetch('index');
    }
    
    
    public function edit()
    {
        $fid = input('fid');
        $data = Db::name('forum')->where('fid', $fid)->find();
        $thumbnail = $data['thumbnail'];
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        if(!$thumbnail){
            $thumbnail = '';
        }
        $threads = Db::name('thread')->where('tid', $data['threads'])->find();
        $pagedata = Db::name('thread')->where(['type' => 2, 'status' =>1])->select();
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'data' =>$data,
            'fid' =>$fid,
            'thumbnail' =>$thumbnail,
            'pagedata' =>$pagedata,
            'threads' =>$threads,
        ]);
        return View::fetch('edit');
        
        
       
    }
    
    public function editup(){
        $data = input('data');
        $fid = input('fid');
        $thumbnail = input('image');
        $name = $data['name'];
        $brief = $data['brief'];
        $announcement = $data['announcement'];
        $seo_title = $data['seo_title'];
        $seo_keywords = $data['seo_keywords'];
        $seo_description = $data['seo_description'];
        $weight = $data['weight'];
        $url = $data['url'];
        $target = $data['target'];
        $status = input('status');
        $type = $data['type'];
        if(!$thumbnail){
            $thumbnail = '';
        }
        if($status == 'on'){
            $status = 1;
        } else if($status == 'off'){
            $status = 2;
        }
        $ok = Db::name('forum')->where('fid', $fid)->cache('forum',Config::get('cache.expire'))->update(['thumbnail' => $thumbnail, 'name' => $name, 'brief' => $brief, 'announcement' => $announcement, 'seo_title' => $seo_title, 'seo_keywords' => $seo_keywords, 'seo_description' => $seo_description, 'weight' => $weight, 'status' => $status, 'url' => $url, 'target' => $target, 'threads' => $type ]);
        if($ok){
            return json(["code" => '1', "data" =>"", "msg" => "修改成功"]);
        } else{
            return json(["code" => '0', "data" =>"", "msg" => "修改失败，可能您并没有做任何修改"]);
            
        }
        
    }
    
    public function add(){
        $data = input('data');
        $name = $data['name'];
        $image = input('image');
        $fid = input('fid');
        $url = $data['url'];
        $target = $data['target'];
        // $fup = input('fup');
        $type = $data['type'];
        $datas = ['fup' => $fid, 'name' => $name, 'url' => $url, 'target' => $target, 'threads' => $type];
        $ok = Db::name('forum')->insert($datas);
        if($ok){
            Cache::delete('forum'); 
            return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '添加失败，请重试']);
            
        }
        
    }
    
     //修改状态
    public function status(){
        $status = input('status');
        $fid = input('fid');
        if($status == 1){
            $status = 2;
        } else if($status == 2){
            $status = 1;
        }
        $ok = Db::name('forum')->where('fid', $fid)->cache('forum',Config::get('cache.expire'))->update(['status' => $status]);
        if($ok){
            
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '修改失败，请重试']);
            
        }
    }
    
        //删除
    public function del($fid){
        $ok = Db::name('forum')->where('fid',$fid)->cache('forum',Config::get('cache.expire'))->delete();
        //  Db::name('forum')->where('fup',$fid)->cache('forum',Config::get('cache.expire'))->delete();
         
         // 软删除数据 使用delete_time字段标记删除
        // Db::name('thread')
        // 	->where('fid', $fid)
        // 	->useSoftDelete('delete_time',time())
        //     ->delete();
        if($ok){
            // Cache::delete('forum');
            return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '删除失败，请重试']);
        }
        
    }
    
    
}