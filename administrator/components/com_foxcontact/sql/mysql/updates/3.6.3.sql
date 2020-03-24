CREATE TABLE IF NOT EXISTS `#__foxcontact_sequences` (
	`series` VARCHAR(32) NOT NULL,
	`value` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`series`)
)
	DEFAULT CHARSET = utf8;
