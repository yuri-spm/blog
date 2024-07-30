-- Database schema
-- Do NOT drop anything here

CREATE TABLE IF NOT EXISTS `user` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`level` INT(10) NOT NULL DEFAULT '0',
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`status` INT(10) NULL DEFAULT '0',
	`last_login` DATETIME NULL DEFAULT NULL,
	`created_at` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
	`update_at` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `email` (`email`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;


CREATE TABLE IF NOT EXISTS `posts` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`category_id` INT(10) NULL DEFAULT NULL,
	`cover` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`slug` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`title` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`text` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`user_id` INT(10) NULL DEFAULT NULL,
	`status` INT(10) NOT NULL DEFAULT '0',
	`views` INT(10) NULL DEFAULT NULL,
	`last_views` DATETIME NULL DEFAULT NULL,
	`created_at` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
	`update_at` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `slug` (`slug`) USING BTREE,
	INDEX `category` (`category_id`) USING BTREE,
	INDEX `idx_views` (`views`) USING BTREE,
	CONSTRAINT `category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE SET NULL ON DELETE SET NULL
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;


CREATE TABLE IF NOT EXISTS `category` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NULL DEFAULT NULL,
	`title` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`text` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
	`slug` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`status` INT(10) NOT NULL,
	`views` INT(10) NULL DEFAULT NULL,
	`last_views` DATETIME NULL DEFAULT NULL,
	`created_at` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
	`update_at` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `slug` (`slug`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;

CREATE TABLE IF NOT EXISTS `mail` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`admin_email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`sender_email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`status` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`smtp_server` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`smtp_login` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`smtp_password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`smtp_port` INT(10) NOT NULL,
	`comments` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;
