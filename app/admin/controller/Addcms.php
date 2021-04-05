<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Cache;
use think\facade\Config;

class Addcms
{
    public function index()
    {
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        // $forum = Db::name('forum')->where('status',1)->field('fid,name')->select(); 
        $forum = Db::name('forum')->where('status',1)->order(['weight'])->select(); 
        $ok = $this->getTree($forum);
        $admin_id = Session::get('admin.id');
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'fangwen' => number_format(169856420),
            'admin_id' => $admin_id,
            'forum' =>$ok,
        ]);
        return View::fetch('index');
    }
    
    //htmlspecialchars_decode转html代码，输出页面用
    //htmlspecialchars转安全字符，存数据库用
    public function post(){
        $data = input('data');
        $admin_id = Session::get('admin.id');
        $uid = $admin_id;
        // $tag = $data["tag[]"];
// 		$tag = input('post.tag/a');
        $tag = input('tag');
// 		$tag = implode(",",$tag) ;        
        $fid = $data["forum"];
        $time = strtotime($data["time"]);
        $timeup = time();
        $subject = $data["subject"];
        $excerpt = $data["excerpt"];
        $files = $data["file"];
        $status = $data["status"];
        $type = $data["muban"];
        // $views = rand(4955,9354);
        $views = 0;
        if(!$files){
            $files = 0;
        }
        $image = input('image');
        if(!$image){
            $image = '';
        }
        if(!$excerpt){
            $excerpt = $this->StringToText($data["mytextarea"],500);
        }
        $content = htmlspecialchars($data["mytextarea"]);
        $content_fmt = $this->StringToText($data["mytextarea"],0);
        $suiji = rand(2955,9854);
        $likes= 0;
        $data = ['uid' => $uid, 'content' => $content, 'content_fmt' => $content_fmt, 'likes' => $likes, 'source' => $data["source"], 'files' => $files, 'create_date' => $time, 'create_dateup' => $timeup];
        // Db::name('post')->replace()->insert($data);
        $pid = Db::name('post')->insertGetId($data);
        
       
        $data = ['fid' => $fid,'uid' => $uid,  'likes' => $likes,'tagids' => $tag, 'views' =>$views, 'subject' => $subject, 'create_date' => $time, 'create_dateup' => $timeup ,'thumbnail' => $image, 'status' =>$status, 'excerpt' => $excerpt,'type' => $type];
        // Db::name('thread')->replace()->insert($data);
        $tid = Db::name('thread')->insertGetId($data);
        
        Db::name('post')->where('pid', $pid)->cache(Config::get('cache.expire'))->update(['tid' => $tid]);
        Cache::delete('thread'); 
        return json(["code" => '1', "data" =>"", "msg" => "发布成功", "admin_id" =>$admin_id, "tag" =>$tag]);
    }
    
     /**
     * 提取富文本字符串的纯文本,并进行截取;
     * @param $string 需要进行截取的富文本字符串
     * @param $int 需要截取多少位
     */
    public static function StringToText($string,$num){
        if($string){
            //把一些预定义的 HTML 实体转换为字符
            $html_string = htmlspecialchars_decode($string);
            //将空格替换成空
            $content = str_replace(" ", "", $html_string);
            //函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
            $contents = strip_tags($content);
            //返回字符串中的前$num字符串长度的字符
            if(!$num||$num<1){
                return $contents;
            }
            return mb_strlen($contents,'utf-8') > $num ? mb_substr($contents, 0, $num, "utf-8").'....' : mb_substr($contents, 0, $num, "utf-8");
        }else{
            return $string;
        }
    }
    
    //标签查询
    public function tags(){
        $fid = input('fid');
        $cate = Db::name('tag_cate')->where('fid',$fid)->find(); 
        $tag = Db::name('tag')->where('tagid',$cate['cateid'])->field('tagid,name')->select(); 
        return json(["code" => '1', "data" =>"", "msg" => "成功", "tag" =>$tag]);
        
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
