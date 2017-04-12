-- phpMyAdmin SQL Dump
-- version 4.6.4deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Gen 06, 2017 alle 23:49
-- Versione del server: 5.7.16-0ubuntu0.16.10.1
-- Versione PHP: 7.0.8-3ubuntu3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `delt`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_account`
--

CREATE TABLE `tbl_account` (
  `id` int(11) NOT NULL,
  `account_parent_id` int(11) DEFAULT NULL,
  `firm_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '1',
  `code` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT 'the code to be used in searching and sorting',
  `rcode` varchar(16) CHARACTER SET utf8 NOT NULL,
  `is_selectable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'if true, it will be possibile to post amounts in this account',
  `subchoices` tinyint(1) NOT NULL DEFAULT '0',
  `position` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'P' COMMENT 'P=Asset/Liability/Equity; E=Profit/Loss; M=Memorandum',
  `outstanding_balance` char(1) COLLATE utf8_bin DEFAULT NULL COMMENT 'C=Credit, D=Debit, null=either',
  `textnames` text COLLATE utf8_bin NOT NULL COMMENT 'a place to store localized names',
  `currentname` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `number_of_children` int(11) NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8,
  `classes` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_challenge`
--

CREATE TABLE `tbl_challenge` (
  `id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `session` varchar(32) DEFAULT NULL,
  `firm_id` int(11) DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `started_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `checked_at` timestamp NULL DEFAULT NULL,
  `method` int(11) NOT NULL,
  `rate` int(3) NOT NULL DEFAULT '0',
  `transaction_id` int(11) DEFAULT NULL,
  `hints` varchar(255) DEFAULT NULL,
  `shown` varchar(255) DEFAULT NULL,
  `declarednoteconomic` varchar(255) DEFAULT NULL,
  `results` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_event`
--

CREATE TABLE `tbl_event` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firm_id` int(11) DEFAULT NULL,
  `action` int(11) NOT NULL,
  `happened_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text,
  `referer` varchar(255) DEFAULT NULL,
  `address` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_exercise`
--

CREATE TABLE `tbl_exercise` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `firm_id` int(11) NOT NULL,
  `slug` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `introduction` text,
  `method` int(11) NOT NULL DEFAULT '61' COMMENT 'default value',
  `session_pattern` varchar(32) DEFAULT 'Ymd'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_firm`
--

CREATE TABLE `tbl_firm` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(32) CHARACTER SET utf8 NOT NULL,
  `firmtype` tinyint(1) NOT NULL DEFAULT '1',
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
  `shortcodes` tinyint(4) NOT NULL DEFAULT '0',
  `css` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_firm_language`
--

CREATE TABLE `tbl_firm_language` (
  `firm_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_firm_user`
--

CREATE TABLE `tbl_firm_user` (
  `firm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` char(1) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_journalentry`
--

CREATE TABLE `tbl_journalentry` (
  `id` int(11) NOT NULL,
  `firm_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `is_closing` tinyint(1) NOT NULL DEFAULT '0',
  `is_adjustment` tinyint(1) NOT NULL DEFAULT '0',
  `is_included` tinyint(1) NOT NULL DEFAULT '1',
  `rank` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_language`
--

CREATE TABLE `tbl_language` (
  `id` int(11) NOT NULL,
  `language_code` char(3) CHARACTER SET utf8 NOT NULL,
  `country_code` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `english_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `native_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `tbl_language`
--

INSERT INTO `tbl_language` (`id`, `language_code`, `country_code`, `english_name`, `native_name`, `is_default`) VALUES
(1, 'en', 'US', 'US English', '', 2),
(2, 'it', 'IT', 'Italian', 'Italiano', 1),
(3, 'fr', 'FR', 'French', 'Français', 0),
(4, 'de', 'DE', 'German', 'Deutsche', 0),
(5, 'en', 'GB', 'UK English', '', 0),
(6, 'en', 'CA', 'Canadian English', '', 0),
(7, 'es', 'ES', 'Spanish', 'Español', 0),
(8, 'pt', 'PT', 'Portuguese', 'Português', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_migration`
--

CREATE TABLE `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_posting`
--

CREATE TABLE `tbl_posting` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `journalentry_id` int(11) NOT NULL,
  `amount` decimal(16,2) NOT NULL COMMENT 'positive if Debit, negative if Credit',
  `rank` int(11) NOT NULL,
  `comment` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `subchoice` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_profiles`
--

CREATE TABLE `tbl_profiles` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `school` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `terms` text COLLATE utf8_bin NOT NULL,
  `usercode` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `language` varchar(7) COLLATE utf8_bin NOT NULL DEFAULT '',
  `allowed_firms` int(11) NOT NULL DEFAULT '20',
  `email_notices` tinyint(1) NOT NULL DEFAULT '1',
  `is_blogger` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_profiles_fields`
--

CREATE TABLE `tbl_profiles_fields` (
  `id` int(11) NOT NULL,
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
  `visible` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `tbl_profiles_fields`
--

INSERT INTO `tbl_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES
(1, 'first_name', 'First Name', 'VARCHAR', 255, 3, 2, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 1, 1),
(2, 'last_name', 'Last Name', 'VARCHAR', 255, 3, 2, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 2, 1),
(3, 'school', 'School', 'VARCHAR', 255, 0, 2, '', '', '', '', '', '', '', 3, 1),
(4, 'terms', 'Terms and conditions acceptance', 'TEXT', 0, 0, 0, '', '', '', '', '', '', '', 0, 0),
(5, 'usercode', 'User code', 'VARCHAR', 255, 0, 0, '', '', '', '', '', '', '', 0, 0),
(6, 'language', 'Language', 'VARCHAR', 7, 0, 0, '', '', '', '', '', '', '', 0, 0),
(7, 'allowed_firms', 'Allowed firms', 'INTEGER', 10, 0, 0, '', '', '', '', '10', '', '', 0, 0),
(8, 'email_notices', 'Email notices', 'INTEGER', 1, 0, 0, '', '', '', '', '', '', '', 4, 0),
(9, 'is_blogger', 'Is this user a blogger?', 'INTEGER', 1, 0, 0, '', '', '', NULL, '0', '', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_template`
--

CREATE TABLE `tbl_template` (
  `id` int(11) NOT NULL,
  `firm_id` int(11) NOT NULL,
  `automatic` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL,
  `info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_transaction`
--

CREATE TABLE `tbl_transaction` (
  `id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '10',
  `event_date` date NOT NULL,
  `description` text NOT NULL,
  `hint` text,
  `regexps` text,
  `points` int(11) NOT NULL DEFAULT '10',
  `penalties` int(11) NOT NULL DEFAULT '5',
  `entries` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `password` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `email` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `activkey` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_user_oauth`
--

CREATE TABLE `tbl_user_oauth` (
  `user_id` int(11) NOT NULL,
  `provider` varchar(45) NOT NULL,
  `identifier` varchar(64) NOT NULL,
  `profile_cache` text,
  `session_data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl_account`
--
ALTER TABLE `tbl_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `firm_code` (`firm_id`,`code`),
  ADD KEY `account_parent_id` (`account_parent_id`),
  ADD KEY `firm_id` (`firm_id`);

--
-- Indici per le tabelle `tbl_challenge`
--
ALTER TABLE `tbl_challenge`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`,`exercise_id`),
  ADD KEY `mark` (`rate`),
  ADD KEY `assigned_at` (`assigned_at`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `firm_id` (`firm_id`),
  ADD KEY `exercise_id` (`exercise_id`),
  ADD KEY `suspended_at` (`suspended_at`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `checked_at` (`checked_at`),
  ADD KEY `session` (`session`);

--
-- Indici per le tabelle `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firm_id` (`firm_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `tbl_exercise`
--
ALTER TABLE `tbl_exercise`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `firm_id` (`firm_id`);

--
-- Indici per le tabelle `tbl_firm`
--
ALTER TABLE `tbl_firm`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `parent_firm_id` (`firm_parent_id`,`create_date`),
  ADD KEY `shortcodes` (`shortcodes`);

--
-- Indici per le tabelle `tbl_firm_language`
--
ALTER TABLE `tbl_firm_language`
  ADD PRIMARY KEY (`firm_id`,`language_id`),
  ADD KEY `firm_id` (`firm_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indici per le tabelle `tbl_firm_user`
--
ALTER TABLE `tbl_firm_user`
  ADD PRIMARY KEY (`firm_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `firm_id` (`firm_id`);

--
-- Indici per le tabelle `tbl_journalentry`
--
ALTER TABLE `tbl_journalentry`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `firm_rank` (`firm_id`,`rank`),
  ADD KEY `firm_id` (`firm_id`),
  ADD KEY `is_confirmed` (`is_confirmed`),
  ADD KEY `is_closing` (`is_closing`),
  ADD KEY `firm_is_confirmed` (`firm_id`,`is_confirmed`),
  ADD KEY `firm_is_closing` (`firm_id`,`is_closing`),
  ADD KEY `is_included` (`is_included`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indici per le tabelle `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_default` (`is_default`);

--
-- Indici per le tabelle `tbl_migration`
--
ALTER TABLE `tbl_migration`
  ADD PRIMARY KEY (`version`);

--
-- Indici per le tabelle `tbl_posting`
--
ALTER TABLE `tbl_posting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journalentryrank` (`journalentry_id`,`rank`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `journalentry_id` (`journalentry_id`),
  ADD KEY `subchoice` (`subchoice`);

--
-- Indici per le tabelle `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `email_notices` (`email_notices`);

--
-- Indici per le tabelle `tbl_profiles_fields`
--
ALTER TABLE `tbl_profiles_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `tbl_template`
--
ALTER TABLE `tbl_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firm_id` (`firm_id`);

--
-- Indici per le tabelle `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exercise_id` (`exercise_id`),
  ADD KEY `event_date` (`event_date`,`rank`);

--
-- Indici per le tabelle `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_username` (`username`),
  ADD UNIQUE KEY `user_email` (`email`);

--
-- Indici per le tabelle `tbl_user_oauth`
--
ALTER TABLE `tbl_user_oauth`
  ADD PRIMARY KEY (`provider`,`identifier`),
  ADD UNIQUE KEY `unic_user_id_name` (`user_id`,`provider`),
  ADD KEY `oauth_user_id` (`user_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl_account`
--
ALTER TABLE `tbl_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=579927;
--
-- AUTO_INCREMENT per la tabella `tbl_challenge`
--
ALTER TABLE `tbl_challenge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
--
-- AUTO_INCREMENT per la tabella `tbl_event`
--
ALTER TABLE `tbl_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112417;
--
-- AUTO_INCREMENT per la tabella `tbl_exercise`
--
ALTER TABLE `tbl_exercise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la tabella `tbl_firm`
--
ALTER TABLE `tbl_firm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2837;
--
-- AUTO_INCREMENT per la tabella `tbl_journalentry`
--
ALTER TABLE `tbl_journalentry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38636;
--
-- AUTO_INCREMENT per la tabella `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT per la tabella `tbl_posting`
--
ALTER TABLE `tbl_posting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259201;
--
-- AUTO_INCREMENT per la tabella `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=722;
--
-- AUTO_INCREMENT per la tabella `tbl_profiles_fields`
--
ALTER TABLE `tbl_profiles_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT per la tabella `tbl_template`
--
ALTER TABLE `tbl_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=553;
--
-- AUTO_INCREMENT per la tabella `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT per la tabella `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=722;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `tbl_account`
--
ALTER TABLE `tbl_account`
  ADD CONSTRAINT `tbl_account_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_challenge`
--
ALTER TABLE `tbl_challenge`
  ADD CONSTRAINT `tbl_challenge_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_challenge_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_challenge_ibfk_3` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_challenge_ibfk_5` FOREIGN KEY (`exercise_id`) REFERENCES `tbl_exercise` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_challenge_ibfk_6` FOREIGN KEY (`transaction_id`) REFERENCES `tbl_transaction` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD CONSTRAINT `tbl_event_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_event_ibfk_2` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_exercise`
--
ALTER TABLE `tbl_exercise`
  ADD CONSTRAINT `tbl_exercise_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_exercise_ibfk_2` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`);

--
-- Limiti per la tabella `tbl_firm_user`
--
ALTER TABLE `tbl_firm_user`
  ADD CONSTRAINT `tbl_firm_user_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_firm_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_journalentry`
--
ALTER TABLE `tbl_journalentry`
  ADD CONSTRAINT `tbl_journalentry_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `tbl_firm` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_journalentry_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `tbl_transaction` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_posting`
--
ALTER TABLE `tbl_posting`
  ADD CONSTRAINT `tbl_posting_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `tbl_account` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_posting_ibfk_2` FOREIGN KEY (`journalentry_id`) REFERENCES `tbl_journalentry` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  ADD CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD CONSTRAINT `tbl_transaction_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `tbl_exercise` (`id`) ON UPDATE CASCADE;
