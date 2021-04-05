<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkYXC!';
});

Route::get('hello/:name', 'index/hello');
Route::get('thread/:fid','thread/index')->ext('shtml|html');
Route::get('post/:tid','post/index')->ext('shtml|html');
Route::get('serch/:serch','serch/index')->ext('shtml|html');
// Route::get('zxzx/:fid','thread/index')->ext('shtml|html');
// Route::rule('zxzx/:fid', 'thread.index')->ext('shtml|html');
// Route::rule('index/thread/:fid', 'thread/index')->ext('shtml|html');

