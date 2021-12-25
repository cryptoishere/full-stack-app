CREATE TABLE if not exists `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) DEFAULT NULL,
  `pubkey` varchar(640) UNIQUE KEY NOT NULL,
  `username` varchar(300) UNIQUE KEY NOT NULL,
  `pin` tinyint NOT NULL DEFAULT '0000',
  `account_type` tinyint NOT NULL DEFAULT '1',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;