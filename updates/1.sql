CREATE TABLE `testimonial_statements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `content` mediumtext,
  `sort_order` int(11) DEFAULT NULL,
  `author_name` varchar(100) DEFAULT NULL,
  `author_link` varchar(100) DEFAULT NULL,
  `author_company` varchar(100) DEFAULT NULL,
  `excerpt` text,
  `is_enabled` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
