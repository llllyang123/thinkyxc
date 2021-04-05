<?php
// 这是系统自动生成的middleware定义文件
return [
    app\admin\middleware\Check::class,
    //跨域请求
    think\middleware\AllowCrossDomain::class,
    think\middleware\SessionInit::class,
    // 请求缓存
    think\middleware\CheckRequestCache::class,
    // 多语言加载
    // think\middleware\LoadLangPack::class,
    //表单令牌
    // think\middleware\FormTokenCheck::class,
];
