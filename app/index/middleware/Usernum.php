<?php
declare (strict_types = 1);

namespace app\index\middleware;
use think\facade\View;
use think\facade\Session;
// use think\facade\Db;
use think\Request;
use think\facade\Event;
use think\facade\Cache;

class Usernum
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
        $usernum = Cache::get('usernum'); 
        $ip = Session::getId();//session_id(),每个访问者的session_id 都是唯一的；
        $timeout = 30;//30秒内没动作者,认为掉线
        if(!empty($ip)){
            if(!empty($usernum)){
                    foreach ($usernum as $key=>$value){
                        if(time() > $value['time'] &&$value['sid'] == $ip){
                            $usernum[$key] = ['sid' => $ip, 'time' => time()+$timeout ];
                        }  
                        if( time() > $value['time'] &&$value['sid'] != $ip){
                            unset($usernum[$key]);
                        }
                    }
                    Cache::set('usernum',$usernum);
            } else{
                Cache::set('usernum',[]);
                Cache::push('usernum', ['sid' => $ip, 'time' => time()+$timeout ]);
            }
            
            
        }
        $usernums = Cache::get('usernum'); 
        if(empty($usernums)){
            Cache::push('usernum', ['sid' => $ip, 'time' => time()+$timeout ]);
             $usernums = Cache::get('usernum');
        }
        View::assign([ 
            // 'usernum' => json_encode($usernums)   
            'usernum' => count($usernums)   
        ]);
        return $next($request);
    }
    
    
        public function end(\think\Response $response)
    {
        // 回调行为
    }
    
    
}
