<?php
// 事件定义文件
return [
    'bind'      => [
        'ceshi' => 'plugin\ceshi\controlle\Admin',
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
    ],

    'subscribe' => [
    ],
];
