-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 10 mar, 2013 at 12:14 PM
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
  `number_of_children` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `firm_code` (`firm_id`,`code`),
  KEY `account_parent_id` (`account_parent_id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=83 ;

--
-- Dump dei dati per la tabella `tbl_account`
--

INSERT INTO `tbl_account` (`id`, `account_parent_id`, `firm_id`, `level`, `code`, `is_selectable`, `nature`, `outstanding_balance`, `textnames`, `number_of_children`) VALUES
(2, NULL, 1, 1, '02', 0, 'P', NULL, '', 6),
(3, NULL, 1, 1, '03', 0, 'P', NULL, 'en_US: Stockholders'' Equity Accounts\r\nit_IT: Capitale netto', 4),
(5, 17, 1, 2, '01.02', 1, 'P', NULL, 'en_US: Bank Accounts\r\nit_IT: C/C bancari\r\n', 0),
(6, 17, 1, 2, '01.03', 0, 'P', NULL, 'en_US: Accounts Receivable\r\nit_IT: Crediti v/clienti\r\n', 2),
(7, 2, 1, 2, '02.01', 0, 'P', NULL, '', 2),
(8, 3, 1, 2, '03.01', 1, 'P', 'C', 'en_US: Common Stock\r\nit_IT: Capitale sociale', 0),
(16, NULL, 1, 1, '04', 0, 'E', NULL, 'en_US: Revenues\r\nit_IT: Ricavi', 4),
(17, NULL, 1, 1, '01', 0, 'P', NULL, 'en_US: Assets\r\nit_IT: Attività\r\n', 11),
(18, 17, 1, 2, '01.01', 1, 'P', 'D', 'en_US: Cash\r\nit_IT: Cassa\r\n', 0),
(20, 7, 1, 3, '02.01.SUPP01', 1, 'P', 'C', 'en_US: Supplier Example #1\r\nit_IT: Fornitore Esempio n. 1\r\n', 0),
(21, NULL, 4, 1, '01', 0, 'P', NULL, 'en_US: \r\nit_IT: Prova uno', 1),
(22, 21, 4, 2, '01.', 1, 'P', NULL, 'en_US: \r\nit_IT: prova due', 0),
(23, NULL, 4, 1, '02', 0, 'P', NULL, 'it_IT: Con un bel nome Ciao\r\n', 1),
(24, 23, 4, 2, '02.', 1, 'P', 'C', 'en_US: fghdfhdfh\r\nit_IT: ghdfgh', 0),
(25, 6, 1, 3, '01.03.CUST01', 1, 'P', 'D', 'en_US: Customer Example #1\r\nit_IT: Cliente Esempio n. 1\r\n', 0),
(26, 6, 1, 3, '01.03.CUST02', 1, 'P', 'D', 'en_US: Customer Example #2\r\nit_IT: Cliente Esempio n. 2', 0),
(27, 17, 1, 2, '01.04', 1, 'P', 'D', 'en_US: Prepaid Expenses\r\nit_IT: Risconti attivi\r\n', 0),
(28, 17, 1, 2, '01.05', 1, 'P', 'D', 'en_US: Inventory\r\nit_IT: Magazzino', 0),
(29, 17, 1, 2, '01.06', 1, 'P', 'D', 'en_US: Buildings\r\nit_IT: Fabbricati', 0),
(30, 17, 1, 2, '01.07', 1, 'P', 'C', 'en_US: Accumulated Depreciation on Buildings\r\nit_IT: Fondo ammortamento Fabbricati\r\n', 0),
(31, 17, 1, 2, '01.08', 0, 'P', NULL, 'en_US: Vehicles & Equipment\r\nit_IT: Automezzi e Attrezzature', 2),
(32, 31, 1, 3, '01.08.01', 1, 'P', 'D', 'en_US: Vehicles\r\nit_IT: Automezzi', 0),
(33, 31, 1, 3, '01.08.02', 1, 'P', 'D', 'en_US: Equipment\r\nit_IT: Attrezzature', 0),
(34, 17, 1, 2, '01.09', 1, 'P', 'D', 'en_US: Investments & Stocks\r\nit_IT: Investimenti finanziari', 0),
(35, 17, 1, 2, '01.10', 1, 'P', 'D', 'en_US: Other Assets\r\nit_IT: Altre attività\r\n', 0),
(36, 17, 1, 2, '01.11', 1, 'P', 'D', 'en_US: Accrued Income\r\nit_IT: Ratei attivi\r\n', 0),
(37, 7, 1, 3, '02.01.SUPP02', 1, 'P', 'C', 'en_US: Supplier Example #2\r\nit_IT: Fornitore Esempio n. 2', 0),
(38, 2, 1, 2, '02.06', 1, 'P', 'C', 'en_US: Credit Cards\r\nit_IT: Debiti per Carte di Credito', 0),
(39, 2, 1, 2, '02.10', 1, 'P', 'C', 'en_US: Tax Payable\r\nit_IT: Debiti per imposte', 0),
(40, 2, 1, 2, '02.20', 1, 'P', 'C', 'en_US: Employment Expenses Payable\r\nit_IT: Debiti v/personale dipendente', 0),
(41, 2, 1, 2, '02.50', 1, 'P', 'C', 'en_US: Bank Loans\r\nit_IT: Mutui passivi', 0),
(42, 2, 1, 2, '02.70', 1, 'P', 'C', 'en_US: Accrued Expense\r\nit_IT: Ratei passivi\r\n', 0),
(43, 3, 1, 2, '03.50', 1, 'P', 'C', 'en_US: Retained Earnings\r\nit_IT: Riserve', 0),
(44, 3, 1, 2, '03.60', 1, 'P', 'C', 'en_US: Dividends\r\nit_IT: Dividendi', 0),
(45, 3, 1, 2, '03.70', 1, 'P', 'D', 'en_US: Drawings\r\nit_IT: Prelievi extra-gestione', 0),
(46, 16, 1, 2, '04.01', 1, 'E', 'C', 'en_US: Sales Revenue\r\nit_IT: Merci c/vendite', 0),
(47, 16, 1, 2, '04.02', 1, 'E', 'D', 'en_US: Sales Returns & Allowances\r\nit_IT: Abbuoni e ribassi passivi\r\n', 0),
(48, 16, 1, 2, '04.03', 1, 'E', 'D', 'en_US: Sales Discounts\r\nit_IT: Sconti sulle vendite', 0),
(49, 16, 1, 2, '04.20', 1, 'E', 'C', 'en_US: Interest Income\r\nit_IT: Interessi attivi', 0),
(50, NULL, 1, 1, '05', 0, 'E', NULL, 'en_US: Cost of Goods Sold\r\nit_IT: Costo del venduto', 2),
(51, 50, 1, 2, '05.01', 0, 'E', NULL, 'en_US: Purchases\r\nit_IT: Acquisti', 3),
(52, 51, 1, 3, '05.01.01', 1, 'E', 'D', 'en_US: Beginning Inventory\r\nit_IT: Merci c/rimanenze iniziali', 0),
(53, 51, 1, 3, '05.01.02', 1, 'E', 'D', 'en_US: Purchases\r\nit_IT: Merci c/acquisti', 0),
(54, 51, 1, 3, '05.01.03', 1, 'E', 'C', 'en_US: Ending Inventory\r\nit_IT: Merci c/rimanenze finali', 0),
(55, 50, 1, 2, '05.02', 1, 'E', 'C', 'en_US: Purchase Returns & Allowances\r\nit_IT: Abbuoni e ribassi attivi\r\n', 0),
(56, NULL, 1, 1, '06', 0, 'E', NULL, 'en_US: Expense\r\nit_IT: Spese', 19),
(57, 56, 1, 2, '06.01', 1, 'E', 'D', 'en_US: Advertising Expense\r\nit_IT: Spese di pubblicità', 0),
(58, 56, 1, 2, '06.05', 1, 'E', 'D', 'en_US: Bank Fees\r\nit_IT: Oneri bancari', 0),
(59, 56, 1, 2, '06.06', 1, 'E', 'D', 'en_US: Audit Fees\r\nit_IT: Spese per audit esterno', 0),
(60, 56, 1, 2, '06.10', 1, 'E', 'D', 'en_US: Client Expense\r\nit_IT: Spese commerciali\r\n', 0),
(61, 56, 1, 2, '06.20', 1, 'E', 'D', 'en_US: Depreciation Expense\r\nit_IT: Ammortamenti', 0),
(62, 56, 1, 2, '06.30', 1, 'E', 'D', 'en_US: Training Expense\r\nit_IT: Spese per formazione', 0),
(63, 56, 1, 2, '06.40', 1, 'E', 'D', 'en_US: Payroll Expense\r\nit_IT: Spese per il personale', 0),
(64, 56, 1, 2, '06.45', 1, 'E', 'D', 'en_US: Sales & Dist. Expense\r\nit_IT: Spese per vendita e distribuzione', 0),
(65, 56, 1, 2, '06.46', 1, 'E', 'D', 'en_US: Rental Expense\r\nit_IT: Fitti passivi', 0),
(66, 56, 1, 2, '06.50', 1, 'E', 'D', 'en_US: Income Tax Expense\r\nit_IT: Imposte e tasse', 0),
(67, 56, 1, 2, '06.55', 1, 'E', 'D', 'en_US: Information Technology Expense\r\nit_IT: Spese per strumentazione informatica', 0),
(69, 56, 1, 2, '06.60', 1, 'E', 'D', 'en_US: Insurance Expense\r\nit_IT: Spese per assicurazione', 0),
(70, 56, 1, 2, '06.70', 1, 'E', 'D', 'en_US: Office Expense\r\nit_IT: Spese per ufficio', 0),
(73, 56, 1, 2, '06.75', 1, 'E', 'D', 'en_US: Utilities Expense\r\nit_IT: Spese per servizi', 0),
(74, 56, 1, 2, '06.77', 1, 'E', 'D', 'en_US: Maintenance - Vehicle\r\nit_IT: Spese per manutenzione automezzi', 0),
(75, 56, 1, 2, '06.85', 1, 'E', 'D', 'en_US: Legal Expense\r\nit_IT: Spese legali', 0),
(76, 56, 1, 2, '06.90', 1, 'E', 'D', 'en_US: Personnel Benefits'' Expenses\r\nit_IT: Spese legate al personale', 0),
(77, 56, 1, 2, '06.95', 1, 'E', 'D', 'en_US: Communication Expense\r\nit_IT: Spese di comunicazione', 0),
(78, 56, 1, 2, '06.96', 1, 'E', 'D', 'en_US: Travelling & Conveyance\r\nit_IT: Spese per viaggi e trasporti', 0),
(79, NULL, 1, 1, 'Z01', 1, 'p', NULL, 'en_US: Closing financial balance\r\nit_IT: Stato patrimoniale finale\r\n', 0),
(80, NULL, 1, 1, 'Z02', 1, 'e', NULL, 'it_IT: Conto economico finale\r\nen_US: Closing Economic Balance\r\n', 0),
(81, NULL, 1, 1, 'Z11', 1, 'r', 'C', 'en_US: Net profit\r\nit_IT: Utile di esercizio\r\n', 0),
(82, NULL, 1, 1, 'Z12', 1, 'r', 'D', 'en_US: Total loss\r\nit_IT: Perdita di esercizio', 0);

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
(2, 1, 'Liabilities'),
(2, 2, 'Passività'),
(3, 1, 'Stockholders'' Equity Accounts'),
(3, 2, 'Capitale netto'),
(5, 1, 'Bank Accounts'),
(5, 2, 'C/C bancari'),
(6, 1, 'Accounts Receivable'),
(6, 2, 'Crediti v/clienti'),
(7, 1, 'Accounts Payable'),
(7, 2, 'Debiti v/fornitori'),
(8, 1, 'Common Stock'),
(8, 2, 'Capitale sociale'),
(16, 1, 'Revenues'),
(16, 2, 'Ricavi'),
(17, 1, 'Assets'),
(17, 2, 'Attività'),
(18, 1, 'Cash'),
(18, 2, 'Cassa'),
(20, 1, 'Supplier Example #1'),
(20, 2, 'Fornitore Esempio n. 1'),
(21, 2, 'Prova uno'),
(22, 2, 'prova due'),
(23, 2, 'Con un bel nome Ciao'),
(24, 1, 'fghdfhdfh'),
(24, 2, 'ghdfgh'),
(25, 1, 'Customer Example #1'),
(25, 2, 'Cliente Esempio n. 1'),
(26, 1, 'Customer Example #2'),
(26, 2, 'Cliente Esempio n. 2'),
(27, 1, 'Prepaid Expenses'),
(27, 2, 'Risconti attivi'),
(28, 1, 'Inventory'),
(28, 2, 'Magazzino'),
(29, 1, 'Buildings'),
(29, 2, 'Fabbricati'),
(30, 1, 'Accumulated Depreciation on Buildings'),
(30, 2, 'Fondo ammortamento Fabbricati'),
(31, 1, 'Vehicles & Equipment'),
(31, 2, 'Automezzi e Attrezzature'),
(32, 1, 'Vehicles'),
(32, 2, 'Automezzi'),
(33, 1, 'Equipment'),
(33, 2, 'Attrezzature'),
(34, 1, 'Investments & Stocks'),
(34, 2, 'Investimenti finanziari'),
(35, 1, 'Other Assets'),
(35, 2, 'Altre attività'),
(36, 1, 'Accrued Income'),
(36, 2, 'Ratei attivi'),
(37, 1, 'Supplier Example #2'),
(37, 2, 'Fornitore Esempio n. 2'),
(38, 1, 'Credit Cards'),
(38, 2, 'Debiti per Carte di Credito'),
(39, 1, 'Tax Payable'),
(39, 2, 'Debiti per imposte'),
(40, 1, 'Employment Expenses Payable'),
(40, 2, 'Debiti v/personale dipendente'),
(41, 1, 'Bank Loans'),
(41, 2, 'Mutui passivi'),
(42, 1, 'Accrued Expense'),
(42, 2, 'Ratei passivi'),
(43, 1, 'Retained Earnings'),
(43, 2, 'Riserve'),
(44, 1, 'Dividends'),
(44, 2, 'Dividendi'),
(45, 1, 'Drawings'),
(45, 2, 'Prelievi extra-gestione'),
(46, 1, 'Sales Revenue'),
(46, 2, 'Merci c/vendite'),
(47, 1, 'Sales Returns & Allowances'),
(47, 2, 'Abbuoni e ribassi passivi'),
(48, 1, 'Sales Discounts'),
(48, 2, 'Sconti sulle vendite'),
(49, 1, 'Interest Income'),
(49, 2, 'Interessi attivi'),
(50, 1, 'Cost of Goods Sold'),
(50, 2, 'Costo del venduto'),
(51, 1, 'Purchases'),
(51, 2, 'Acquisti'),
(52, 1, 'Beginning Inventory'),
(52, 2, 'Merci c/rimanenze iniziali'),
(53, 1, 'Purchases'),
(53, 2, 'Merci c/acquisti'),
(54, 1, 'Ending Inventory'),
(54, 2, 'Merci c/rimanenze finali'),
(55, 1, 'Purchase Returns & Allowances'),
(55, 2, 'Abbuoni e ribassi attivi'),
(56, 1, 'Expense'),
(56, 2, 'Spese'),
(57, 1, 'Advertising Expense'),
(57, 2, 'Spese di pubblicità'),
(58, 1, 'Bank Fees'),
(58, 2, 'Oneri bancari'),
(59, 1, 'Audit Fees'),
(59, 2, 'Spese per audit esterno'),
(60, 1, 'Client Expense'),
(60, 2, 'Spese commerciali'),
(61, 1, 'Depreciation Expense'),
(61, 2, 'Ammortamenti'),
(62, 1, 'Training Expense'),
(62, 2, 'Spese per formazione'),
(63, 1, 'Payroll Expense'),
(63, 2, 'Spese per il personale'),
(64, 1, 'Sales & Dist. Expense'),
(64, 2, 'Spese per vendita e distribuzione'),
(65, 1, 'Rental Expense'),
(65, 2, 'Fitti passivi'),
(66, 1, 'Income Tax Expense'),
(66, 2, 'Imposte e tasse'),
(67, 1, 'Information Technology Expense'),
(67, 2, 'Spese per strumentazione informatica'),
(69, 1, 'Insurance Expense'),
(69, 2, 'Spese per assicurazione'),
(70, 1, 'Office Expense'),
(70, 2, 'Spese per ufficio'),
(73, 1, 'Utilities Expense'),
(73, 2, 'Spese per servizi'),
(74, 1, 'Maintenance - Vehicle'),
(74, 2, 'Spese per manutenzione automezzi'),
(75, 1, 'Legal Expense'),
(75, 2, 'Spese legali'),
(76, 1, 'Personnel Benefits'' Expenses'),
(76, 2, 'Spese legate al personale'),
(77, 1, 'Communication Expense'),
(77, 2, 'Spese di comunicazione'),
(78, 1, 'Travelling & Conveyance'),
(78, 2, 'Spese per viaggi e trasporti'),
(79, 1, 'Closing financial balance'),
(79, 2, 'Stato patrimoniale finale'),
(80, 1, 'Closing Economic Balance'),
(80, 2, 'Conto economico finale'),
(81, 1, 'Net profit'),
(81, 2, 'Utile di esercizio'),
(82, 1, 'Total loss'),
(82, 2, 'Perdita di esercizio');

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_posting`
--

CREATE TABLE IF NOT EXISTS `tbl_posting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'positive if Debit, negative if Credit',
  `rank` int(11) NOT NULL,
  `comment` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `postrank` (`post_id`,`rank`),
  KEY `account_id` (`account_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=45 ;


--
-- Dump dei dati per la tabella `tbl_posting`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_firm`
--

CREATE TABLE IF NOT EXISTS `tbl_firm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(32) CHARACTER SET utf8 NOT NULL,
  `description` text COLLATE utf8_bin,
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

INSERT INTO `tbl_firm` (`id`, `name`, `slug`, `description`, `status`, `currency`, `csymbol`, `language_id`, `firm_parent_id`, `create_date`) VALUES
(1, 'Test One Inc.', 'test-one', 'Sample firm with a simple Chart of Accounts (http://en.wikipedia.org/wiki/Chart_of_accounts)', 1, 'USD', '$', 2, 0, '0000-00-00 00:00:00'),
(2, 'Test Two Inc.', 'test-two', NULL, 0, 'USD', '$', 1, 0, '0000-00-00 00:00:00'),
(3, 'Test Three Ltd', 'test-three', NULL, 0, 'GBP', '£', 1, 0, '0000-00-00 00:00:00'),
(4, 'Test Four SpA', 'test-four', NULL, 0, 'EUR', '€', 2, 0, '0000-00-00 00:00:00');

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
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `firm_rank` (`firm_id`,`rank`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=29 ;

--
-- Dump dei dati per la tabella `tbl_post`
--


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
(2, 'abcdef', '5fe6ce280af32ba2816b0f0d4cff5e63', 'abcdef@example.com', '09a586d70c9a9004e6f567d367c000d2', 0, 1, '2013-02-24 13:46:18', '2013-03-10 09:48:12'),
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
-- Limiti per la tabella `tbl_posting`
--
ALTER TABLE `tbl_posting`
  ADD CONSTRAINT `tbl_posting_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `tbl_account` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_posting_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `tbl_post` (`id`) ON UPDATE CASCADE;

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
