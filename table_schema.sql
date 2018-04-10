CREATE TABLE IF NOT EXISTS `tblcache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(255) DEFAULT NULL,
  `value` varchar(21585) DEFAULT NULL,
  `expiration` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`)
);