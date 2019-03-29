# Host: localhost  (Version 5.5.5-10.1.37-MariaDB)
# Date: 2019-03-29 22:44:35
# Generator: MySQL-Front 6.1  (Build 1.26)


#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "users"
#

INSERT INTO `users` VALUES (1,'galip','$2y$10$LgbK2vT/mGFjLgXDlavOLOydN4Tzx/rp5yL.EzNy0ETfJe2q34zrO','2019-03-29 22:37:29'),(2,'hasan','$2y$10$fvtnenEfYN5WxKinm6XEMOpaEv9njcxUO0nPYwJuZ58Y/ZYUKlUEm','2019-03-29 22:37:54'),(3,'umit','$2y$10$gQpaMLVpAeFU7bZC9HeJ5.1dVeJTd4OUFjXKDJsFHyMkomc5zm.f.','2019-03-29 22:38:29');
