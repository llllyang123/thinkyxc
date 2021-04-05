<?php
declare (strict_types = 1);

namespace app\admin\middleware;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Event;
use yxc\Auth;

class Check
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        
        Session::set('backurl','/admin');
        //获取当前用户
        $userid = Session::get('userid');
        $admin_id = Session::get('admin.id');
        if(!$admin_id&&$userid){
            echo json_encode(['code' => 0, 'data' => '', 'msg' => '该账户不是管理员'],JSON_UNESCAPED_UNICODE);
            return redirect('/index/login/adminindex');
            // return json(['code' => 0, 'data' => '/admin', 'msg' => '该账户不是管理员']); 
        } else
        if(!$admin_id){
            return redirect('/index/login/adminindex');
        }
        $ok = new auth;
        $ok->auth_id($request->get('auth_id'));
        
        //  return redirect('/index/login/adminindex');
        return $next($request);
    }
    
    
        public function end(\think\Response $response)
    {
        // 回调行为
        
        
    }
    
    
}
