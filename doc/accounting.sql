-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2016 at 03:00 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `accounting`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_name`
--

CREATE TABLE IF NOT EXISTS `account_name` (
  `ACC_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ACC_NAME` varchar(200) NOT NULL,
  `COMPANY_ID` int(11) NOT NULL,
  `ACC_HEAD_TYPE` enum('bs','tr','pl') NOT NULL,
  `GROUP_HEAD_NAME` enum('Asset','Liability','Debit','Credit','Income','Expense') NOT NULL,
  `OTHER_DETAILS` text NOT NULL,
  `SUB_HEAD_ID` varchar(100) NOT NULL,
  `OPENING_BALANCE` bigint(15) NOT NULL,
  `OPENING_BALANCE_TYPE` enum('Debit','Credit') NOT NULL,
  `CODE` varchar(20) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `CREATED_DATE` date NOT NULL,
  `UPDATED_BY` int(11) NOT NULL,
  `UPDATED_DATE` date NOT NULL,
  `STATUS` int(11) NOT NULL,
  PRIMARY KEY (`ACC_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `account_name`
--

INSERT INTO `account_name` (`ACC_ID`, `ACC_NAME`, `COMPANY_ID`, `ACC_HEAD_TYPE`, `GROUP_HEAD_NAME`, `OTHER_DETAILS`, `SUB_HEAD_ID`, `OPENING_BALANCE`, `OPENING_BALANCE_TYPE`, `CODE`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(2, 'Cash', 2, 'bs', 'Asset', '', '', 50000, 'Debit', '123456', 10, '2016-11-20', 0, '0000-00-00', 7),
(3, 'Purchase', 2, 'pl', 'Expense', '', '', 20000, 'Debit', '432156', 10, '2016-11-20', 0, '0000-00-00', 7),
(4, 'Capital', 2, 'bs', 'Liability', '', '', 70000, 'Credit', '542311', 10, '2016-11-20', 0, '0000-00-00', 7),
(5, 'Bank Accounts', 2, 'bs', 'Asset', '', '', 0, '', '112233', 10, '2016-11-20', 0, '0000-00-00', 7),
(6, 'Machinery', 2, 'bs', 'Asset', '', '', 0, 'Debit', '212121', 10, '2016-11-20', 0, '0000-00-00', 7),
(7, 'A/C Payable', 2, 'bs', 'Liability', '', '', 0, 'Credit', '211231', 10, '2016-11-20', 0, '0000-00-00', 7),
(8, 'Advanced Rent', 2, 'bs', 'Asset', '', '', 0, 'Debit', '213342', 10, '2016-11-20', 0, '0000-00-00', 7),
(9, 'Advertisement Expense', 2, 'pl', 'Expense', '', '', 0, 'Debit', '121121', 10, '2016-11-20', 0, '0000-00-00', 7),
(10, 'Owners Drawing', 2, 'bs', 'Liability', '', '', 0, 'Debit', '121233', 10, '2016-11-20', 0, '0000-00-00', 7),
(11, 'Salary &amp; wages (Officer &amp; Staff)', 2, 'pl', 'Expense', '', '', 0, 'Debit', '322411', 10, '2016-11-20', 0, '0000-00-00', 7),
(12, 'Unpaid Salary', 2, 'bs', 'Liability', '', '', 0, 'Credit', '897688', 10, '2016-11-20', 0, '0000-00-00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `account_transaction`
--

CREATE TABLE IF NOT EXISTS `account_transaction` (
  `ACC_DATA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ACC_BADGE_ID` int(11) NOT NULL,
  `ACC_DATE` date NOT NULL,
  `COMPANY_ID` int(11) NOT NULL,
  `DEBIT` varchar(50) NOT NULL,
  `CREDIT` varchar(50) NOT NULL,
  `ACC_CONTRA` enum('Y','N') NOT NULL,
  `ACC_TYPE` varchar(100) NOT NULL,
  `ACC_AMOUNT` int(11) NOT NULL,
  `MONTH` varchar(20) DEFAULT NULL,
  `DESCRIPTION` text NOT NULL,
  `VOUCHER_NO` varchar(100) NOT NULL,
  `PAID_TO` varchar(100) NOT NULL,
  `PAID_BY` varchar(100) NOT NULL,
  `VOUCHER_TYPE` varchar(100) NOT NULL,
  `VOUCHER_NOTE` text NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `CREATED_DATE` date NOT NULL,
  `UPDATED_BY` int(11) NOT NULL,
  `UPDATED_DATE` date NOT NULL,
  `STATUS` int(11) NOT NULL,
  PRIMARY KEY (`ACC_DATA_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `account_transaction`
--

INSERT INTO `account_transaction` (`ACC_DATA_ID`, `ACC_BADGE_ID`, `ACC_DATE`, `COMPANY_ID`, `DEBIT`, `CREDIT`, `ACC_CONTRA`, `ACC_TYPE`, `ACC_AMOUNT`, `MONTH`, `DESCRIPTION`, `VOUCHER_NO`, `PAID_TO`, `PAID_BY`, `VOUCHER_TYPE`, `VOUCHER_NOTE`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 1, '2016-11-02', 2, '112233', '', 'Y', 'journal', 20000, 'November', 'open new bank account', '', '', '', '', '', 10, '2016-11-20', 0, '0000-00-00', 7),
(2, 1, '2016-11-02', 2, '123456', '', 'Y', 'journal', 20000, 'November', 'open new bank account', '', '', '', '', '', 10, '2016-11-20', 0, '0000-00-00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `COMPANY_ID` int(11) NOT NULL AUTO_INCREMENT,
  `COMPANY_NAME` varchar(100) NOT NULL,
  `COMPANY_DESCRIPTION` text NOT NULL,
  `COMPANY_PHONE` varchar(20) NOT NULL,
  `COMPANY_EMAIL` varchar(100) NOT NULL,
  `COMPANY_ADDRESS` varchar(400) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `CREATED_DATE` date NOT NULL,
  `UPDATED_BY` int(11) NOT NULL,
  `UPDATED_DATE` date NOT NULL,
  `STATUS` tinyint(2) NOT NULL,
  PRIMARY KEY (`COMPANY_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`COMPANY_ID`, `COMPANY_NAME`, `COMPANY_DESCRIPTION`, `COMPANY_PHONE`, `COMPANY_EMAIL`, `COMPANY_ADDRESS`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 'Base4', '', '', '', '', 1, '2016-11-19', 1, '2016-11-19', 7),
(2, 'e-Shopper', '', '', '', '', 1, '2016-11-19', 0, '0000-00-00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `general_ledger`
--

CREATE TABLE IF NOT EXISTS `general_ledger` (
  `GL_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ACC_CODE` int(11) NOT NULL,
  `HEAD_TYPE` enum('bs','tr','pl') NOT NULL,
  `TRANSACTION_ID` int(11) NOT NULL,
  `COMPANY_ID` int(11) NOT NULL,
  `OPENING_BALANCE` int(11) NOT NULL,
  `CLOSING_BALANCE` int(11) NOT NULL,
  `DEBIT` int(11) NOT NULL,
  `CREDIT` int(11) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `CREATED_DATE` date NOT NULL,
  `UPDATED_BY` int(11) NOT NULL,
  `UPDATED_DATE` date NOT NULL,
  `STATUS` int(11) NOT NULL,
  PRIMARY KEY (`GL_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `general_ledger`
--

INSERT INTO `general_ledger` (`GL_ID`, `ACC_CODE`, `HEAD_TYPE`, `TRANSACTION_ID`, `COMPANY_ID`, `OPENING_BALANCE`, `CLOSING_BALANCE`, `DEBIT`, `CREDIT`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(2, 123456, 'bs', 0, 2, 0, 0, 50000, 0, 10, '2016-11-20', 0, '0000-00-00', 7),
(3, 432156, 'pl', 0, 2, 0, 0, 20000, 0, 10, '2016-11-20', 0, '0000-00-00', 7),
(4, 542311, 'bs', 0, 2, 0, 0, 0, 70000, 10, '2016-11-20', 0, '0000-00-00', 7),
(5, 112233, 'bs', 1, 2, 0, 0, 20000, 0, 10, '2016-11-20', 0, '0000-00-00', 7),
(6, 123456, 'bs', 1, 2, 0, 0, 0, 20000, 10, '2016-11-20', 0, '0000-00-00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `PERMISSION_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PERMISSION_NAME` varchar(100) NOT NULL COMMENT 'example: create order, edit PI, Create User etc',
  `DETAILS` varchar(250) DEFAULT NULL,
  `GROUP_NAME` varchar(200) DEFAULT NULL,
  `MENU_NAME` varchar(50) NOT NULL,
  `ROUTE_NAME` varchar(100) NOT NULL,
  `PARENT_ID` int(11) NOT NULL,
  `STATUS` tinyint(4) NOT NULL COMMENT '1=Pending | 2=Approved | 3=Resolved | 4=Forwarded  | 5=Deployed  | 6=New  | 7=Active  | 8=Initiated  | 9=On Progress  | 10=Delivered  | -2=Declined | -3=Canceled | -5=Taking out | -6=Renewed/Replaced | -7=Inactive',
  PRIMARY KEY (`PERMISSION_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=177 ;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`PERMISSION_ID`, `PERMISSION_NAME`, `DETAILS`, `GROUP_NAME`, `MENU_NAME`, `ROUTE_NAME`, `PARENT_ID`, `STATUS`) VALUES
(74, 'create_user', NULL, 'user', 'create_user', 'create_user', 0, 1),
(75, 'manage_user', NULL, 'user', 'manage_user', 'manage_user', 0, 1),
(76, 'create_role', NULL, 'user', 'create_role', 'create_role', 0, 1),
(77, 'manage_role', NULL, 'user', 'manage_role', 'manage_role', 0, 1),
(158, 'create_account_head', NULL, 'accounts', 'create_account_head', 'create_account_head', 0, 0),
(159, 'manage_account_head', NULL, 'accounts', 'manage_account_head', 'manage_account_head', 0, 0),
(160, 'create_journal', NULL, 'accounts', 'create_journal', 'create_journal', 0, 0),
(161, 'manage_journal', NULL, 'accounts', 'manage_journal', 'manage_journal', 0, 0),
(163, 'ledger', NULL, 'accounts', 'ledger', 'ledger', 0, 0),
(164, 'trial_balance', NULL, 'accounts', 'trial_balance', 'trial_balance', 0, 0),
(165, 'balance_sheet', NULL, 'accounts', 'balance_sheet', 'balance_sheet', 0, 0),
(168, 'create_voucher', NULL, 'accounts', 'create_debit_voucher', 'create_voucher', 0, 0),
(169, 'manage_voucher', NULL, 'accounts', 'manage_debit_voucher', 'manage_voucher', 0, 0),
(170, 'create_company', NULL, 'company', 'create_company', 'create_company', 0, 1),
(171, 'manage_company', NULL, 'company', 'manage_company', 'manage_company', 0, 1),
(172, 'create_credit_voucher', NULL, 'accounts', 'create_credit_voucher', 'create_credit_voucher', 0, 0),
(173, 'manage_credit_voucher', NULL, 'accounts', 'manage_credit_voucher', 'manage_credit_voucher', 0, 0),
(176, 'journal', NULL, 'accounts', 'journal_report', 'journal', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `ROLE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ROLE_NAME` varchar(100) NOT NULL,
  `DETAILS` varchar(255) DEFAULT NULL,
  `PERMISSION_NAME` text NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `CREATED_DATE` datetime NOT NULL,
  `UPDATED_BY` int(11) DEFAULT NULL,
  `UPDATED_DATE` datetime DEFAULT NULL,
  `STATUS` tinyint(4) NOT NULL,
  PRIMARY KEY (`ROLE_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`ROLE_ID`, `ROLE_NAME`, `DETAILS`, `PERMISSION_NAME`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 'Super Admin', NULL, '', 0, '2015-10-03 17:01:53', 1, '2016-06-21 02:51:14', 7),
(8, 'Company Admin', NULL, 'balance_sheet,create_account_head,create_credit_voucher,create_journal,create_voucher,journal,ledger,manage_account_head,manage_credit_voucher,manage_journal,manage_voucher,trial_balance', 1, '2016-11-20 01:09:40', 1, '2016-11-20 05:54:50', 7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ROLE_ID` int(11) DEFAULT NULL,
  `COMPANY_ID` int(11) DEFAULT NULL,
  `USER_NAME` varchar(50) NOT NULL,
  `USER_EMAIL` varchar(50) NOT NULL,
  `USER_PHONE` varchar(50) NOT NULL,
  `USER_TYPE` varchar(50) NOT NULL,
  `USER_PASSWORD` varchar(50) NOT NULL,
  `USER_PASSWORD_HISTORY` varchar(250) DEFAULT NULL,
  `CREATED_BY` int(11) DEFAULT NULL,
  `CREATED_DATE` datetime DEFAULT NULL,
  `UPDATED_BY` int(11) DEFAULT NULL,
  `UPDATED_DATE` datetime DEFAULT NULL,
  `STATUS` tinyint(4) NOT NULL COMMENT '1=active | -1=inactive',
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_ID`, `ROLE_ID`, `COMPANY_ID`, `USER_NAME`, `USER_EMAIL`, `USER_PHONE`, `USER_TYPE`, `USER_PASSWORD`, `USER_PASSWORD_HISTORY`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 0, 0, 'Admin', 'admin@base4.com', '', 'admin', 'AvPzL4oU37R9-2KTQOqYgMLWTcsCmFG3U8jLemJx4V8', 'UM_nEepm0nfpmSqeEWbv9MxxAMDg3FnHKyczZlf2w30,RiGKhnYpUQL-m6HZtVB3fP0qmpu4LvF4RLUvG-o9hRI', 1, '2015-09-16 11:12:13', 1, '2017-04-12 11:36:06', 9),
(10, 8, 2, 'Hasan Mahmud', 'hasan.techworld@gmail.com', '+8801919998554', '', '3rGyHZhjwFlalNLksfFgM88Jd173z-2soIyrS3zEnbI', NULL, 1, '2016-11-20 02:08:05', NULL, NULL, 7),
(11, 8, 1, 'Rakib', 'mh.mahmud.me@gmail.com', '+8801919998554', '', 'Es0dec04dVcoB-tLpmVjfzo1lr81Z4stx5_xpb1TQXY', NULL, 1, '2016-11-20 02:32:58', NULL, NULL, 7);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
