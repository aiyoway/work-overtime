-- ----------------------------
-- Table structure for wo_times
-- ----------------------------
DROP TABLE IF EXISTS `wo_times`;
CREATE TABLE `wo_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `hours` tinyint(4) NOT NULL DEFAULT '0' COMMENT '时长',
  `date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
