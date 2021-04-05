<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Cache;
use think\facade\Config;

class Linkedit
{
    public function index($linkid)
    {
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $link = Db::name('friendlink')->where('linkid',$linkid)->find();
        $thumbnail = $link['thumbnail'];
        if(!$thumbnail){
            $thumbnail = '';
        }
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'link' => $link,
            'thumbnail' => $thumbnail,
        ]);
        return View::fetch('index');
    }
    
    public function updata(){
        $data = input('data');
        $name = $data["name"];
        $url = $data["url"];
        $target = $data['target'];
        $thumbnail = input('thumbnail');
        $linkid = input('linkid');
        $weight = $data['weight'];
        // if($thumbnail == 0){
        //     $thumbnail = '';
        // }
        $datas = ['name' => $name, 'url' => $url, 'thumbnail' => $thumbnail, 'target' => $target, 'weight'=>$weight ];
        $ok = Db::name('friendlink')->where('linkid', $linkid)->cache('friendlink',Config::get('cache.expire'))->update($datas);
        if($ok){
            // Cache::delete('friendlink'); 
                return json(['code' => 1, 'data' => '', 'msg' => '成功' ]);
            } else{
                return json(['code' => 0, 'data' => '', 'msg' => '失败，请重试，可能您并没有做任何修改' ]);
        }
        
    }
    
    
}