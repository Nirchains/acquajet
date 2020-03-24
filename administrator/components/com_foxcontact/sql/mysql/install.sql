CREATE TABLE IF NOT EXISTS `#__foxcontact_enquiries` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`form_id` INT(11) NOT NULL,
	`date` DATE NOT NULL,
	`exported` TINYINT(4) NOT NULL DEFAULT '0',
	`ip` VARCHAR(15) NOT NULL,
	`url` TEXT NOT NULL,
	`fields` MEDIUMTEXT NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_time` (`date`)
)
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS `#__foxcontact_captcha` (
	`session_id` VARCHAR(200) NOT NULL,
	`form_uid` VARCHAR(16) NOT NULL,
	`date` INT NOT NULL,
	`answer` VARCHAR(64) NOT NULL,
	PRIMARY KEY (`session_id`, `form_uid`)
)
	DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `#__foxcontact_sequences` (
	`series` VARCHAR(32) NOT NULL,
	`value` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`series`)
)
	DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `#__foxcontact_domain_blacklist` (
	`domain` varchar(256) NOT NULL,
	PRIMARY KEY (`domain`)
)
	DEFAULT CHARSET = latin1;

INSERT INTO `#__foxcontact_domain_blacklist` (`domain`) VALUES
('qq.com'),
('trash-mail.com'),
('trashmail.com')
ON DUPLICATE KEY UPDATE `domain`=`domain`;
-- ON DUPLICATE KEY UPDATE Prevent duplicate key mysql error if a previous table has not been removed for some strange reason