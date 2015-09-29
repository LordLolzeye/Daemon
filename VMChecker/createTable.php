<?php
$db = new SQLite3('queue.db');
$db->exec("CREATE TABLE IF NOT EXISTS `queue` (
`id` INTEGER PRIMARY KEY AUTOINCREMENT,
`date` varchar(100) NOT NULL,
`user` varchar(80) NOT NULL UNIQUE,
`email` varchar(80) NOT NULL,
`localDir` varchar(255) NOT NULL,
`status` varchar(30) NOT NULL DEFAULT 'inQueue'
);");
?>
