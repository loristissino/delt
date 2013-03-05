-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 05 mar, 2013 at 05:28 PM
-- Versione MySQL: 5.1.41
-- Versione PHP: 5.3.2-1ubuntu4.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `delt`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_account`
--

CREATE TABLE IF NOT EXISTS `tbl_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_parent_id` int(11) DEFAULT NULL,
  `firm_id` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `code` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT 'the code to be used in searching and sorting',
  `is_selectable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'if true, it will be possibile to post amounts in this account',
  `nature` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'P' COMMENT 'P=Asset/Liability/Equity; E=Profit/Loss; M=Memorandum',
  `outstanding_balance` char(1) COLLATE utf8_bin DEFAULT NULL COMMENT 'C=Credit, D=Debit, null=either',
  `textnames` text COLLATE utf8_bin NOT NULL COMMENT 'a place to store localized names',
  PRIMARY KEY (`id`),
  UNIQUE KEY `firm_code` (`firm_id`,`code`),
  KEY `account_parent_id` (`account_parent_id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=17 ;

--
-- Dump dei dati per la tabella `tbl_account`
--

INSERT INTO `tbl_account` (`id`, `account_parent_id`, `firm_id`, `level`, `code`, `is_selectable`, `nature`, `outstanding_balance`, `textnames`) VALUES
(1, NULL, 1, 1, '01', 0, 'P', NULL, ''),
(2, NULL, 1, 1, '02', 0, 'P', NULL, ''),
(3, NULL, 1, 1, '03', 0, 'P', NULL, ''),
(4, 1, 1, 2, '01.01', 1, 'P', 'D', ''),
(5, 1, 1, 2, '01.02', 1, 'P', NULL, ''),
(6, 1, 1, 2, '01.10', 0, 'P', NULL, ''),
(7, 2, 1, 2, '02.01', 0, 'P', NULL, ''),
(8, 3, 1, 2, '03.01', 1, 'P', 'C', ''),
(9, 6, 1, 3, '01.10.C01', 1, 'P', 'D', ''),
(10, 6, 1, 3, '01.10.C02', 1, 'P', 'D', ''),
(11, 7, 1, 3, '02.01.S01', 1, 'P', 'C', ''),
(12, 7, 1, 3, '02.01.S02', 1, 'P', 'C', ''),
(13, NULL, 1, 1, '11', 0, 'E', NULL, ''),
(14, NULL, 1, 1, '21', 0, 'E', NULL, ''),
(15, 13, 1, 2, '11.01', 1, 'E', 'D', ''),
(16, 14, 1, 2, '21.01', 1, 'E', 'C', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_account_name`
--

CREATE TABLE IF NOT EXISTS `tbl_account_name` (
  `account_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`account_id`,`language_id`),
  KEY `language_id` (`language_id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `tbl_account_name`
--

INSERT INTO `tbl_account_name` (`account_id`, `language_id`, `name`) VALUES
(1, 1, 'Assets'),
(1, 2, 'Attività'),
(2, 1, 'Liabilities'),
(2, 2, 'Passività'),
(3, 1, 'Equity'),
(3, 2, 'Capitale netto'),
(4, 1, 'Cash'),
(4, 2, 'Cassa'),
(5, 1, 'Bank Checking Account'),
(5, 2, 'Banca c/c'),
(6, 1, 'Accounts Receivable'),
(6, 2, 'Crediti v/clienti'),
(7, 1, 'Accounts Payable'),
(7, 2, 'Debiti v/fornitori'),
(8, 1, 'Owner''s equity'),
(8, 2, 'Capitale proprio'),
(9, 1, 'Customer One'),
(10, 1, 'Customer Two'),
(11, 1, 'Supplier One'),
(12, 1, 'Supplier Two'),
(13, 1, 'Expenses'),
(13, 2, 'Costi'),
(14, 1, 'Revenues'),
(14, 2, 'Ricavi'),
(15, 1, 'Purchases'),
(15, 2, 'Merci c/acquisti'),
(16, 1, 'Sales'),
(16, 2, 'Merci c/vendite');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_debitcredit`
--

CREATE TABLE IF NOT EXISTS `tbl_debitcredit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'positive if Debit, negative if Credit',
  `rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `tbl_debitcredit`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_firm`
--

CREATE TABLE IF NOT EXISTS `tbl_firm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(32) CHARACTER SET utf8 NOT NULL,
  `status` smallint(1) NOT NULL DEFAULT '0',
  `currency` varchar(5) CHARACTER SET utf8 NOT NULL,
  `csymbol` char(1) COLLATE utf8_bin NOT NULL,
  `language_id` int(11) NOT NULL,
  `firm_parent_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `parent_firm_id` (`firm_parent_id`,`create_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `tbl_firm`
--

INSERT INTO `tbl_firm` (`id`, `name`, `slug`, `status`, `currency`, `csymbol`, `language_id`, `firm_parent_id`, `create_date`) VALUES
(1, 'Test One Inc.', 'test-one', 1, 'USD', '$', 1, 0, '0000-00-00 00:00:00'),
(2, 'Test Two Inc.', 'test-two', 0, 'USD', '$', 1, 0, '0000-00-00 00:00:00'),
(3, 'Test Three Ltd', 'test-three', 0, 'GBP', '£', 1, 0, '0000-00-00 00:00:00'),
(4, 'Test Four SpA', 'test-four', 0, 'EUR', '€', 2, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_firm_user`
--

CREATE TABLE IF NOT EXISTS `tbl_firm_user` (
  `firm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` char(1) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`firm_id`,`user_id`),
  UNIQUE KEY `firm_id` (`firm_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `tbl_firm_user`
--

INSERT INTO `tbl_firm_user` (`firm_id`, `user_id`, `role`) VALUES
(1, 2, ''),
(4, 2, 'O');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_language`
--

CREATE TABLE IF NOT EXISTS `tbl_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_code` char(3) CHARACTER SET utf8 NOT NULL,
  `country_code` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `english_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `native_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `tbl_language`
--

INSERT INTO `tbl_language` (`id`, `language_code`, `country_code`, `english_name`, `native_name`) VALUES
(1, 'en', 'US', 'English', 'English'),
(2, 'it', 'IT', 'Italian', 'italiano');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tbl_migration`
--

INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1361709638),
('m110805_153437_installYiiUser', 1361709669),
('m110810_162301_userTimestampFix', 1361709670);

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_post`
--

CREATE TABLE IF NOT EXISTS `tbl_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firm_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `tbl_post`
--

INSERT INTO `tbl_post` (`id`, `firm_id`, `date`, `description`, `is_confirmed`, `rank`) VALUES
(1, 1, '2013-02-25', 'Establishment', 0, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_profiles`
--

CREATE TABLE IF NOT EXISTS `tbl_profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `school` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dump dei dati per la tabella `tbl_profiles`
--

INSERT INTO `tbl_profiles` (`user_id`, `first_name`, `last_name`, `school`) VALUES
(1, 'Administrator', 'Admin', ''),
(2, 'Abcdef', 'Defghjkl', ''),
(7, 'Loris', 'Tissino', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_profiles_fields`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `tbl_profiles_fields`
--

INSERT INTO `tbl_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES
(1, 'first_name', 'First Name', 'VARCHAR', 255, 3, 2, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 1, 1),
(2, 'last_name', 'Last Name', 'VARCHAR', 255, 3, 2, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 2, 1),
(3, 'school', 'School', 'VARCHAR', 255, 0, 2, '', '', '', '', '', '', '', 0, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_users`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dump dei dati per la tabella `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `password`, `email`, `activkey`, `superuser`, `status`, `create_at`, `lastvisit_at`) VALUES
(1, 'admin', '00e624aa2bcc3f749e28af0732cd5f10', 'webmaster@example.com', 'b6b2194b3c9dde4bd74bd636044989d9', 1, 1, '2013-02-24 13:41:09', '2013-03-03 12:30:58'),
(2, 'abcdef', '5fe6ce280af32ba2816b0f0d4cff5e63', 'abcdef@example.com', '09a586d70c9a9004e6f567d367c000d2', 0, 1, '2013-02-24 13:46:18', '2013-03-04 08:21:25'),
(7, 'pippo', 'e08a7c49d96c2b475656cc8fe18cee8e', 'pippo@example.com', 'd25a809426d4cb8412f8fcef681f09e7', 0, 1, '2013-03-02 18:31:36', '0000-00-00 00:00:00');

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `tbl_account`
--
ALTER TABLE `tbl_account`
  ADD CONSTRAINT `tbl_account_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_account_name`
--
ALTER TABLE `tbl_account_name`
  ADD CONSTRAINT `tbl_account_name_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `tbl_account` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_account_name_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `tbl_language` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_debitcredit`
--
ALTER TABLE `tbl_debitcredit`
  ADD CONSTRAINT `tbl_debitcredit_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `tbl_account` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_debitcredit_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `tbl_post` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_firm_user`
--
ALTER TABLE `tbl_firm_user`
  ADD CONSTRAINT `tbl_firm_user_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_firm_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_post`
--
ALTER TABLE `tbl_post`
  ADD CONSTRAINT `tbl_post_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  ADD CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;
