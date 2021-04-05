<?php
// 这是系统自动生成的event定义文件
return [
    'bind'      => [
        // 'UserLogin' => 'app\admin\event\UserLogin',
        // 更多事件绑定
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'UserLogin' => ['app\admin\event\UserLogin'],
    ],

    'subscribe' => [
    ],
    
];
