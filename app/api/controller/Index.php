<?php
declare (strict_types = 1);

namespace app\api\controller;
use think\facade\Db;

class Index
{
    public function index()
    {
        return '您好！这是一个[api]示例应用';
    }
    
    public function setpost(){
        // $data = input('data');
        $admin_id = 1;
        $uid = $admin_id;
        // $tag = $data["tag[]"];
// 		$tag = input('post.tag/a');
        $tag = '';
// 		$tag = implode(",",$tag) ;        
        $fid = 19;
        $time = strtotime('2020-03-29 19:15:46');
        $timeup = $time;
        // $timeup = time();
        $subject = '测试数据百万级别';
        $excerpt = '测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别测试数据百万级别';
        $files = '';
        $status = 1;
        $type = 1;
        // $views = rand(4955,9354);
        $views = 0;
        if(!$files){
            $files = 0;
        }
        $image = '';
        if(!$image){
            $image = '';
        }
        if(!$excerpt){
            $excerpt = $this->StringToText($excerpt,500);
        }
        $content = htmlspecialchars($excerpt);
        $content_fmt = $this->StringToText($excerpt,0);
        // $suiji = rand(2955,9854);
        $likes= 0;
        
        // Db::name('post')->replace()->insert($data);
        
        for ($x=0; $x<=100000; $x++) {
            $data = ['uid' => $uid, 'content' => $content, 'content_fmt' => $content_fmt, 'source' => '', 'files' => $files, 'create_date' => $time+1, 'create_dateup' => $timeup];
                $pid = Db::name('post')->insertGetId($data);
                $datas = ['fid' => $fid,'uid' => $uid,  'likes' => $likes,'tagids' => $tag, 'views' =>$views, 'subject' => $subject, 'create_date' => $time+1, 'create_dateup' => $timeup ,'thumbnail' => $image, 'status' =>$status, 'excerpt' => $excerpt,'type' => $type];
                // Db::name('thread')->replace()->insert($data);
                $tid = Db::name('thread')->insertGetId($datas);
                Db::name('post')->where('pid', $pid)->update(['tid' => $tid]);
                echo "数字是：$x <br>";
            } 
        
        
        return json(["code" => '1', "data" =>"", "msg" => "发布成功", "admin_id" =>$admin_id, "tag" =>$tag]);
    }
    
    public function dellist(){
        $list = Db::name('thread')->where('fid', 19)->limit(0,20000)->select();
        foreach ($list as $k => $v){
            Db::name('post')->where('tid','=',$v['tid'])->delete();
            Db::name('thread')->where('tid','=',$v['tid'])->delete();
            unset($v);
        }
        echo('123');
        $this->dellist();
        
        
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
    
}
