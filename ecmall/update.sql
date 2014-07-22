CREATE TABLE `ecm_account` (
  `account_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_money` float(10,2) unsigned NOT NULL,
  `money` float(10,2) NOT NULL,
  `pay_id` int(10) unsigned NOT NULL,
  `pay_name` int(100) NOT NULL,
  `update_time` int(10) NOT NULL,
  `update_user` int(10) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `ecm_bank` (
  `bank_id` int(10) unsigned NOT NULL DEFAULT '0',
  `money` float(10,2) NOT NULL,
  `password` varchar(32) NOT NULL,
  `caution_money` float(10,2) NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `phone_mob` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ecm_member_auth` (
  `auth_id` int(10) unsigned NOT NULL DEFAULT '0',
  `auth_name` varchar(10) DEFAULT NULL,
  `auth_state` char(1) DEFAULT '0',
  `auth_card` varchar(40) DEFAULT NULL,
  `auth_addr` varchar(200) DEFAULT NULL,
  `image_1` varchar(100) DEFAULT NULL,
  `image_2` varchar(100) DEFAULT NULL,
  `image_3` varchar(100) DEFAULT NULL,
  `bank1_name` varchar(100) DEFAULT NULL,
  `bank1_user` varchar(100) DEFAULT NULL,
  `bank1_account` varchar(50) DEFAULT NULL,
  `bank2_name` varchar(100) DEFAULT NULL,
  `bank2_user` varchar(100) DEFAULT NULL,
  `bank2_account` varchar(100) DEFAULT '0',
  `create_time` int(10) DEFAULT NULL,
  `add_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`auth_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ecm_pay` (
  `pay_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` char(1) NOT NULL DEFAULT '',
  `money` float(10,2) NOT NULL,
  `buyer_id` int(10) NOT NULL DEFAULT '0',
  `buyer_name` varchar(20) NOT NULL,
  `seller_id` int(10) NOT NULL DEFAULT '0',
  `seller_name` varchar(100) NOT NULL,
  `pay_name` varchar(200) NOT NULL,
  `status` char(2) NOT NULL DEFAULT '',
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_user` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_user` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pay_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

CREATE TABLE `ecm_funds` (
  `funds_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(10) DEFAULT NULL,
  `in_out` char(1) NOT NULL,
  `type` char(1) NOT NULL DEFAULT '',
  `money` float(10,2) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` varchar(20) NOT NULL,
  `funds_name` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `account` varchar(100) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `status` char(2) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_user` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_user` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`funds_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

CREATE TABLE `ecm_funds_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `funds_id` int(10) NOT NULL,
  `in_out` char(1) NOT NULL,
  `type` char(1) NOT NULL DEFAULT '',
  `money` float(10,2) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` varchar(200) NOT NULL,
  `status` char(2) NOT NULL DEFAULT '',
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_user` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_user` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;


ALTER TABLE `ecm_goods`
ADD COLUMN `year`  varchar(20) NOT NULL AFTER `tags`,
ADD COLUMN `size`  varchar(20) NOT NULL AFTER `year`,
ADD COLUMN `quality`  varchar(20) NOT NULL AFTER `size`,
ADD COLUMN `weight`  varchar(20) NOT NULL AFTER `quality`;












