DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `id` bigint NOT NULL auto_increment,
  `group` varchar(200) default NULL COMMENT 'email of the group creator',
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `email` varchar(200) unique COMMENT 'email of the person, mandatory if the person is the group creator',
  `cancel_token` varchar(32) NOT NULL,
  `status` tinyint NOT NULL default '0' COMMENT '0:ok; 1:cancel',
  `seat` varchar(16) default NULL,
  `creation` datetime NOT NULL,
  PRIMARY KEY  (`id`)
)

