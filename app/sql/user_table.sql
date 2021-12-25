CREATE TABLE if not exists `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `pin` tinyint NOT NULL,
  `account_type` tinyint NOT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;