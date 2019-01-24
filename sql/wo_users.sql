-- ----------------------------
-- Table structure for wo_users
-- ----------------------------
DROP TABLE IF EXISTS `wo_users`;
CREATE TABLE `wo_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户标识',
  `password` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1-正常，0-删除',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
