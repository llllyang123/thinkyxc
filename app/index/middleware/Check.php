<?php
declare (strict_types = 1);

namespace app\index\middleware;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\Request;
use think\facade\Event;

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
        //获取当前用户
        $userid = Session::get('userid');
        if(!$userid){
            echo json_encode(['code' => 0, 'data' => '', 'msg' => '您未登录，请登录账号'],JSON_UNESCAPED_UNICODE);
            return redirect('/index/login/index');
            // return json(['code' => 0, 'data' => '/admin', 'msg' => '该账户不是管理员']); 
        }

        return $next($request);
    }
    
    
        public function end(\think\Response $response)
    {
        // 回调行为
    }
    
    
}
