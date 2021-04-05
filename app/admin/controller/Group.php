<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Db;
use think\facade\Session;
use think\facade\Config;

class Group
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
   
   //管理列表
   public function admin(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
        ]);
        return View::fetch('admin');
       
   }
   
   //增加管理页面
   public function addadmin(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       $group = Db::name('group')->select();
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'group' =>$group,
        ]);
        return View::fetch('addadmin');
       
   }
   
   //增加管理
   public function addadmins(){
       $data = input('data');
       $email = $data['email'];
       $username = $data['username'];
        $password = $data['password'];
        $group  = $data['group'];
        $salt = $this->salt();
        $pass = md5(md5($password).$salt);
        $datas = ['email' => $email, 'username' => $username, 'password' => $pass, 'gid' => $group, 'salt' => $salt];
        $ok =Db::name('user')->insert($datas);
        if($ok){
               return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
           } else{
               return json(['code' => 0, 'data' => '', 'msg' => '添加失败，请重试']);
           }
       
   }
   
   //编辑管理员页面
   public function adminedit(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       $uid = input('uid');
       $adminid = Session::get('admin.id');
       $users = Db::name('user')->where('uid',$adminid)->find();
       if($adminid['gid'] == 1){
           $group = Db::name('group')->select();
       } else{
           $group = Db::name('group')->where('gid','<>',1)->select();
       }
       $user = Db::name('user')->where('uid',$uid)->find();
       $groupname = Db::name('group')->where('gid',$user['gid'])->find();
       $user = array_merge($user,$groupname);
       
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'user' =>$user,
            'group' => $group,
        ]);
        return View::fetch('adminedit');
       
   }
   
   //编辑管理员
   public function adminedits(){
       $uid = input('uid');
       $data = input('data');
        $email = $data['email'];
       $username = $data['username'];
        $password = $data['password'];
        $group  = $data['group'];
        $salt = $this->salt();
        $pass = md5(md5($password).$salt);
        $datas = ['email' => $email, 'username' => $username, 'password' => $pass, 'gid' => $group, 'salt' => $salt];
        $ok =Db::name('user')->where('uid',$uid)->update($datas);
        if($ok){
               return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
           } else{
               return json(['code' => 0, 'data' => '', 'msg' => '修改失败或未作任何修改，请重试']);
           }
       
   }
   
   public function addgroup(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
        ]);
        return View::fetch('addgroup');
       
   }
   
   public function editgroup(){
       $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       $gid = input('gid');
       $data = Db::name('group')->where('gid',$gid)->find();
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'data' =>$data,
        ]);
        return View::fetch('editgroup');
       
   }
   
    public function quanxian(){
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
       $gid = input('gid');
       $data = Db::name('auth_access')->where('gid',$gid)->field('arid')->select();
       $arid = $this->arid($gid,$data);
       View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'data'  => json_encode($arid),
            'gid'   =>$gid,
        ]);
        return View::fetch('quanxian');
       
       
   }
   
   public function arid($gid,$data){
        // $data = Db::name('auth_access')->where('gid',$gid)->field('arid')->select();
        $arr = array();
        foreach($data as $k => $v){
            $arr[] = $v['arid'];
        }
        return $arr;
   }
   
   public function quanxiangroup(){
       $data = input('data');
       $gid = input('gid');
       if(empty($data)){
            Db::name('auth_access')->where([ 'gid' => $gid ])->delete();
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
       }
       $ok = $this->getTree1($data,$gid);
        if($ok){
               return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
           } else{
               return json(['code' => 0, 'data' => '', 'msg' => '修改失败或未作任何修改，请重试']);
           }
   }
   
    public function getTree1($data,$gid)
    {
        Db::name('auth_access')->where([ 'gid' => $gid])->delete();
        foreach ($data as $k => &$v){
            if(!empty($v['id'])&&!empty($data)){
                   $group = Db::name('auth_access')->where(['gid' => $gid, 'rule_name'=>$v['name'], 'type'=>$v['type'] ])->find();
                    if(empty($group)){
                        if(!empty($v['aid']) > 0){
                            $datas = ['gid' => $gid, 'rule_name'=>$v['name'], 'type'=>$v['type'], 'arid' =>$v['id'] ];
                            Db::name('auth_access')->insert($datas);
                        }
                        
                    }
                if (!empty($v["children"])){
                     unset($data[$k]);
                    $this->getTree1($v["children"],$gid);
                    
                }
            }
    }
        return 'ok';
    }
   
   public function editgroupdata(){
        $data = input('data');
       $gid = $data['gid'];
       $name = $data['name'];
       $creditsfrom = $data['creditsfrom'];
       $creditsto = $data['creditsto'];
       $remarks = $data['remarks'];
       $data = ['gid' => $gid, 'name' => $name, 'creditsfrom' => $creditsfrom, 'creditsto' => $creditsto, 'remarks' => $remarks ];
        $ok = Db::name('group')->where('gid', $gid)->update($data);
       if($ok){
               return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
           } else{
               return json(['code' => 0, 'data' => '', 'msg' => '修改失败或未作任何修改，请重试']);
           }
       
   }
   

   
   //添加角色
   public function addgroupdata(){
       $data = input('data');
       $gid = $data['gid'];
       $name = $data['name'];
       $creditsfrom = $data['creditsfrom'];
       $creditsto = $data['creditsto'];
       $remarks = $data['remarks'];
       $data = ['gid' => $gid, 'name' => $name, 'creditsfrom' => $creditsfrom, 'creditsto' => $creditsto, 'remarks' => $remarks ];
        $ok = Db::name('group')->insert($data);
       if($ok){
               return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
           } else{
               return json(['code' => 0, 'data' => '', 'msg' => '添加失败或未作任何修改，请重试']);
           }
   }
   
   //添加管理员
   public function addadmindata(){
       $data = input('data');
       
       
   }
   
   //删除管理员
   public function delgroupdata(){
       $gid = input('gid');
       // table方法必须指定完整的数据表名
       $group = Db::name('group')->where('gid', $gid)->find();
       if(!$group){
           return json(['code' => 0, 'data' => '', 'msg' => '该角色不存在']);
       } else{
           $ok = Db::name('group')->where('gid', $gid)->delete();
           if($ok){
               return json(['code' => 1, 'data' => '', 'msg' => '已删除']);
           } else{
               return json(['code' => 0, 'data' => '', 'msg' => '删除失败，请重试']);
           }
       }
   }
   
   public function lists(){
       $page = intval(input('page'));
       $limit = intval(input('limit'));
       $data = Db::name('group')->page($page,$limit)->select();
       $count = Db::name('group')->count();
       if($data){
           return json(['code' => 1, 'data' => $data, 'msg' => '', 'count' => $count ]);
            
        } else{
            
           return json(['code' => 0, 'data' => '', 'msg' => '暂无数据','count' => 0]); 
        }
       
       
   }
   
   public function adminlist(){
       $page = intval(input('page'));
        $limit = intval(input('limit'));
       $data = Db::name('user')->whereBetween('gid','1,5')->page($page,$limit)->select();
       $count = Db::name('user')->whereBetween('gid','1,5')->count();
       if($data){
            foreach($data as $key => $value){
                $group = Db::name('group')->where('gid', $value['gid'])->find();
                $data[$key] = array_merge($value,$group);
            }
            return json(['code' => 1, 'data' => $data, 'msg' => '','count' => $count]);
            
        } else{
            
           return json(['code' => 0, 'data' => '', 'msg' => '暂无数据','count' => 0]); 
        }
       
       
   }
   
    public function salt(){
        $string = '';
        for ($i=1;$i<13;$i++) {    
             $randstr = chr(rand(65,90));    //指定为字母
            $string .= $randstr; 
        }
        return $string;
    }

}