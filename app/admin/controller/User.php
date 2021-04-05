<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;

class User
{
    
     public function index(){
        //  $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache(true)->find();
        // View::config(['view_path' => 'template/'.$template['template'].'/']);
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
        ]);
        return View::fetch('index');
       
   }
   
   public function useropen(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
        ]);
        return View::fetch('useropen');
       
   }
   
   
   public function lists(){
       $page = intval(input('page'));
        $limit = intval(input('limit'));
       $data = Db::name('user')->withoutField(['password','password_sms','salt',''])->page($page,$limit)->select();
       $count = Db::name('user')->count();
       if($data){
           
            return json(['code' => 1, 'data' => $data, 'msg' => '','count' => $count]);
            
        } else{
            
           return json(['code' => 0, 'data' => '', 'msg' => '暂无数据','count' => 0]); 
        }
       
       
   }
   
   public function useropenlist(){
       $page = intval(input('page'));
        $limit = intval(input('limit'));
       $data = Db::name('user_open_plat')->where('platid','>',0)->page($page,$limit)->select();
       $count = Db::name('user_open_plat')->where('platid','>',0)->count();
       foreach($data as $k=>$v){
             $a = Db::name('user')->where('uid',$v['uid'])->withoutField(['password','password_sms','salt',''])->find();
             $v = array_merge($v,$a);
             $data[$k] = $v;
       }
       if($data){
           
            return json(['code' => 1, 'data' => $data, 'msg' => '','count' => $count]);
            
        } else{
            
           return json(['code' => 0, 'data' => '', 'msg' => '暂无数据','count' => 0]); 
        }
       
       
   }
   
   
   public function adduser(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       $group = Db::name('group')->whereNotBetween('gid','1,5')->select();
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'group' => $group,
        ]);
        return View::fetch('adduser');
       
   }
    
    
    
     //修改状态
    public function status(){
        $status = input('status');
        $uid = input('uid');
        if($status == 1){
            $status = 2;
        } else if($status == 2){
            $status = 1;
        }
        $ok = Db::name('user')->where('uid', $uid)->update(['status' => $status]);
        if($ok){
            
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '修改失败，请重试']);
            
        }
    }
    
    
    public function serchuser(){
        $serchtitle = input('data');
        $userov = Db::name('user')->where('username', $serchtitle)->select();
        return json(['code' => 1, 'data' => $userov, 'msg' => '']);
    }
    
    public function del(){
        $uid = input('uid');
        $user = Db::name('user')->where('uid',$uid)-find();
        if($user['gid'] == 1){
            return json(['code' => 0, 'data' => '', 'msg' => '删除失败，该用户为管理员']);
        }
        $ok = Db::name('user')->where('uid',$uid)->delete();
        Db::name('user_open_plat')->where('uid',$uid)->delete();
         if($ok){
            
            return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '删除失败，请重试']);
            
        }
    }
    
   
    
    
}