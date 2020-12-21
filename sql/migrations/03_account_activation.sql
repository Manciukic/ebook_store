ALTER TABLE `users` ADD `activated` boolean default false;

CREATE TABLE `activation_links` (
  `user_id` int PRIMARY KEY,
  `link` varchar(255),
  `expiration` timestamp
);

ALTER TABLE `activation_links` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
