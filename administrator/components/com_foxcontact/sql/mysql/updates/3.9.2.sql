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