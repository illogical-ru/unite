CREATE DATABASE `unite`;

USE `unite`;

CREATE TABLE `users` (
  `id` varchar(32) NOT NULL,
  `email` varchar(48) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `created` datetime NOT NULL,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tokens` (
  `id` varchar(32) NOT NULL,
  `user_id` varchar(32) NOT NULL,
  `ip` int unsigned NOT NULL,
  `created` datetime NOT NULL,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
