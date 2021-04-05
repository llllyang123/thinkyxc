<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Db;
use think\facade\Cache;
use think\facade\Config;

class Cms
{
    public function index()
    {
        $forum = Db::name('forum')->where('status',1)->order(['fup','weight'=>'desc'])->select(); 
        $forum = $this->getTree($forum);
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'fangwen' => number_format(169856420),
            'forum' =>$forum,
        ]);
        return View::fetch('index');
    }
    
    
    public function lists(){
        $page = intval(input('page'));
        $limit = intval(input('limit'));
        
        $time = input('time');
        $ovtime = input('ovtime');
        $serchtitle = input('serchtitle');
        $forum = input('forum');
        if(empty($time)){
            $time = '1970-10-1';
        }
        if(empty($ovtime)){
           $ovtime = '3999-10-1';
        }
        if(!empty($forum)){
            $where[] = ['fid', '=', $forum];
            
        }
        // $data = Db::name('thread')->where('status','>', 0)->page($page,$limit)->order('tid', 'desc')->select();
        
        $where[] = ['subject|excerpt','like','%'.$serchtitle.'%'];
        $where[] = ['delete_time','=',0];
        $data = Db::name('thread')->where('status','>', 0)->where($where)->page($page,$limit)->whereTime('create_date', 'between', [$time, $ovtime])->select();
        $count = Db::name('thread')->where('status','>', 0)->where($where)->whereTime('create_date', 'between', [$time, $ovtime])->count();
        
        if($data){
            foreach ($data as $k => $v) {
                $username =  Db::name('user')->where('uid', $v['uid'])->value('username');
                 $a=array('username'=>$username);
                 $v = array_merge($v,$a);
                 $data[$k] = $v;
                // print_r($v);
            }
            return json(['code' => 1, 'data' => $data, 'msg' => '','count' => $count]);
            
        } else{
            
           return json(['code' => 0, 'data' => '', 'msg' => '暂无数据','count' => 0]); 
        }
        
        
    }
    
    
    //修改状态
    public function status(){
        $status = input('status');
        $tid = input('tid');
        if($status == 1){
            $status = 2;
        } else if($status == 2){
            $status = 1;
        }
        $ok = Db::name('thread')->where('tid', $tid)->cache(Config::get('cache.expire'))->update(['status' => $status]);
        if($ok){
            
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
            
        } else{
            
            return json(['code' => 0, 'data' => '', 'msg' => '修改失败，请重试']);
            
        }
    }
    
    //删除文章
    public function del($tid){
        $delete_time = time();
        $dela = Db::name('thread')->where('tid',$tid)->cache(Config::get('cache.expire'))->update(['delete_time' => $delete_time]);
        $delb = Db::name('post')->where('tid',$tid)->cache(Config::get('cache.expire'))->update(['delete_time' => $delete_time]);
        // $dela = Db::name('thread')->where('tid',$tid)->delete();
        // $delb = Db::name('post')->where('tid',$tid)->delete();
        if($dela&&$delb){
            Cache::delete('thread');
            Cache::delete('post');
            return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
        } else{
            return json(['code' => 0, 'data' => '', 'msg' => '删除失败，请重试']);
        }
        
    }
    
    //批量删除文章
    public function dellist(){
        $data = input('data');
        // $data = json_decode($data);
        $delete_time = time();
        $datas = json_decode($data,true);
        foreach($datas as $k => $v){
            $dela = Db::name('thread')->where('tid',$v['tid'])->cache(Config::get('cache.expire'))->update(['delete_time' => $delete_time]);
            $delb = Db::name('post')->where('tid',$v['tid'])->cache(Config::get('cache.expire'))->update(['delete_time' => $delete_time]);
            // $dela = Db::name('thread')->where('tid',$v['tid'])->delete();
            // $delb = Db::name('post')->where('tid',$v['tid'])->delete();
        }
        Cache::delete('thread');
        Cache::delete('post');
        return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
        
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
    
}
