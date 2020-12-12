CREATE DATABASE EbookStore 
  COLLATE 'utf8_general_ci';
USE EbookStore;

CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(255),
  `password` varchar(255),
  `full_name` varchar(255),
  `email` varchar(255),
  `failed_login_attempts` int,
  `disabled_until` timestamp
);

CREATE TABLE `recovery_links` (
  `user_id` int PRIMARY KEY,
  `link` varchar(255),
  `expiration` timestamp
);

CREATE TABLE `credit_cards` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `number` varchar(255),
  `expiration` varchar(255),
  `cvv` varchar(3),
  CONSTRAINT unique_card UNIQUE (user_id, number)
);

CREATE TABLE `ebooks` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255),
  `author` varchar(255),
  `description` text,
  `price` float,
  `path` varchar(255)
);

CREATE TABLE `ebook_genre` (
  `ebook_id` int,
  `genre_id` int,
  PRIMARY KEY (`ebook_id`, `genre_id`)
);

CREATE TABLE `genres` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

CREATE TABLE `orders` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `credit_card_id` int,
  `time` timestamp,
  `price` float
);

CREATE TABLE `order_ebook` (
  `order_id` int,
  `ebook_id` int,
  `price` float,
  PRIMARY KEY (`order_id`, `ebook_id`)
);

CREATE TABLE `secret_questions` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `question` varchar(255)
);

CREATE TABLE `secret_answers` (
  `user_id` int PRIMARY KEY,
  `question_id` int,
  `custom_question` varchar(255),
  `answer` varchar(255)
);

ALTER TABLE `recovery_links` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `credit_cards` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `ebook_genre` ADD FOREIGN KEY (`ebook_id`) REFERENCES `ebooks` (`id`);

ALTER TABLE `ebook_genre` ADD FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`credit_card_id`) REFERENCES `credit_cards` (`id`);

ALTER TABLE `order_ebook` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `order_ebook` ADD FOREIGN KEY (`ebook_id`) REFERENCES `ebooks` (`id`);

ALTER TABLE `secret_answers` ADD FOREIGN KEY (`question_id`) REFERENCES `secret_questions` (`id`);

ALTER TABLE `secret_answers` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
