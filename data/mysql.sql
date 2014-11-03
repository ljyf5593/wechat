DROP TABLE IF EXISTS `wechatcgi_menus`;
CREATE TABLE `wechatcgi_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `wechat_id` int(10) unsigned NOT NULL COMMENT '所属公众号',
  `menu_data` text NOT NULL COMMENT '菜单数据',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `value` varchar(50) NOT NULL COMMENT '菜单值',
  `type` tinyint(3) unsigned NOT NULL COMMENT '菜单类型',
  `parent_id` int(10) unsigned NOT NULL COMMENT '父ID',
  `dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自定义菜单';


DROP TABLE IF EXISTS `wechatcgi_wechats`;
CREATE TABLE `wechatcgi_wechats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT '公众号名称',
  `origin_id` int(10) unsigned NOT NULL COMMENT '原始ID',
  `account` varchar(50) NOT NULL COMMENT '微信号',
  `token` varchar(50) NOT NULL COMMENT 'Token',
  `user_id` int(10) unsigned NOT NULL COMMENT '所属用户',
  `app_id` varchar(50) NOT NULL COMMENT 'AppId',
  `app_secret` varchar(50) NOT NULL COMMENT 'AppSecret',
  `access_token` varchar(50) NOT NULL COMMENT '请求凭证',
  `expires_in` int(10) NOT NULL COMMENT '凭证过期时间',
  `dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公众账号';