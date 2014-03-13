-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 13, 2014 at 07:14 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

--
-- Database: `delt`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account`
--

CREATE TABLE IF NOT EXISTS `tbl_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_parent_id` int(11) DEFAULT NULL,
  `firm_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '1',
  `code` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT 'the code to be used in searching and sorting',
  `rcode` varchar(16) CHARACTER SET utf8 NOT NULL,
  `is_selectable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'if true, it will be possibile to post amounts in this account',
  `position` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'P' COMMENT 'P=Asset/Liability/Equity; E=Profit/Loss; M=Memorandum',
  `outstanding_balance` char(1) COLLATE utf8_bin DEFAULT NULL COMMENT 'C=Credit, D=Debit, null=either',
  `textnames` text COLLATE utf8_bin NOT NULL COMMENT 'a place to store localized names',
  `currentname` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `number_of_children` int(11) NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `firm_code` (`firm_id`,`code`),
  KEY `account_parent_id` (`account_parent_id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=63983 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event`
--

CREATE TABLE IF NOT EXISTS `tbl_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `firm_id` int(11) DEFAULT NULL,
  `action` int(11) NOT NULL,
  `happened_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text,
  `referer` varchar(255) DEFAULT NULL,
  `address` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `firm_id` (`firm_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_firm`
--

CREATE TABLE IF NOT EXISTS `tbl_firm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(32) CHARACTER SET utf8 NOT NULL,
  `firmtype` int(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8_bin,
  `status` smallint(1) NOT NULL DEFAULT '0',
  `currency` varchar(5) CHARACTER SET utf8 NOT NULL,
  `csymbol` char(1) COLLATE utf8_bin NOT NULL,
  `language_id` int(11) NOT NULL,
  `firm_parent_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `banner` blob,
  `frozen_at` timestamp NULL DEFAULT NULL,
  `checked_positions` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `language_id` (`language_id`),
  KEY `parent_firm_id` (`firm_parent_id`,`create_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=571 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_firm_language`
--

CREATE TABLE IF NOT EXISTS `tbl_firm_language` (
  `firm_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`firm_id`,`language_id`),
  KEY `firm_id` (`firm_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_firm_user`
--

CREATE TABLE IF NOT EXISTS `tbl_firm_user` (
  `firm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` char(1) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`firm_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_journalentry`
--

CREATE TABLE IF NOT EXISTS `tbl_journalentry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firm_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `is_closing` tinyint(1) NOT NULL DEFAULT '0',
  `is_adjustment` tinyint(1) NOT NULL DEFAULT '0',
  `is_included` tinyint(1) NOT NULL DEFAULT '1',
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `firm_rank` (`firm_id`,`rank`),
  KEY `firm_id` (`firm_id`),
  KEY `is_confirmed` (`is_confirmed`),
  KEY `is_closing` (`is_closing`),
  KEY `firm_is_confirmed` (`firm_id`,`is_confirmed`),
  KEY `firm_is_closing` (`firm_id`,`is_closing`),
  KEY `is_included` (`is_included`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2164 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE IF NOT EXISTS `tbl_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_code` char(3) CHARACTER SET utf8 NOT NULL,
  `country_code` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `english_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `native_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `is_default` (`is_default`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posting`
--

CREATE TABLE IF NOT EXISTS `tbl_posting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `journalentry_id` int(11) NOT NULL,
  `amount` decimal(16,2) NOT NULL COMMENT 'positive if Debit, negative if Credit',
  `rank` int(11) NOT NULL,
  `comment` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journalentryrank` (`journalentry_id`,`rank`),
  KEY `account_id` (`account_id`),
  KEY `journalentry_id` (`journalentry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=18797 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_profiles`
--

CREATE TABLE IF NOT EXISTS `tbl_profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `school` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `terms` text COLLATE utf8_bin NOT NULL,
  `usercode` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `language` varchar(7) COLLATE utf8_bin NOT NULL DEFAULT '',
  `allowed_firms` int(11) NOT NULL DEFAULT '10',
  `email_notices` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  KEY `email_notices` (`email_notices`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=202 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_profiles_fields`
--

CREATE TABLE IF NOT EXISTS `tbl_profiles_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `field_type` varchar(50) NOT NULL DEFAULT '',
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` text,
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` text,
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_template`
--

CREATE TABLE IF NOT EXISTS `tbl_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firm_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `password` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `email` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `activkey` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=202 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_account`
--
ALTER TABLE `tbl_account`
  ADD CONSTRAINT `tbl_account_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD CONSTRAINT `tbl_event_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_event_ibfk_2` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_firm_user`
--
ALTER TABLE `tbl_firm_user`
  ADD CONSTRAINT `tbl_firm_user_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_firm_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_journalentry`
--
ALTER TABLE `tbl_journalentry`
  ADD CONSTRAINT `tbl_journalentry_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_posting`
--
ALTER TABLE `tbl_posting`
  ADD CONSTRAINT `tbl_posting_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `tbl_account` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_posting_ibfk_2` FOREIGN KEY (`journalentry_id`) REFERENCES `tbl_journalentry` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  ADD CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;
COMMIT;

  
--
-- Dumping data for table `tbl_language`
--

INSERT INTO `tbl_language` (`id`, `language_code`, `country_code`, `english_name`, `native_name`, `is_default`) VALUES
(1, 'en', 'US', 'English', 'English', 2),
(2, 'it', 'IT', 'Italian', 'italiano', 1);
  
INSERT INTO `tbl_users` (`id`, `username`, `password`, `email`, `activkey`, `superuser`, `status`, `create_at`, `lastvisit_at`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'info@example.com', '5bc1a418ca19f1b3f694612375a1afc9', 1, 1, '2013-02-24 13:41:09', '2013-06-09 19:56:21');

--
-- Dumping data for table `tbl_profiles_fields`
--

INSERT INTO `tbl_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES
(1, 'first_name', 'First Name', 'VARCHAR', 255, 3, 2, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 1, 1),
(2, 'last_name', 'Last Name', 'VARCHAR', 255, 3, 2, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 2, 1),
(3, 'school', 'School', 'VARCHAR', 255, 0, 2, '', '', '', '', '', '', '', 3, 1),
(4, 'terms', 'Terms and conditions acceptance', 'TEXT', 0, 0, 0, '', '', '', '', '', '', '', 0, 0),
(5, 'usercode', 'User code', 'VARCHAR', 255, 0, 0, '', '', '', '', '', '', '', 0, 0),
(6, 'language', 'Language', 'VARCHAR', 7, 0, 0, '', '', '', '', '', '', '', 0, 0),
(7, 'allowed_firms', 'Allowed firms', 'INTEGER', 10, 0, 0, '', '', '', '', '10', '', '', 0, 0);

--
-- Dumping data for table `tbl_profiles`
--

INSERT INTO `tbl_profiles` (`user_id`, `first_name`, `last_name`, `school`, `terms`, `usercode`, `language`, `allowed_firms`) VALUES
(1, 'Administrator', 'DELT', '', '', '', 'en', 10);

  
COMMIT;
