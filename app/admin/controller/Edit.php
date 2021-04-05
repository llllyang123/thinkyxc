<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Cache;
use think\facade\Config;

class Edit
{
    
    public function index($tid){
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $forum = Db::name('forum')->where('status',1)->field('fid,name')->select(); 
        $thread = Db::name('thread')->where('tid', $tid)->find();
        $forumname =  Db::name('forum')->where('status',1)->where('fid', $thread['fid'])->field('fid,name')->find();
        $post = Db::name('post')->where('tid', $tid)->find();
        $thread = array_merge($thread,$forumname);
        $tagidsp = $thread['tagids'];
        $thumbnail = $thread['thumbnail'];
        if(!$thumbnail){
            $thumbnail = '';
        }
        if(!$tagidsp){
            $tagidsp = '0';
        } else{
            $tagidsp = '1';
        }
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'thread' => $thread,
            'forum' =>$forum,
            'post' =>$post,
            'tagidsp' =>$tagidsp,
            'thumbnail' => $thumbnail,
        ]);
        return View::fetch('index');
        
    }
    
    //更新文章
    public function edit(){
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
        $tid = input('tid');
        $pid = input('pid');
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
        $suiji = rand(2955,9854);//随机访问量
        $data = ['content' => $content, 'content_fmt' => $content_fmt, 'source' => $data["source"], 'files' => $files, 'create_date' => $time, 'create_dateup' => $timeup];
        $posts = Db::name('post')->where('pid', $pid)->cache('post_tid_'.$tid,Config::get('cache.expire'))->update($data);
        
        $datas = ['fid' => $fid ,'tagids' => $tag, 'subject' => $subject, 'create_date' => $time, 'create_dateup' => $timeup ,'thumbnail' => $image, 'status' =>$status, 'excerpt' => $excerpt,'type' => $type];
        $threads = Db::name('thread')->cache(Config::get('cache.expire'))->where('tid', $tid)->update($datas);
        
        if($posts&&$threads){
            // Cache::delete('thread');
            return json(["code" => '1', "data" =>"", "msg" => "修改成功"]);
        } else{
            return json(["code" => '0', "data" =>"", "msg" => "修改失败，可能您并没有做任何修改"]);
            
        }
        
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
 
 
    public function ceshi($tid){
        // $forum = Db::name('forum')->field('fid,name')->select(); 
        // $thread = Db::name('thread')->where('tid', $tid)->find();
        // $forumname =  Db::name('forum')->where('fid', $thread['fid'])->field('fid,name')->find();
        // $thread = array_merge($thread,$forumname);
        // return json($thread);
    
        $source = "hello1,hello2,hello3,hello4,hello5";//按逗号分离字符串
        $hello = explode(',',$source);
        
        for($index=0;$index<count($hello);$index++){
            echo $hello[$index];echo "</br>";
        }
        
        
    }
    
    
}