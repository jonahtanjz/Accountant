CREATE TABLE `trip` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `trip_name` varchar(64) NOT NULL,
  `people` varchar(64) NOT NULL,
  `transactions` varchar(64) NOT NULL,
  `currency` float NOT NULL,
  PRIMARY KEY (`id`)
);