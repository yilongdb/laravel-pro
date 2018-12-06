DROP DATABASE IF EXISTS `bento_laravelpro`;
CREATE DATABASE bento_laravelpro
CHARACTER SET utf8;
#SET storage_engine=INNODB;
USE bento_laravelpro;

DROP TABLE IF EXISTS `users`;
CREATE TABLE users(
	user_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(80) NOT NULL,
	email VARCHAR(60) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	confirmed BOOL DEFAULT FALSE NOT NULL,
	confirmation_code VARCHAR(255),
	PRIMARY KEY(user_id),
	UNIQUE KEY (email),
	INDEX (name)
);


DROP TABLE IF EXISTS `files`;
CREATE TABLE `files`(
	file_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id MEDIUMINT UNSIGNED NOT NULL,
	name VARCHAR(80) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(file_id),
	INDEX (name),
	FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE NO ACTION
);

DROP TABLE IF EXISTS `components`;
CREATE TABLE `components`(
	component_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	file_id MEDIUMINT UNSIGNED NOT NULL,
	name VARCHAR(80) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(component_id),
	INDEX (name),
	FOREIGN KEY (file_id) REFERENCES files (file_id) ON DELETE CASCADE ON UPDATE NO ACTION
);

DROP TABLE IF EXISTS `layers`;
CREATE TABLE `layers`(
	layer_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	component_id MEDIUMINT UNSIGNED NOT NULL,
	file_id MEDIUMINT UNSIGNED NOT NULL,
	name VARCHAR(80) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	type ENUM('box' , 'image' , 'icon' , 'slot' , 'text') NOT NULL DEFAULT 'box',
		`status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '状态(0:已删除; 1:正常)',
	parent_id MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY(layer_id),
	INDEX (parent_id),
	INDEX (name),
	FOREIGN KEY (component_id) REFERENCES components (component_id) ON DELETE CASCADE ON UPDATE NO ACTION,
	FOREIGN KEY (file_id) REFERENCES files (file_id) ON DELETE CASCADE ON UPDATE NO ACTION
);

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens`(
	token_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'token id',
	file_id MEDIUMINT UNSIGNED NOT NULL,
	value TEXT NOT NULL,
	`status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '状态(0:已删除; 1:正常)',
	name VARCHAR(80) NOT NULL DEFAULT 'untitled-token',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	type ENUM('radius' , 'color' , 'border' , 'font') NOT NULL,
	PRIMARY KEY(token_id),
	INDEX (name),
	FOREIGN KEY (file_id) REFERENCES files (file_id) ON DELETE CASCADE ON UPDATE NO ACTION
);