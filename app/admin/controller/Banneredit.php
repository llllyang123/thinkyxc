<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Cache;
use think\facade\Config;

class Banneredit
{
    public function index($id)
    {
        $template = Db::name('template')->where(['templatestatus' => 2, 'status' =>1])->cache('admin_template',Config::get('cache.expire'))->find();
        View::config(['view_path' => 'template/'.$template['template'].'/']);
        $banner = Db::name('banner')->where('id',$id)->find();
        $img_src = $banner['img_src'];
        if(!$img_src){
            $img_src = '';
        }
        View::assign([
            'name'  => 'CMS管理系统',
            'email' => '673011635@qq.com',
            'banner' => $banner,
            'img_src' => $img_src,
        ]);
        return View::fetch('index');
    }
    
    public function updata(){
        $data = input('data');
        $img_alt = $data["img_alt"];
        $url = $data["url"];
        $target = $data['target'];
        $img_src = input('img_src');
        $id = input('id');
        $weight = $data['weight'];
        
        $datas = ['img_alt' => $img_alt, 'url' => $url, 'img_src' => $img_src, 'update_time' => time(), 'target' => $target, 'weight'=>$weight ];
        $ok = Db::name('banner')->where('id', $id)->cache('banner',Config::get('cache.expire'))->update($datas);
        if($ok){
                return json(['code' => 1, 'data' => '', 'msg' => '成功' ]);
            } else{
                return json(['code' => 0, 'data' => '', 'msg' => '失败，请重试，可能您并没有做任何修改' ]);
        }
        
    }
    
    
}