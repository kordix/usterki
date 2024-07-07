-- Zrzut struktury tabela serwis.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' UNIQUE,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli serwis.users: ~0 rows (około)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `login`, `password`, `group`) VALUES
	(1, 'admin', '$2y$10$qN6UQsJXjT04OKR7Pp436eJDyDzW2d6eYE2oF.XkR8OEW9T1iT/0a', 'admin');

ALTER TABLE users
ADD CONSTRAINT unique_login UNIQUE (login);



