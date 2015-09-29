<?php
$db = new SQLite3('queue.db');
$db->exec("CREATE TABLE IF NOT EXISTS `queue` (
`id` int(255) NOT NULL AUTO_INCREMENT,
`date` varchar(100) NOT NULL,
`user` varchar(80) NOT NULL,
`email` varchar(80) NOT NULL,
`localDir` varchar(255) NOT NULL,
`status` varchar(30) NOT NULL DEFAULT 'inQueue',
PRIMARY KEY (`id`),
UNIQUE KEY `user` (`user`)
);");
?>
