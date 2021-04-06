ThinkYXC 1.0
===============

> 运行环境要求PHP7.1+。

基于最新的ThinkPHPV6.0版本倾力打造！追求极致的同时为PHP正名！谁说PHP不是世界上最好的语言？！

性能远超市面同类产品，百万数据压测，借鉴xiuno系统，后期会持续升级优化，提高用户体验！支持插件机制，快速的拓展系统。

基于Thinkphp5开发的市面上不少，但是基于Thinkphp6的屈指可数，特此从心开发了一套系统。 精简的同时追求极致的性能，后期会加入更多高性能的功能和优化升级哦！

欢迎加入技术群： 

点击链接加入群聊【ThinkYXC-CMS系统①群】：https://jq.qq.com/?_wv=1027&k=aujuhkuJ

## 主要新特性

* 采用`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 原生多应用支持
* 更强大和易用的查询
* 全新的事件系统
* 模型事件和数据库事件统一纳入事件系统
* 模板引擎分离出核心
* 内部功能中间件化
* SESSION/Cookie机制改进
* 对Swoole以及协程支持改进
* 对IDE更加友好
* 统一和精简大量用法
* 支持插件化
* 几乎全部开通了缓存功能，文章内容能够实时更新，支持高并发
* 加入了同一用户访问频率限制，提升性能
* 后续持续优化升级……

## 安装

Github下载地址：
https://gitee.com/werde/think-yxc-cms

Gitee下载地址：
https://gitee.com/werde/think-yxc-cms

或者使用

> composer require llllyang123/thinkyxc
~~~
部署到服务器，导入sqldata文件夹下的数据库文件后，修改config配置文件的数据库账号密码信息
~~~


## 文档
基于
[完全开发手册](https://www.kancloud.cn/manual/thinkphp6_0/content) 

[ThinkYXC手册](https://www.kancloud.cn/llllyang123/thinkyxc/1931980)

很纯的基于thinkphp6，没有多余的库，极大降低耦合度的同时发掘更深层次的性能！

市面同类型产品几乎都单独开发了一套自己的库，不仅提高的耦合度，更降低了性能，试想一下：我只要加载一个首页，但是却需要加载thinkphp的库还要加载开发者自己开发的库文件，功能多的时候还要加载其他功能的库文件，想想有多臃肿！性能怎么可能快！

美其名曰是方便快速开发，实际上却是多了太多的不规范的标准，不仅要学习适应thinkphp的标准，还要学所用系统的代码标准，学习成本提高的同时也浪费了大量的精力。

犹如手机厂商绞尽脑汁将手机做薄，而你却给他换了个一厘米多厚的壳，告诉消费者说这是你的新款产品……


