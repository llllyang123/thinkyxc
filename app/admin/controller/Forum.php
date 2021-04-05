<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Cache;
use think\facade\Config;


class Forum
{
    public function index()
    {
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $forum = Db::name('forum')->select(); 
        $ok = $this->getTree($forum);
        
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'data' =>$ok,
        ]);
        return View::fetch('index');
    }
    
    
    public function forum(){
        $forum = Db::name('forum')->select(); 
        $ok = $this->getTree($forum);
        return json(['code' => 1, 'data' =>$ok, 'msg' =>'成功' ]);
        
    }
    
    public function getTree($array, $pid =0, $level = 0){

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['fup'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['fid'], $level+1);

            }
        }
        return $list;
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
    
    
    
}
