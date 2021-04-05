-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2021-04-05 15:18:31
-- 服务器版本： 5.6.50-log
-- PHP 版本： 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `tp_aitu666_cn`
--

-- --------------------------------------------------------

--
-- 表的结构 `yxc_auth_access`
--

CREATE TABLE `yxc_auth_access` (
  `acid` int(11) UNSIGNED NOT NULL COMMENT 'id排序',
  `arid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '权限表id',
  `gid` int(11) NOT NULL COMMENT '角色id',
  `rule_name` varchar(110) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则唯一英文标识,全小写',
  `type` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限规则分类,请加应用前缀,如admin_	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限授权表';

--
-- 转存表中的数据 `yxc_auth_access`
--

INSERT INTO `yxc_auth_access` (`acid`, `arid`, `gid`, `rule_name`, `type`) VALUES
(54, 13, 2, 'setting/edit', 'admin_');

-- --------------------------------------------------------

--
-- 表的结构 `yxc_auth_rule`
--

CREATE TABLE `yxc_auth_rule` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'id排序',
  `aid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父id',
  `app` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则所属app',
  `type` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限规则分类，请加应用前缀,如admin_	',
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则唯一英文标识,全小写',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则描述',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态：0=删除，1=正常，2=隐藏'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限规则表';

--
-- 转存表中的数据 `yxc_auth_rule`
--

INSERT INTO `yxc_auth_rule` (`id`, `aid`, `app`, `type`, `name`, `title`, `status`) VALUES
(11, 0, 'admin', 'admin_', 'index', '后台页面', 1),
(12, 11, 'admin', 'admin_', 'settingadmin', '网站配置', 1),
(13, 12, 'admin', 'admin_', 'setting/edit', '修改', 0),
(14, 11, 'admin', 'admin_', 'navadmin', '导航设置', 1),
(15, 14, 'admin', 'admin_', 'forum/status', '状态修改', 0),
(16, 14, 'admin', 'admin_', 'forum/forumadd', '增加分类', 1),
(17, 11, 'admin', 'admin_', 'emailadmin', '邮箱设置', 1),
(18, 11, 'admin', 'admin_', 'linkadmin', '友情链接', 1),
(19, 11, 'admin', 'admin_', 'authadmin', '权限设置', 1),
(20, 11, 'admin', 'admin_', 'groupadmin', '角色管理', 1),
(21, 11, 'admin', 'admin_', 'groupadminadmin', '管理员', 1),
(22, 11, 'admin', 'admin_', 'user', '本站用户', 1),
(23, 11, 'admin', 'admin_', 'useropen', '第三方用户', 1),
(24, 11, 'admin', 'adimin_', 'pluginadmin', '插件列表', 1),
(25, 11, 'admin', 'admin_', 'cmsadmin', '文章管理', 1),
(26, 11, 'admin', 'admin_', 'addcmsadmin', '添加文章', 1),
(27, 11, 'admin', 'admin_', 'forumadmin', '分类管理', 1);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_banner`
--

CREATE TABLE `yxc_banner` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'id排序',
  `weight` int(10) UNSIGNED NOT NULL DEFAULT '1000' COMMENT '权重排序：数字越小越在前面',
  `img_src` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片地址：',
  `img_alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '图片标题属性：设置后有利于seo',
  `url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '点击后要跳转的网址',
  `target` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_blank' COMMENT '打开方式：',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态：1=显示，2=隐藏，3=删除',
  `type` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '位置类型：1=首页，后期自定义',
  `create_time` int(10) UNSIGNED NOT NULL COMMENT '创建时间：',
  `update_time` int(10) UNSIGNED NOT NULL COMMENT '更新时间：'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='网站轮播图表';

--
-- 转存表中的数据 `yxc_banner`
--

INSERT INTO `yxc_banner` (`id`, `weight`, `img_src`, `img_alt`, `url`, `target`, `status`, `type`, `create_time`, `update_time`) VALUES
(2, 1000, '/storage/topic/20210331/9cdbf53f1890291015a7463eecf6eb84.jpg', '测试', '', '_blank', 1, 1, 1617190873, 1617191714),
(5, 1000, '/storage/topic/20210401/2c0b0b4b3edc416931f9f9bad8940a80.jpg', '', '', '_blank', 1, 1, 1617264322, 1617264322),
(6, 1000, '/storage/topic/20210402/49ee58218bd5debfdf292cf9d7126376.jpg', '轮播图', 'https://www.werde.cn', '_blank', 1, 1, 1617349791, 1617349874);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_collect`
--

CREATE TABLE `yxc_collect` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'id排序',
  `tid` bigint(20) UNSIGNED NOT NULL COMMENT '列表文章id',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '用户id',
  `create_date` int(10) UNSIGNED NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户收藏表';

-- --------------------------------------------------------

--
-- 表的结构 `yxc_email_code`
--

CREATE TABLE `yxc_email_code` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'id排序',
  `event` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '事件：',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '验证码',
  `num` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ip',
  `createtime` int(11) UNSIGNED NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邮箱验证码';

-- --------------------------------------------------------

--
-- 表的结构 `yxc_file`
--

CREATE TABLE `yxc_file` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'id排序',
  `file_route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件路径',
  `creat_time` int(10) UNSIGNED NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件管理信息表';

--
-- 转存表中的数据 `yxc_file`
--

INSERT INTO `yxc_file` (`id`, `file_route`, `creat_time`) VALUES
(1, '/storage/topic/20210402/49ee58218bd5debfdf292cf9d7126376.jpg', 1617349783),
(2, '/storage/topic/20210402/333bf25c7f0f306c814612be10f38aa2.png', 1617350633);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_forget_log`
--

CREATE TABLE `yxc_forget_log` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'id排序',
  `email` char(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `create_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '创建时的ip',
  `create_date` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `passwordpast` char(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '修改前的密码',
  `password` char(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '修改后的密码',
  `saltpast` char(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '修改前的密码混杂',
  `salt` char(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '修改后的密码混杂'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='修改密码和忘记密码操作记录表';

-- --------------------------------------------------------

--
-- 表的结构 `yxc_forum`
--

CREATE TABLE `yxc_forum` (
  `fid` int(11) NOT NULL COMMENT '频道id',
  `name` char(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '频道名称',
  `rank` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '等级',
  `threads` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文章列表id',
  `brief` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '简介',
  `announcement` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '公告',
  `seo_title` varchar(69) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'seo标题',
  `seo_description` char(115) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'seo描述',
  `seo_keywords` varchar(95) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'seo关键词',
  `thumbnail` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片',
  `weight` int(11) UNSIGNED NOT NULL DEFAULT '1000' COMMENT '排序权重：',
  `status` int(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态：1=正常，2=隐藏，0=删除',
  `fup` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上一级id',
  `url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网址：',
  `target` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_blank' COMMENT '打开方式：'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='频道栏目表';

--
-- 转存表中的数据 `yxc_forum`
--

INSERT INTO `yxc_forum` (`fid`, `name`, `rank`, `threads`, `brief`, `announcement`, `seo_title`, `seo_description`, `seo_keywords`, `thumbnail`, `weight`, `status`, `fup`, `url`, `target`) VALUES
(1, '最新新闻', 0, 0, '', '', '', '', '', '', 0, 1, 0, '', '_blank'),
(14, '关于我们', 0, 0, '', '', '', '', '', '', 1000, 1, 0, '', '_blank'),
(15, '联系我们', 0, 23, '', '', '', '', '', '', 1000, 1, 0, '', '_blank');

-- --------------------------------------------------------

--
-- 表的结构 `yxc_friendlink`
--

CREATE TABLE `yxc_friendlink` (
  `linkid` bigint(20) UNSIGNED NOT NULL COMMENT 'ID排序：',
  `type` smallint(11) NOT NULL DEFAULT '0',
  `rank` smallint(11) NOT NULL DEFAULT '0' COMMENT '等级',
  `create_date` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `name` char(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `url` char(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网址',
  `thumbnail` varchar(980) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `target` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '打开方式：',
  `status` int(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态：0=删除，1=正常，2=隐藏',
  `weight` int(11) UNSIGNED NOT NULL DEFAULT '10000' COMMENT '排序：'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='友情链接表';

--
-- 转存表中的数据 `yxc_friendlink`
--

INSERT INTO `yxc_friendlink` (`linkid`, `type`, `rank`, `create_date`, `name`, `url`, `thumbnail`, `target`, `status`, `weight`) VALUES
(2, 0, 0, 1596454802, '成都威尔德', 'http://www.werde.cn', '', '_blank', 1, 10000),
(3, 0, 0, 1598264774, '爱途', 'http://www.aitu666.cn', '', '_blank', 1, 10000);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_group`
--

CREATE TABLE `yxc_group` (
  `gid` smallint(6) UNSIGNED NOT NULL,
  `name` char(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `creditsfrom` int(11) NOT NULL DEFAULT '0' COMMENT '起始积分',
  `creditsto` int(11) NOT NULL DEFAULT '0' COMMENT '最高积分',
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备注',
  `allowread` int(11) NOT NULL DEFAULT '0' COMMENT '允许阅读',
  `allowthread` int(11) NOT NULL DEFAULT '0' COMMENT '允许发布',
  `allowpost` int(11) NOT NULL DEFAULT '0' COMMENT '允许回帖',
  `allowattach` int(11) NOT NULL DEFAULT '0' COMMENT '允许 上传',
  `allowdown` int(11) NOT NULL DEFAULT '0' COMMENT '允许下载',
  `allowtop` int(11) NOT NULL DEFAULT '0' COMMENT '允许置顶',
  `allowupdate` int(11) NOT NULL DEFAULT '0' COMMENT '允许编辑',
  `allowdelete` int(11) NOT NULL DEFAULT '0' COMMENT '允许删除',
  `allowmove` int(11) NOT NULL DEFAULT '0' COMMENT '允许移动',
  `allowbanuser` int(11) NOT NULL DEFAULT '0' COMMENT '允许禁止用户',
  `allowdeleteuser` int(11) NOT NULL DEFAULT '0' COMMENT '允许删除用户',
  `allowviewip` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '允许查看用户信息'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户权限表';

--
-- 转存表中的数据 `yxc_group`
--

INSERT INTO `yxc_group` (`gid`, `name`, `creditsfrom`, `creditsto`, `remarks`, `allowread`, `allowthread`, `allowpost`, `allowattach`, `allowdown`, `allowtop`, `allowupdate`, `allowdelete`, `allowmove`, `allowbanuser`, `allowdeleteuser`, `allowviewip`) VALUES
(0, '游客组', 0, 0, '未注册的用户游客访问', 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(1, '管理员组', 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, '超级版主组', 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(4, '版主组', 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1),
(5, '实习版主组', 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 0, 0),
(6, '待验证用户组', 0, 0, '', 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(7, '禁止用户组', 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(101, '一级用户组', 0, 50, '', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(102, '二级用户组', 50, 200, '', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(103, '三级用户组', 200, 1000, '', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(104, '四级用户组', 1000, 10000, '', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(105, '五级用户组', 10000, 10000000, '', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_likes`
--

CREATE TABLE `yxc_likes` (
  `lid` bigint(20) UNSIGNED NOT NULL COMMENT 'id排序',
  `tid` bigint(20) UNSIGNED NOT NULL COMMENT '文章详情id',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '用户id',
  `create_date` int(10) UNSIGNED NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户点赞表';

-- --------------------------------------------------------

--
-- 表的结构 `yxc_plugin`
--

CREATE TABLE `yxc_plugin` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'id排序:',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '插件类型;1:网站;8:微信',
  `has_admin` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有后台管理,0:没有;1:有	',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '	状态;1:开启;0:禁用	',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '插件标识名,英文字母(唯一)	',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '插件名称',
  `demo_url` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '演示地址，带协议	',
  `author` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '插件作者',
  `author_url` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '作者网站链接',
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '插件版本号',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '插件描述',
  `config` text COLLATE utf8mb4_unicode_ci COMMENT '插件配置'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='插件表';

--
-- 转存表中的数据 `yxc_plugin`
--

INSERT INTO `yxc_plugin` (`id`, `type`, `has_admin`, `status`, `create_time`, `name`, `title`, `demo_url`, `author`, `author_url`, `version`, `description`, `config`) VALUES
(10, 1, 1, 0, 1616661820, 'test', '测试插件', 'http://www.aitu666.cn', '爱途平台', 'http://www.aitu666.cn', '1.0', '测试插件', '{\"title\":\"\",\"mail_from\":\"123\",\"mail_secure\":\"\",\"sex\":\"\",\"mail_password\":\"\",\"desc\":\"\"}'),
(11, 1, 0, 0, 1616817852, 'ceshi', '测试插件', 'http://www.aitu666.cn', '爱途平台', 'http://www.aitu666.cn', '1.0', '测试插件', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_post`
--

CREATE TABLE `yxc_post` (
  `pid` bigint(20) UNSIGNED NOT NULL COMMENT 'ID排序：',
  `tid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文章列表id',
  `uid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '未过滤代码的内容：',
  `content_fmt` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '安全过滤代码后的内容：',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '转载出处',
  `files` smallint(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件数量',
  `images` smallint(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '图片数量',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间：',
  `create_date` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建日期',
  `create_dateup` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章详情表';

--
-- 转存表中的数据 `yxc_post`
--

INSERT INTO `yxc_post` (`pid`, `tid`, `uid`, `content`, `content_fmt`, `source`, `files`, `images`, `delete_time`, `create_date`, `create_dateup`) VALUES
(35, 23, 3, '&lt;p&gt;追求极致的同时为PHP正名！谁说PHP不是世界上最好的语言？！&lt;/p&gt;\n&lt;p&gt;性能远超市面同类产品，百万数据压测，借鉴xiuno系统，后期会持续升级优化，提高用户体验！支持插件机制，快速的拓展系统。&lt;/p&gt;\n&lt;p&gt;基于Thinkphp5开发的市面上不少，但是基于Thinkphp6的屈指可数，特此从心开发了一套系统。 精简的同时追求极致的性能，后期会加入更多高性能的功能和优化升级哦！&lt;/p&gt;\n&lt;p&gt;欢迎加入技术群：&amp;nbsp;&lt;/p&gt;\n&lt;p&gt;点击链接加入群聊【ThinkYXC-CMS系统①群】：&lt;a href=&quot;https://jq.qq.com/?_wv=1027&amp;amp;k=aujuhkuJ&quot; target=&quot;_blank&quot; rel=&quot;noopener&quot;&gt;https://jq.qq.com/?_wv=1027&amp;amp;k=aujuhkuJ&lt;/a&gt;&lt;/p&gt;\n&lt;p&gt;或者扫码加入：&lt;/p&gt;\n&lt;p&gt;&lt;img src=&quot;../storage/topic/20210402/333bf25c7f0f306c814612be10f38aa2.png&quot; alt=&quot;&quot; width=&quot;226&quot; height=&quot;290&quot; /&gt;&lt;/p&gt;\n&lt;p&gt;有问题可邮件：673011635@qq.com&lt;/p&gt;', '追求极致的同时为PHP正名！谁说PHP不是世界上最好的语言？！\n性能远超市面同类产品，百万数据压测，借鉴xiuno系统，后期会持续升级优化，提高用户体验！支持插件机制，快速的拓展系统。\n基于Thinkphp5开发的市面上不少，但是基于Thinkphp6的屈指可数，特此从心开发了一套系统。 精简的同时追求极致的性能，后期会加入更多高性能的功能和优化升级哦！\n欢迎加入技术群：&nbsp;\n点击链接加入群聊【ThinkYXC-CMS系统①群】：https://jq.qq.com/?_wv=1027&k=aujuhkuJ\n或者扫码加入：\n\n有问题可邮件：673011635@qq.com', '', 0, 0, 0, 1617004812, 1617355514);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_serch_log`
--

CREATE TABLE `yxc_serch_log` (
  `key_word` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '搜索关键词',
  `time` int(10) UNSIGNED NOT NULL COMMENT '搜索时间',
  `uid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id：0=游客'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户搜索词表，方便大数据分析';

-- --------------------------------------------------------

--
-- 表的结构 `yxc_setting`
--

CREATE TABLE `yxc_setting` (
  `sid` int(11) UNSIGNED NOT NULL COMMENT 'id排序',
  `set_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置名：',
  `set_value` longtext COLLATE utf8mb4_unicode_ci COMMENT '配置值：'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='网站设置表';

--
-- 转存表中的数据 `yxc_setting`
--

INSERT INTO `yxc_setting` (`sid`, `set_name`, `set_value`) VALUES
(3, 'site_cdn', '{\"cdn_root\":\"\"}'),
(5, 'site_email', '{\"mail_from\":\"ThinkYXC\\u7cfb\\u7edfcms\\u7f51\\u7ad9\",\"mail_email\":\"yangxiaochuang2@163.com\",\"mail_smtp\":\"smtp.163.com\",\"mail_secure\":\"ssl\",\"mail_port\":\"465\",\"mail_username\":\"youuser@163.com\",\"mail_password\":\"123456\"}'),
(6, 'site_email_code', '{\"mail_title\":\"ThinkYXC\\u7cfb\\u7edf\\u9a8c\\u8bc1\\u7801\\u6765\\u5566\\uff01\",\"mail_template\":\"<p>\\u4f60\\u597d\\uff0c\\u3010\\u672c\\u7f51\\u7ad9\\u3011\\u63d0\\u793a\\u60a8\\uff0c\\u60a8\\u7684\\u9a8c\\u8bc1\\u7801\\u4e3a{$code},\\u8bf7\\u6ce8\\u610f\\u4fdd\\u62a4\\u60a8\\u7684\\u9a8c\\u8bc1\\u7801\\uff01<\\/p>\"}'),
(1, 'site_info', '{\"site_name\":\"ThinkYXC-CMS\\u7cfb\\u7edf\",\"site_icp\":\"\\u5907\\u6848\\u4fe1\\u606f\",\"site_gov\":\"\",\"site_admin_email\":\"673011635@qq.com\",\"templateadmin\":\"adminwerde\",\"template\":\"werde\",\"site_stats\":\"\"}'),
(4, 'site_logon', '{\"logon_user\":\"1\"}'),
(2, 'site_seo', '{\"seo_title\":\"Thinkyxc-cms\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"seo_keywords\":\"cms\\u7ba1\\u7406\\u7cfb\\u7edf,thinkyxc,thinkphp\\u5efa\\u7ad9\",\"seo_description\":\"\\u57fa\\u4e8ethinkphp6\\u7684cms\\u7ba1\\u7406\\u7cfb\\u7edf\"}');

-- --------------------------------------------------------

--
-- 表的结构 `yxc_tag`
--

CREATE TABLE `yxc_tag` (
  `tagid` int(11) NOT NULL,
  `cateid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `name` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `enable` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `style` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` int(11) UNSIGNED NOT NULL DEFAULT '1000' COMMENT '权重：',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态：1=正常，2=隐藏，0=删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='三级标签分类表';

--
-- 转存表中的数据 `yxc_tag`
--

INSERT INTO `yxc_tag` (`tagid`, `cateid`, `name`, `rank`, `enable`, `style`, `weight`, `status`) VALUES
(1, 1, '测试标签', 0, 1, '', 1000, 1);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_tag_cate`
--

CREATE TABLE `yxc_tag_cate` (
  `cateid` int(11) UNSIGNED NOT NULL,
  `fid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `name` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `rank` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `enable` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `status` int(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态：1=正常，2=隐藏，0=删除',
  `weight` int(11) UNSIGNED NOT NULL DEFAULT '1000' COMMENT '排序：'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='二级标签分类表';

--
-- 转存表中的数据 `yxc_tag_cate`
--

INSERT INTO `yxc_tag_cate` (`cateid`, `fid`, `name`, `rank`, `enable`, `status`, `weight`) VALUES
(3, 1, '分类', 0, 1, 1, 1000);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_template`
--

CREATE TABLE `yxc_template` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'id排序',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否启用',
  `templatestatus` tinyint(2) UNSIGNED NOT NULL COMMENT '模板类型：1=前台模板，2=后台模板',
  `template` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主题名唯一标示：',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主题名：',
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主题版本号',
  `template_url` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '演示地址',
  `thumbnail` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '缩略图',
  `author` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主题作者',
  `author_url` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '作者网站链接',
  `description` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主题描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='网站模板表';

--
-- 转存表中的数据 `yxc_template`
--

INSERT INTO `yxc_template` (`id`, `status`, `templatestatus`, `template`, `name`, `version`, `template_url`, `thumbnail`, `author`, `author_url`, `description`) VALUES
(1, 1, 1, 'werde', 'werde', '1.0', '', '', '', '', ''),
(2, 1, 2, 'adminwerde', 'adminwerde', '1.0', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `yxc_thread`
--

CREATE TABLE `yxc_thread` (
  `fid` smallint(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '频道id',
  `tid` bigint(20) UNSIGNED NOT NULL COMMENT '文章列表id',
  `uid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
  `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `type` int(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '页面类型：1=文章，2=页面',
  `tagids` char(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标签id',
  `tagids_time` int(11) NOT NULL DEFAULT '0' COMMENT '标签创建时间',
  `subject` char(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章主题/标题',
  `excerpt` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '简介摘要：',
  `create_date` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_dateup` int(11) UNSIGNED NOT NULL COMMENT '更新时间',
  `views` bigint(20) NOT NULL DEFAULT '0' COMMENT '点击量',
  `favorites` bigint(20) DEFAULT '0' COMMENT '收藏数',
  `likes` bigint(20) DEFAULT '0' COMMENT '点赞数',
  `thumbnail` varchar(980) COLLATE utf8mb4_unicode_ci DEFAULT '/static/logo.png' COMMENT '缩略图',
  `images` tinyint(6) NOT NULL DEFAULT '0' COMMENT '图片数量',
  `files` tinyint(6) NOT NULL DEFAULT '0' COMMENT '文件数量',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间：',
  `status` int(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态：1=正常，0=删除，2=未审核,3=待修改，4=不允通过'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章列表表';

--
-- 转存表中的数据 `yxc_thread`
--

INSERT INTO `yxc_thread` (`fid`, `tid`, `uid`, `top`, `type`, `tagids`, `tagids_time`, `subject`, `excerpt`, `create_date`, `create_dateup`, `views`, `favorites`, `likes`, `thumbnail`, `images`, `files`, `delete_time`, `status`) VALUES
(15, 23, 3, 0, 2, '', 0, '联系我们', '追求极致的同时为PHP正名！谁说PHP不是世界上最好的语言？！\n性能远超市面同类产品，百万数据压测，借鉴xiuno系统，后期会持续升级优化，提高用户体验！支持插件机制，快速的拓展系统。\n基于Thinkphp5开发的市面上不少，但是基于Thinkphp6的屈指可数，特此从心开发了一套系统。\n欢迎加入技术群：\n点击链接加入群聊【ThinkYXC-CMS系统①群】：https://jq.qq.com/?_wv=1027&k=aujuhkuJ\n或者扫码加入：\n\n有问题可邮件：673011635@qq.com', 1617004812, 1617355514, 107, 0, 0, '', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `yxc_thread_top`
--

CREATE TABLE `yxc_thread_top` (
  `fid` smallint(6) NOT NULL DEFAULT '0' COMMENT '频道id',
  `tid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文章id',
  `top` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '置顶排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章置顶表';

-- --------------------------------------------------------

--
-- 表的结构 `yxc_user`
--

CREATE TABLE `yxc_user` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户编号',
  `gid` smallint(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户组编号',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态：0=删除，1=正常，2=拉黑，3=异常',
  `email` char(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `username` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `realname` char(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `idnumber` char(19) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `password_sms` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码混杂',
  `mobile` char(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `qq` char(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `threads` int(11) NOT NULL DEFAULT '0' COMMENT '发帖数',
  `posts` int(11) NOT NULL DEFAULT '0' COMMENT '回帖数',
  `credits` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `golds` int(11) NOT NULL DEFAULT '0' COMMENT '金币',
  `rmbs` int(11) NOT NULL DEFAULT '0' COMMENT '人民币',
  `create_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '创建时IP',
  `create_date` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `login_ip` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录时IP',
  `login_date` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录时间',
  `logins` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数',
  `avatar` varchar(980) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户头像',
  `avatartime` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户最后更新图像时间',
  `notices` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '通知数',
  `unread_notices` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '未读通知数',
  `email_notice` tinyint(4) NOT NULL DEFAULT '1' COMMENT '邮件通知数量',
  `favorites` int(11) DEFAULT '0' COMMENT '收藏数',
  `invitenums` smallint(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '受邀请用户id',
  `sex` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '性别：0=未知，1=女，2=男',
  `birthday` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '生日',
  `signature` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户签名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

--
-- 转存表中的数据 `yxc_user`
--

INSERT INTO `yxc_user` (`uid`, `gid`, `status`, `email`, `username`, `realname`, `idnumber`, `password`, `password_sms`, `salt`, `mobile`, `qq`, `threads`, `posts`, `credits`, `golds`, `rmbs`, `create_ip`, `create_date`, `login_ip`, `login_date`, `logins`, `avatar`, `avatartime`, `notices`, `unread_notices`, `email_notice`, `favorites`, `invitenums`, `sex`, `birthday`, `signature`) VALUES
(1, 1, 1, '673011635@qq.com', '勇闯天下', '', '', 'e785dee25d1b42280f63cc320d80d944', '', 'OTVXGHVPXKIC', '', '', 0, 0, 0, 0, 0, '0', 0, 192168, 1606221797, 0, '/storage/topic/20200802/c0f586fdbe44a99f0defb82869a840f4.png', 0, 0, 0, 1, 0, 0, 0, 0, ''),
(3, 2, 1, '123@qq.com', '123456', '', '', '853cfb2853c3c61c9a9ea70c7744f331', '', 'IEWEWZAOBCQS', '', '', 0, 0, 0, 0, 0, '0', 0, 192168, 1617606385, 0, '/storage/topic/20200802/c0f586fdbe44a99f0defb82869a840f4.png', 0, 0, 0, 1, 0, 0, 0, 662400, '爱福 ');

-- --------------------------------------------------------

--
-- 表的结构 `yxc_user_open_plat`
--

CREATE TABLE `yxc_user_open_plat` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户编号',
  `platid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '平台编号  0:本站 1:QQ 登录 2:微信登陆 3:支付宝登录 ',
  `openid` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第三方唯一标识'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转储表的索引
--

--
-- 表的索引 `yxc_auth_access`
--
ALTER TABLE `yxc_auth_access`
  ADD PRIMARY KEY (`acid`),
  ADD KEY `gid` (`gid`,`rule_name`,`type`) USING BTREE,
  ADD KEY `arid` (`arid`);

--
-- 表的索引 `yxc_auth_rule`
--
ALTER TABLE `yxc_auth_rule`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `name` (`app`,`name`) USING BTREE,
  ADD KEY `module` (`app`,`type`,`status`) USING BTREE;

--
-- 表的索引 `yxc_banner`
--
ALTER TABLE `yxc_banner`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yxc_collect`
--
ALTER TABLE `yxc_collect`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `tid` (`tid`,`uid`);

--
-- 表的索引 `yxc_email_code`
--
ALTER TABLE `yxc_email_code`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`,`code`);

--
-- 表的索引 `yxc_file`
--
ALTER TABLE `yxc_file`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yxc_forget_log`
--
ALTER TABLE `yxc_forget_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- 表的索引 `yxc_forum`
--
ALTER TABLE `yxc_forum`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `yxc_friendlink`
--
ALTER TABLE `yxc_friendlink`
  ADD PRIMARY KEY (`linkid`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `yxc_group`
--
ALTER TABLE `yxc_group`
  ADD PRIMARY KEY (`gid`);

--
-- 表的索引 `yxc_likes`
--
ALTER TABLE `yxc_likes`
  ADD PRIMARY KEY (`lid`),
  ADD KEY `tid` (`tid`,`uid`),
  ADD KEY `create_date` (`create_date`);

--
-- 表的索引 `yxc_plugin`
--
ALTER TABLE `yxc_plugin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- 表的索引 `yxc_post`
--
ALTER TABLE `yxc_post`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `tid` (`pid`,`tid`,`uid`) USING BTREE,
  ADD KEY `tid_2` (`tid`);

--
-- 表的索引 `yxc_serch_log`
--
ALTER TABLE `yxc_serch_log`
  ADD KEY `key_word` (`key_word`(191)),
  ADD KEY `uid` (`uid`);

--
-- 表的索引 `yxc_setting`
--
ALTER TABLE `yxc_setting`
  ADD PRIMARY KEY (`set_name`),
  ADD UNIQUE KEY `sid` (`sid`),
  ADD KEY `set_name` (`set_name`);

--
-- 表的索引 `yxc_tag`
--
ALTER TABLE `yxc_tag`
  ADD PRIMARY KEY (`tagid`),
  ADD KEY `cateid` (`cateid`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `yxc_tag_cate`
--
ALTER TABLE `yxc_tag_cate`
  ADD PRIMARY KEY (`cateid`),
  ADD KEY `fid` (`fid`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `yxc_template`
--
ALTER TABLE `yxc_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_2` (`status`,`templatestatus`);

--
-- 表的索引 `yxc_thread`
--
ALTER TABLE `yxc_thread`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `fid` (`fid`,`tid`),
  ADD KEY `tagids` (`tagids`),
  ADD KEY `create_date` (`create_date`),
  ADD KEY `create_dateup` (`create_dateup`),
  ADD KEY `fid_2` (`fid`,`type`,`status`),
  ADD KEY `type` (`type`,`delete_time`,`status`);
ALTER TABLE `yxc_thread` ADD FULLTEXT KEY `subject` (`subject`);

--
-- 表的索引 `yxc_thread_top`
--
ALTER TABLE `yxc_thread_top`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `top` (`top`,`tid`),
  ADD KEY `fid` (`fid`,`top`);

--
-- 表的索引 `yxc_user`
--
ALTER TABLE `yxc_user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `gid` (`gid`),
  ADD KEY `status` (`status`),
  ADD KEY `mobile` (`mobile`),
  ADD KEY `uid` (`uid`,`status`);

--
-- 表的索引 `yxc_user_open_plat`
--
ALTER TABLE `yxc_user_open_plat`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `openid_platid` (`platid`,`openid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `yxc_auth_access`
--
ALTER TABLE `yxc_auth_access`
  MODIFY `acid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=55;

--
-- 使用表AUTO_INCREMENT `yxc_auth_rule`
--
ALTER TABLE `yxc_auth_rule`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=28;

--
-- 使用表AUTO_INCREMENT `yxc_banner`
--
ALTER TABLE `yxc_banner`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `yxc_collect`
--
ALTER TABLE `yxc_collect`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `yxc_email_code`
--
ALTER TABLE `yxc_email_code`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=35;

--
-- 使用表AUTO_INCREMENT `yxc_file`
--
ALTER TABLE `yxc_file`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `yxc_forget_log`
--
ALTER TABLE `yxc_forget_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `yxc_forum`
--
ALTER TABLE `yxc_forum`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT COMMENT '频道id', AUTO_INCREMENT=25;

--
-- 使用表AUTO_INCREMENT `yxc_friendlink`
--
ALTER TABLE `yxc_friendlink`
  MODIFY `linkid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID排序：', AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `yxc_likes`
--
ALTER TABLE `yxc_likes`
  MODIFY `lid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `yxc_plugin`
--
ALTER TABLE `yxc_plugin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序:', AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `yxc_post`
--
ALTER TABLE `yxc_post`
  MODIFY `pid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID排序：', AUTO_INCREMENT=1014084;

--
-- 使用表AUTO_INCREMENT `yxc_setting`
--
ALTER TABLE `yxc_setting`
  MODIFY `sid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `yxc_tag`
--
ALTER TABLE `yxc_tag`
  MODIFY `tagid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `yxc_tag_cate`
--
ALTER TABLE `yxc_tag_cate`
  MODIFY `cateid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- 使用表AUTO_INCREMENT `yxc_template`
--
ALTER TABLE `yxc_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id排序', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `yxc_thread`
--
ALTER TABLE `yxc_thread`
  MODIFY `tid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文章列表id', AUTO_INCREMENT=1014064;

--
-- 使用表AUTO_INCREMENT `yxc_user`
--
ALTER TABLE `yxc_user`
  MODIFY `uid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户编号', AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `yxc_user_open_plat`
--
ALTER TABLE `yxc_user_open_plat`
  MODIFY `uid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户编号', AUTO_INCREMENT=8805;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
