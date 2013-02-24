-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 24 feb, 2013 at 09:21 PM
-- Versione MySQL: 5.1.41
-- Versione PHP: 5.3.2-1ubuntu4.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;


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
  `code` varchar(16) CHARACTER SET utf8 NOT NULL,
  `is_selectable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `account_parent_id` (`account_parent_id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- Dump dei dati per la tabella `tbl_account`
--

INSERT INTO `tbl_account` (`id`, `account_parent_id`, `firm_id`, `level`, `code`, `is_selectable`) VALUES
(1, NULL, 1, 1, '01', 0),
(2, NULL, 1, 1, '02', 0),
(3, NULL, 1, 1, '03', 0),
(4, 1, 1, 2, '01.01', 1),
(5, 1, 1, 2, '01.02', 1),
(6, 1, 1, 2, '01.10', 0),
(7, 2, 1, 2, '02.01', 0),
(8, 3, 1, 2, '03.01', 1),
(9, 6, 1, 3, 'CUST01', 1),
(10, 6, 1, 3, 'CUST02', 1),
(11, 7, 1, 1, 'SUPPL01', 1),
(12, 7, 1, 1, 'SUPPL02', 1);

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
(12, 1, 'Supplier Two');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_debitcredit`
--

CREATE TABLE IF NOT EXISTS `tbl_debitcredit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

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
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `currency` varchar(5) CHARACTER SET utf8 NOT NULL,
  `csymbol` char(1) COLLATE utf8_bin NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `tbl_firm`
--

INSERT INTO `tbl_firm` (`id`, `name`, `slug`, `is_public`, `currency`, `csymbol`, `language_id`) VALUES
(1, 'Test One Inc.', 'test-one', 1, 'USD', '$', 1),
(2, 'Test Two Inc.', 'test-two', 0, 'USD', '$', 1),
(3, 'Test Three Ltd', 'test-three', 0, 'GBP', '£', 1),
(4, 'Test Four SpA', 'test-four', 0, 'EUR', '€', 2);

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
(1, 2, '');

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
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `tbl_profiles`
--

INSERT INTO `tbl_profiles` (`user_id`, `first_name`, `last_name`) VALUES
(1, 'Administrator', 'Admin'),
(2, 'Loris', 'Tissino');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `tbl_profiles_fields`
--

INSERT INTO `tbl_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES
(1, 'first_name', 'First Name', 'VARCHAR', 255, 3, 2, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 1, 3),
(2, 'last_name', 'Last Name', 'VARCHAR', 255, 3, 2, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 2, 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `password`, `email`, `activkey`, `superuser`, `status`, `create_at`, `lastvisit_at`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'webmaster@example.com', 'cf039dcc239258ae55bb2905c9c5b70d', 1, 1, '2013-02-24 13:41:09', '0000-00-00 00:00:00'),
(2, 'abcdef', 'e10adc3949ba59abbe56e057f20f883e', 'loris@tissino.it', '972ed1ae320b27035bb8c41ff6af9e1d', 0, 0, '2013-02-24 13:46:18', '0000-00-00 00:00:00');

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

--
-- Limiti per la tabella `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tbl_profiles` (`user_id`);
COMMIT;
