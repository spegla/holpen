CREATE TABLE IF NOT EXISTS `guestbook` (
  `id` int(11) NOT NULL auto_increment,
  `date_time` datetime NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`)
)