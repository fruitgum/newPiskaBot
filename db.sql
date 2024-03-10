CREATE USER 'newPiska'@'%' IDENTIFIED BY '';

CREATE DATABASE `newPiska`
    /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */
    /*!80016 DEFAULT ENCRYPTION='N' */;

GRANT ALL on 'newPiska'@'*' to 'newPiska'@'%';

CREATE TABLE `history` (
  `chat_id` bigint(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `chat_message_id` int(11) DEFAULT NULL,
  `proceeded` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `piska` (
  `user_id` int(11) DEFAULT NULL,
  `chat_id` bigint(20) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `size` int(5) DEFAULT NULL,
  `last_run` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ;
