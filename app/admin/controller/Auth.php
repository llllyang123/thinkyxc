<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;


class Auth
{
    
    public function index(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
        ]);
        return View::fetch('index');
       
   }
   
   public function authadd(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       $authr = Db::name('auth_rule')->select(); 
        $auth = $this->getTree($authr);
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'auth' =>$auth,
        ]);
        return View::fetch('authadd');
       
   }
   
    public function authedit(){
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $id = input('id');
        $data = Db::name('auth_rule')->where('id',$id)->find(); 
        $authr = Db::name('auth_rule')->select(); 
        $topauth = Db::name('auth_rule')->where('id',$data['aid'])->find(); 
        $auth = $this->getTree($authr);
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'data' =>  $data,
            'auth' =>  $auth,
            'topauth' => $topauth,
        ]);
        return View::fetch('authedit');
       
   }
   
   public function authdel(){
       $data =input('data');
       $id = input('id');
       $authid = Db::name('auth_rule')->where('id',$id)->find();
       
       if($authid['aid']){
                $ok = Db::name('auth_rule')->where('id',$id)->delete();
           } else{
               $ok = Db::name('auth_rule')->where('id',$id)->delete();
           }
       
       if($ok){
            
            return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '删除失败，请重试']);
            
        }
       
   }
   
   public function authadds(){
       $data = input('data');
       $app = $data['app'];
       $type = $data['type'];
       $name = $data['name'];
       $title = $data['title'];
       $aid = $data['aid'];
       $status = $data['status'];
       if(empty($status)){
           $status = 0;
       } else if(!empty($status)&&$status == 'on'){
           $status = 1;
       }
       $data = [ 'aid' => $aid, 'app' => $app, 'type' => $type, 'name' => $name, 'title' => $title, 'status' => $status ];
        $ok = Db::name('auth_rule')->insert($data);
        if($ok){
            
            return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '添加失败，请重试']);
            
        }
   }
   
   public function authup(){
       $data = input('data');
       $app = $data['app'];
       $type = $data['type'];
       $name = $data['name'];
       $title = $data['title'];
       $aid = $data['aid'];
       $status = $data['status'];
       $id = input('id');
       if(empty($status)){
           $status = 0;
       } else if(!empty($status)&&$status == 'on'){
           $status = 1;
       }
       
       $data = [ 'aid' => $aid, 'app' => $app, 'type' => $type, 'name' => $name, 'title' => $title, 'status' => $status ];
        $ok = Db::name('auth_rule')->where('id', $id)->update($data);
        if($ok){
            
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '修改失败或没有修改内容，请重试']);
            
        }
   }
    
    
    public function auth(){
        $auth = Db::name('auth_rule')->order('id', 'asc')->select(); 
        // $ok = $this->getTree($auth);
        // $ok = $this->gettr($auth);
        $ok = $this->getTree1($auth);
        return json($ok);
        // return json(['code' => 1, 'data' =>$ok, 'msg' =>'成功' ]);
        
    }
    
    function getTree1($data, $parent_id = 0)
{
    $tree = array();
    foreach ($data as $k => $v) {
        if ($v["aid"] == $parent_id) {
            unset($data[$k]);
            if (!empty($data)) {
                $children = $this->getTree1($data, $v["id"]);
                if (!empty($children)) {
                    $v["children"] = $children;
                }
            }
            $tree[] = $v;
        }
    }
    return $tree;
}
    
    
     function getTree($array, $pid =0, $level = 0){

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['aid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['id'], $level+1);

            }
        }
        return $list;
    }
    
    
    
    
}