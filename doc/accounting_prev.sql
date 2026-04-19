-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2016 at 02:08 PM
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
  `STUDENT_ID` int(11) NOT NULL,
  `TEACHER_ID` int(11) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=124 ;

--
-- Dumping data for table `account_name`
--

INSERT INTO `account_name` (`ACC_ID`, `ACC_NAME`, `STUDENT_ID`, `TEACHER_ID`, `ACC_HEAD_TYPE`, `GROUP_HEAD_NAME`, `OTHER_DETAILS`, `SUB_HEAD_ID`, `OPENING_BALANCE`, `OPENING_BALANCE_TYPE`, `CODE`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 'Current Assets', 0, 0, 'bs', 'Asset', '', 'Asset', 0, 'Debit', '120000', 1, '2016-10-26', 0, '0000-00-00', 7),
(2, 'Receivable', 0, 0, 'bs', 'Asset', '', 'Current Assets', 0, 'Debit', '120120', 1, '2016-10-26', 0, '0000-00-00', 7),
(3, 'Tuition Fees &amp; others student fees', 0, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120121', 1, '2016-10-26', 0, '0000-00-00', 7),
(4, 'Advance Receipts', 0, 0, 'bs', 'Asset', '', 'Current Assets', 0, 'Debit', '120130', 1, '2016-10-26', 0, '0000-00-00', 7),
(5, 'Advance Against Salary', 0, 0, 'bs', 'Asset', '', 'Current Assets', 0, 'Debit', '120137', 1, '2016-10-26', 1, '2016-10-27', 7),
(6, 'Cash in Hand', 0, 0, 'bs', 'Asset', '', 'Current Assets', 0, 'Debit', '120170', 1, '2016-10-26', 0, '0000-00-00', 7),
(7, 'Cash', 0, 0, 'bs', 'Asset', '', 'Cash in Hand', 0, 'Debit', '120171', 1, '2016-10-26', 0, '0000-00-00', 7),
(8, 'Cheque, DD, PO', 0, 0, 'bs', 'Asset', '', 'Cash in Hand', 0, 'Debit', '120172', 1, '2016-10-26', 0, '0000-00-00', 7),
(9, 'Patty Cash', 0, 0, 'bs', 'Asset', '', 'Cash in Hand', 0, 'Debit', '120173', 1, '2016-10-26', 0, '0000-00-00', 7),
(10, 'Current Liabilities', 0, 0, 'bs', 'Liability', '', 'Liability', 0, 'Credit', '220120', 1, '2016-10-26', 1, '2016-10-26', 7),
(11, 'Advance From Students', 0, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220124', 1, '2016-10-26', 0, '0000-00-00', 7),
(12, 'Advance Tuition Fees', 0, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220125', 1, '2016-10-26', 0, '0000-00-00', 7),
(13, 'Library Caution Money', 0, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220126', 1, '2016-10-26', 0, '0000-00-00', 7),
(14, 'Lab Caution Money', 0, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220127', 1, '2016-10-26', 0, '0000-00-00', 7),
(15, 'Academic Income', 0, 0, 'pl', 'Income', '', 'Income', 0, 'Credit', '310110', 1, '2016-10-26', 0, '0000-00-00', 7),
(16, 'Tuition/Course Fees', 0, 0, 'pl', 'Income', '', 'Academic Income', 0, 'Credit', '310112', 1, '2016-10-26', 0, '0000-00-00', 7),
(17, 'Laboratory Fees', 0, 0, 'pl', 'Income', '', 'Academic Income', 0, 'Credit', '310114', 1, '2016-10-26', 0, '0000-00-00', 7),
(18, 'Transcript / Certificates Fees', 0, 0, 'pl', 'Income', '', 'Academic Income', 0, 'Credit', '310115', 1, '2016-10-26', 0, '0000-00-00', 7),
(19, 'Late Fine', 0, 0, 'pl', 'Income', '', 'Academic Income', 0, 'Credit', '310117', 1, '2016-10-26', 0, '0000-00-00', 7),
(20, 'Convocation Fees', 0, 0, 'pl', 'Income', '', 'Academic Income', 0, 'Credit', '310118', 1, '2016-10-26', 1, '2016-10-26', 7),
(21, 'Hand book and Academic Material Sales', 0, 0, 'pl', 'Income', '', 'Academic Income', 0, 'Credit', '310119', 1, '2016-10-26', 0, '0000-00-00', 7),
(22, 'Admission Income (Sales of Forms)', 0, 0, 'pl', 'Income', '', 'Income', 0, 'Credit', '310130', 1, '2016-10-26', 0, '0000-00-00', 7),
(23, 'Admission Form Sale', 0, 0, 'pl', 'Income', '', 'Admission Income (Sales of Forms)', 0, 'Credit', '310131', 1, '2016-10-26', 0, '0000-00-00', 7),
(24, 'Non Academic Income', 0, 0, 'pl', 'Income', '', 'Income', 0, 'Credit', '310140', 1, '2016-10-26', 0, '0000-00-00', 7),
(25, 'Grants/ Donations (Local)', 0, 0, 'pl', 'Income', '', 'Non Academic Income', 0, 'Credit', '310141', 1, '2016-10-26', 0, '0000-00-00', 7),
(26, 'Grants/ Donations (Foreign)', 0, 0, 'pl', 'Income', '', 'Non Academic Income', 0, 'Credit', '310142', 1, '2016-10-26', 0, '0000-00-00', 7),
(27, 'Sales of Scrap, Paper Periodic &amp; Others', 0, 0, 'pl', 'Income', '', 'Non Academic Income', 0, 'Credit', '310149', 1, '2016-10-26', 0, '0000-00-00', 7),
(28, 'ACADEMIC EXPENSES', 0, 0, 'pl', 'Expense', '', 'Expense', 5000, 'Debit', '410100', 1, '2016-10-26', 1, '2016-11-15', 7),
(29, 'Salary &amp; Wages (Teachers &amp; Academic Staffs)', 0, 0, 'pl', 'Expense', '', 'ACADEMIC EXPENSES', 0, 'Debit', '410110', 1, '2016-10-26', 0, '0000-00-00', 7),
(30, 'Full time Teacher''s salary', 0, 0, 'pl', 'Expense', '', 'Salary &amp; Wages (Teachers &amp; Academic Staffs)', 0, 'Debit', '410111', 1, '2016-10-26', 1, '2016-10-27', 7),
(31, 'Full time Academic Staff''s salary', 0, 0, 'pl', 'Expense', '', 'Salary &amp; Wages (Teachers &amp; Academic Staffs)', 0, 'Debit', '410112', 1, '2016-10-26', 0, '0000-00-00', 7),
(32, 'Part time Teacher''s salary', 0, 0, 'pl', 'Expense', '', 'Salary &amp; Wages (Teachers &amp; Academic Staffs)', 0, 'Debit', '410113', 1, '2016-10-26', 0, '0000-00-00', 7),
(33, 'Allowances (Teachers &amp; Academic Staff)', 0, 0, 'pl', 'Expense', '', 'ACADEMIC EXPENSES', 0, 'Debit', '410120', 1, '2016-10-26', 0, '0000-00-00', 7),
(34, 'Eid Festival Bonus', 0, 0, 'pl', 'Expense', '', 'Allowances (Teachers &amp; Academic Staff)', 0, 'Debit', '410126', 1, '2016-10-26', 0, '0000-00-00', 7),
(35, 'General Expenses (Academic)', 0, 0, 'pl', 'Expense', '', 'ACADEMIC EXPENSES', 0, 'Debit', '410140', 1, '2016-10-26', 0, '0000-00-00', 7),
(36, 'Entertainment', 0, 0, 'pl', 'Expense', '', 'General Expenses (Academic)', 0, 'Debit', '410142', 1, '2016-10-26', 0, '0000-00-00', 7),
(37, 'National Festival Observation', 0, 0, 'pl', 'Expense', '', 'General Expenses (Academic)', 0, 'Debit', '410146', 1, '2016-10-26', 0, '0000-00-00', 7),
(38, 'Religious Festival Observation', 0, 0, 'pl', 'Expense', '', 'General Expenses (Academic)', 0, 'Debit', '410147', 1, '2016-10-26', 0, '0000-00-00', 7),
(39, 'Board, Banner, Stone laying Exp', 0, 0, 'pl', 'Expense', '', 'General Expenses (Academic)', 0, 'Debit', '410148', 1, '2016-10-26', 0, '0000-00-00', 7),
(40, 'Meeting / Sitting Allowance', 0, 0, 'pl', 'Expense', '', 'General Expenses (Academic)', 0, 'Debit', '410149', 1, '2016-10-26', 0, '0000-00-00', 7),
(41, 'Academic Materials', 0, 0, 'pl', 'Expense', '', 'ACADEMIC EXPENSES', 0, 'Debit', '410160', 1, '2016-10-26', 0, '0000-00-00', 7),
(42, 'Electrical Goods', 0, 0, 'pl', 'Expense', '', 'Academic Materials', 0, 'Debit', '410161', 1, '2016-10-26', 0, '0000-00-00', 7),
(43, 'Network Accessories', 0, 0, 'pl', 'Expense', '', 'Academic Materials', 0, 'Debit', '410162', 1, '2016-10-26', 0, '0000-00-00', 7),
(44, 'Syllabus Remuneration', 0, 0, 'pl', 'Expense', '', 'Academic Materials', 0, 'Debit', '410163', 1, '2016-10-26', 0, '0000-00-00', 7),
(45, 'ID Card', 0, 0, 'pl', 'Expense', '', 'Academic Materials', 0, 'Debit', '410164', 1, '2016-10-26', 0, '0000-00-00', 7),
(46, 'Miscellaneous Expenses', 0, 0, 'pl', 'Expense', '', 'Academic Materials', 0, 'Debit', '410165', 1, '2016-10-26', 0, '0000-00-00', 7),
(47, 'Laboratory Expenses (Materials &amp; Chemicals)', 0, 0, 'pl', 'Expense', '', 'ACADEMIC EXPENSES', 0, 'Debit', '410170', 1, '2016-10-26', 0, '0000-00-00', 7),
(48, 'Pharmacy Lab', 0, 0, 'pl', 'Expense', '', 'Laboratory Expenses (Materials &amp; Chemicals)', 0, 'Debit', '410172', 1, '2016-10-26', 0, '0000-00-00', 7),
(49, 'Computer Science Lab', 0, 0, 'pl', 'Expense', '', 'Laboratory Expenses (Materials &amp; Chemicals)', 0, 'Debit', '410173', 1, '2016-10-26', 0, '0000-00-00', 7),
(50, 'Printing &amp; Stationery', 0, 0, 'pl', 'Expense', '', 'ACADEMIC EXPENSES', 0, 'Debit', '410180', 1, '2016-10-26', 0, '0000-00-00', 7),
(51, 'Printing', 0, 0, 'pl', 'Expense', '', 'Printing &amp; Stationery', 0, 'Debit', '410181', 1, '2016-10-26', 0, '0000-00-00', 7),
(52, 'Stationery', 0, 0, 'pl', 'Expense', '', 'Printing &amp; Stationery', 0, 'Debit', '410182', 1, '2016-10-26', 0, '0000-00-00', 7),
(53, 'Photocopy', 0, 0, 'pl', 'Expense', '', 'Printing &amp; Stationery', 0, 'Debit', '410183', 1, '2016-10-26', 0, '0000-00-00', 7),
(54, 'Students Programs Expenses', 0, 0, 'pl', 'Expense', '', 'ACADEMIC EXPENSES', 0, 'Debit', '410190', 1, '2016-10-26', 0, '0000-00-00', 7),
(55, 'Orientation', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410191', 1, '2016-10-26', 0, '0000-00-00', 7),
(56, 'Cultural Program', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410192', 1, '2016-10-26', 0, '0000-00-00', 7),
(57, 'Debate competition', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410193', 1, '2016-10-26', 0, '0000-00-00', 7),
(58, 'Programming Contest', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410194', 1, '2016-10-26', 0, '0000-00-00', 7),
(59, 'Internship Expenses', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410195', 1, '2016-10-26', 0, '0000-00-00', 7),
(60, 'Study Tour', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410196', 1, '2016-10-26', 0, '0000-00-00', 7),
(61, 'entertainment &amp; other', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410197', 1, '2016-10-26', 0, '0000-00-00', 7),
(62, 'Convocation', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410198', 1, '2016-10-26', 0, '0000-00-00', 7),
(63, 'Picnic', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410199', 1, '2016-10-26', 0, '0000-00-00', 7),
(64, 'Sports &amp; Game', 0, 0, 'pl', 'Expense', '', 'Students Programs Expenses', 0, 'Debit', '410200', 1, '2016-10-26', 0, '0000-00-00', 7),
(66, 'ADMISSION EXPENSES', 0, 0, 'pl', 'Expense', '', 'ACADEMIC EXPENSES', 0, 'Debit', '410290', 1, '2016-10-26', 0, '0000-00-00', 7),
(67, 'Advertisement For Admission', 0, 0, 'pl', 'Expense', '', 'ADMISSION EXPENSES', 0, 'Debit', '410291', 1, '2016-10-26', 0, '0000-00-00', 7),
(68, 'Material Expenses', 0, 0, 'pl', 'Expense', '', 'ADMISSION EXPENSES', 0, 'Debit', '410293', 1, '2016-10-26', 0, '0000-00-00', 7),
(69, 'Stationery', 0, 0, 'pl', 'Expense', '', 'ADMISSION EXPENSES', 0, 'Debit', '410294', 1, '2016-10-26', 0, '0000-00-00', 7),
(70, 'Printing', 0, 0, 'pl', 'Expense', '', 'ADMISSION EXPENSES', 0, 'Debit', '410295', 1, '2016-10-26', 0, '0000-00-00', 7),
(71, 'NONACADEMIC EXPENSES', 0, 0, 'pl', 'Expense', '', 'Expense', 0, 'Debit', '420100', 1, '2016-10-26', 0, '0000-00-00', 7),
(72, 'Salary &amp; wages (Officer &amp; Staff)', 0, 0, 'pl', 'Expense', '', 'NONACADEMIC EXPENSES', 0, 'Debit', '420110', 1, '2016-10-26', 0, '0000-00-00', 7),
(73, 'Officer''s salary', 0, 0, 'pl', 'Expense', '', 'Salary &amp; wages (Officer &amp; Staff)', 0, 'Debit', '420113', 1, '2016-10-26', 0, '0000-00-00', 7),
(74, 'Staff''s salary', 0, 0, 'pl', 'Expense', '', 'Salary &amp; wages (Officer &amp; Staff)', 0, 'Debit', '420114', 1, '2016-10-26', 0, '0000-00-00', 7),
(75, 'Allowances (Officer &amp; Staff)', 0, 0, 'pl', 'Expense', '', 'NONACADEMIC EXPENSES', 0, 'Debit', '420120', 1, '2016-10-26', 0, '0000-00-00', 7),
(76, 'Eid Festival Bonus', 0, 0, 'pl', 'Expense', '', 'Allowances (Officer &amp; Staff)', 0, 'Debit', '420126', 1, '2016-10-26', 0, '0000-00-00', 7),
(77, 'Utility Expenses (Administrative Building)', 0, 0, 'pl', 'Expense', '', 'Printing', 0, 'Debit', '420130', 1, '2016-10-26', 0, '0000-00-00', 7),
(78, 'Electricity, Gas &amp; Water', 0, 0, 'pl', 'Expense', '', 'Utility Expenses (Administrative Building)', 0, 'Debit', '420131', 1, '2016-10-26', 0, '0000-00-00', 7),
(79, 'Electricity bill', 0, 0, 'pl', 'Expense', '', 'Utility Expenses (Administrative Building)', 0, 'Debit', '420132', 1, '2016-10-26', 0, '0000-00-00', 7),
(80, 'Gas bill', 0, 0, 'pl', 'Expense', '', 'Utility Expenses (Administrative Building)', 0, 'Debit', '420133', 1, '2016-10-26', 0, '0000-00-00', 7),
(81, 'Water bill', 0, 0, 'pl', 'Expense', '', 'Utility Expenses (Administrative Building)', 0, 'Debit', '420134', 1, '2016-10-26', 0, '0000-00-00', 7),
(83, 'Printing &amp; Stationary', 0, 0, 'pl', 'Expense', '', 'NONACADEMIC EXPENSES', 0, 'Debit', '420220', 1, '2016-10-26', 0, '0000-00-00', 7),
(84, 'Printing', 0, 0, 'pl', 'Expense', '', 'Printing &amp; Stationary', 0, 'Debit', '420221', 1, '2016-10-26', 0, '0000-00-00', 7),
(85, 'Stationary', 0, 0, 'pl', 'Expense', '', 'Printing &amp; Stationary', 0, 'Debit', '420222', 1, '2016-10-26', 0, '0000-00-00', 7),
(86, 'Photocopy', 0, 0, 'pl', 'Expense', '', 'Printing &amp; Stationary', 0, 'Debit', '420223', 1, '2016-10-26', 0, '0000-00-00', 7),
(87, 'ID Card', 0, 0, 'pl', 'Expense', '', 'Printing &amp; Stationary', 0, 'Debit', '420227', 1, '2016-10-26', 0, '0000-00-00', 7),
(88, 'Photo &amp; Frame', 0, 0, 'pl', 'Expense', '', 'Printing &amp; Stationary', 0, 'Debit', '420226', 1, '2016-10-26', 0, '0000-00-00', 7),
(89, 'Accounts Receivable (Others)', 0, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120123', 1, '2016-10-26', 0, '0000-00-00', 7),
(90, 'Advance Tuition Fees', 1, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220125', 1, '2016-10-26', 0, '0000-00-00', 7),
(91, 'Tuition Fees & others student fees', 1, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120121', 1, '2016-10-26', 0, '0000-00-00', 7),
(92, 'Advance Tuition Fees', 2, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220125', 1, '2016-10-26', 0, '0000-00-00', 7),
(93, 'Tuition Fees & others student fees', 2, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120121', 1, '2016-10-26', 0, '0000-00-00', 7),
(94, 'Advance Tuition Fees', 3, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220125', 1, '2016-10-26', 0, '0000-00-00', 7),
(95, 'Tuition Fees & others student fees', 3, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120121', 1, '2016-10-26', 0, '0000-00-00', 7),
(96, 'Advance Tuition Fees', 4, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220125', 1, '2016-10-26', 0, '0000-00-00', 7),
(97, 'Tuition Fees & others student fees', 4, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120121', 1, '2016-10-26', 0, '0000-00-00', 7),
(98, 'Advance Tuition Fees', 5, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220125', 1, '2016-10-26', 0, '0000-00-00', 7),
(99, 'Tuition Fees & others student fees', 5, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120121', 1, '2016-10-26', 0, '0000-00-00', 7),
(100, 'Advance Tuition Fees', 6, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220125', 1, '2016-10-26', 0, '0000-00-00', 7),
(101, 'Tuition Fees & others student fees', 6, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120121', 1, '2016-10-26', 0, '0000-00-00', 7),
(102, 'Advance Tuition Fees', 7, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220125', 1, '2016-10-26', 0, '0000-00-00', 7),
(103, 'Tuition Fees & others student fees', 7, 0, 'bs', 'Asset', '', 'Receivable', 0, 'Debit', '120121', 1, '2016-10-26', 0, '0000-00-00', 7),
(110, 'Salary Payable', 0, 1, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220153', 1, '2016-10-26', 0, '0000-00-00', 7),
(111, 'Full time Teacher''s salary', 0, 1, 'pl', 'Expense', '', 'Salary & Wages (Teachers & Academic Staffs)', 0, 'Debit', '410111', 1, '2016-10-26', 0, '0000-00-00', 7),
(112, 'Salary Payable', 0, 2, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220153', 1, '2016-10-26', 0, '0000-00-00', 7),
(113, 'Full time Teacher''s salary', 0, 2, 'pl', 'Expense', '', 'Salary & Wages (Teachers & Academic Staffs)', 0, 'Debit', '410111', 1, '2016-10-26', 0, '0000-00-00', 7),
(114, 'Salary Payable', 0, 3, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220153', 1, '2016-10-26', 0, '0000-00-00', 7),
(115, 'Full time Teacher''s salary', 0, 3, 'pl', 'Expense', '', 'Salary & Wages (Teachers & Academic Staffs)', 0, 'Debit', '410111', 1, '2016-10-26', 0, '0000-00-00', 7),
(116, 'Salary Payable', 0, 0, 'bs', 'Liability', '', 'Current Liabilities', 0, 'Credit', '220153', 1, '2016-10-26', 0, '0000-00-00', 7),
(117, 'Bank Accounts', 0, 0, 'bs', 'Asset', '', 'Current Assets', 0, 'Debit', '120180', 1, '2016-10-31', 0, '0000-00-00', 7),
(118, 'Asset', 0, 0, 'bs', 'Asset', '', '', 500000, 'Debit', '100000', 1, '2016-11-14', 0, '0000-00-00', 7),
(119, 'Expense', 0, 0, 'pl', 'Expense', '', '', 0, 'Debit', '400000', 1, '2016-11-14', 0, '0000-00-00', 7),
(120, 'Income', 0, 0, 'pl', 'Income', '', '', 0, 'Credit', '300000', 1, '2016-11-14', 0, '0000-00-00', 7),
(121, 'Liability', 0, 0, 'bs', 'Liability', '', '', 100000, 'Credit', '220000', 1, '2016-11-14', 0, '0000-00-00', 7),
(122, 'Capital', 0, 0, 'bs', 'Liability', '', 'Liability', 700000, 'Credit', '220001', 1, '2016-11-14', 0, '0000-00-00', 7),
(123, 'Loan from Bank', 0, 0, 'bs', 'Liability', '', 'Liability', 100000, 'Credit', '220112', 1, '2016-11-14', 0, '0000-00-00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `account_sub_head`
--

CREATE TABLE IF NOT EXISTS `account_sub_head` (
  `SUB_HEAD_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SUB_HEAD_NAME` varchar(200) NOT NULL,
  `GROUP_HEAD_NAME` varchar(20) NOT NULL,
  `SUB_HEAD_TYPE` varchar(10) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `CREATED_DATE` date NOT NULL,
  `UPDATED_BY` int(11) NOT NULL,
  `UPDATED_DATE` date NOT NULL,
  `STATUS` int(11) NOT NULL,
  PRIMARY KEY (`SUB_HEAD_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `account_sub_head`
--

INSERT INTO `account_sub_head` (`SUB_HEAD_ID`, `SUB_HEAD_NAME`, `GROUP_HEAD_NAME`, `SUB_HEAD_TYPE`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(2, 'Fixed Asset', 'Asset', 'bs', 1, '2016-10-15', 0, '0000-00-00', 7),
(3, 'A/C Receiveable', 'Asset', 'bs', 1, '2016-10-15', 0, '0000-00-00', 7),
(4, 'Advance Payment', 'Asset', 'bs', 1, '2016-10-15', 0, '0000-00-00', 7),
(5, 'Capital', 'Liability', 'bs', 1, '2016-10-15', 0, '0000-00-00', 7),
(6, 'A/C Payable', 'Liability', 'bs', 1, '2016-10-15', 1, '2016-10-16', 7),
(7, 'Cash &amp; Cash Equivelants', 'Asset', 'bs', 1, '2016-10-16', 0, '0000-00-00', 7),
(8, 'Stock', 'Asset', 'bs', 1, '2016-10-16', 0, '0000-00-00', 7),
(9, 'Long Term Liabilities', 'Liability', 'bs', 1, '2016-10-16', 0, '0000-00-00', 7),
(10, 'Liabilities Expense', 'Liability', 'bs', 1, '2016-10-16', 0, '0000-00-00', 7),
(11, 'Store Term Loans', 'Liability', 'bs', 1, '2016-10-16', 0, '0000-00-00', 7),
(12, 'Admission Fee', 'Income', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(13, 'Tuition Fee', 'Income', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(14, 'Other Income', 'Income', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(15, 'Salary', 'Expense', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(16, 'Utility Bills', 'Expense', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(17, 'Stationary', 'Expense', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(18, 'Entertainment', 'Expense', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(19, 'Conveyance', 'Expense', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(20, 'Other Expense', 'Expense', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7),
(21, 'Depreciation Expense', 'Expense', 'pl', 1, '2016-10-16', 0, '0000-00-00', 7);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `account_transaction`
--

INSERT INTO `account_transaction` (`ACC_DATA_ID`, `ACC_BADGE_ID`, `ACC_DATE`, `COMPANY_ID`, `DEBIT`, `CREDIT`, `ACC_CONTRA`, `ACC_TYPE`, `ACC_AMOUNT`, `MONTH`, `DESCRIPTION`, `VOUCHER_NO`, `PAID_TO`, `PAID_BY`, `VOUCHER_TYPE`, `VOUCHER_NOTE`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 1, '2016-11-10', 0, '120121', '', 'Y', 'journal', 500, 'November', 'hgrfyhrftghy', '', '', '', '', '', 1, '2016-11-19', 0, '0000-00-00', 7),
(2, 1, '2016-11-10', 0, '120120', '', 'Y', 'journal', 200, 'November', 'hgrfyhrftghy', '', '', '', '', '', 1, '2016-11-19', 0, '0000-00-00', 7),
(3, 1, '2016-11-10', 0, '310112', '', 'Y', 'journal', 200, 'November', 'hgrfyhrftghy', '', '', '', '', '', 1, '2016-11-19', 0, '0000-00-00', 7),
(4, 1, '2016-11-10', 0, '310115', '', 'Y', 'journal', 600, 'November', 'hgrfyhrftghy', '', '', '', '', '', 1, '2016-11-19', 0, '0000-00-00', 7),
(5, 1, '2016-11-10', 0, '310114', '', 'Y', 'journal', 300, 'November', 'hgrfyhrftghy', '', '', '', '', '', 1, '2016-11-19', 0, '0000-00-00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `CATEGORY_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CATEGORY_NAME` varchar(50) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `CREATED_DATE` date NOT NULL,
  `UPDATED_BY` int(11) NOT NULL,
  `UPDATED_DATE` int(11) NOT NULL,
  `STATUS` int(11) NOT NULL,
  PRIMARY KEY (`CATEGORY_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CATEGORY_ID`, `CATEGORY_NAME`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(8, 'Gallery', 1, '2016-05-04', 4, 2016, 7),
(9, 'Downloads', 1, '2016-05-04', 0, 0, 7),
(10, 'Videos', 1, '2016-05-20', 0, 0, 7),
(11, 'Page', 1, '2016-05-23', 0, 0, 7);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `general_ledger`
--

INSERT INTO `general_ledger` (`GL_ID`, `ACC_CODE`, `HEAD_TYPE`, `TRANSACTION_ID`, `OPENING_BALANCE`, `CLOSING_BALANCE`, `DEBIT`, `CREDIT`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 120121, 'bs', 1, 0, 0, 500, 0, 1, '2016-11-19', 0, '0000-00-00', 7),
(2, 120120, 'bs', 1, 0, 0, 200, 0, 1, '2016-11-19', 0, '0000-00-00', 7),
(3, 310112, 'bs', 1, 0, 0, 200, 0, 1, '2016-11-19', 0, '0000-00-00', 7),
(4, 310115, 'bs', 1, 0, 0, 0, 600, 1, '2016-11-19', 0, '0000-00-00', 7),
(5, 310114, 'bs', 1, 0, 0, 0, 300, 1, '2016-11-19', 0, '0000-00-00', 7);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=172 ;

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
(160, 'create_journal', NULL, 'accounts', 'create_student_payment', 'create_journal', 0, 0),
(161, 'manage_journal', NULL, 'accounts', 'manage_student_payment', 'manage_journal', 0, 0),
(163, 'ledger', NULL, 'accounts', 'ledger', 'ledger', 0, 0),
(164, 'trial_balance', NULL, 'accounts', 'trial_balance', 'trial_balance', 0, 0),
(165, 'balance_sheet', NULL, 'accounts', 'balance_sheet', 'balance_sheet', 0, 0),
(168, 'create_voucher', NULL, 'accounts', 'create_voucher', 'create_voucher', 0, 0),
(169, 'manage_voucher', NULL, 'accounts', 'manage_voucher', 'manage_voucher', 0, 0),
(170, 'create_company', NULL, 'company', 'create_company', 'create_company', 0, 1),
(171, 'manage_company', NULL, 'company', 'manage_company', 'manage_company', 0, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`ROLE_ID`, `ROLE_NAME`, `DETAILS`, `PERMISSION_NAME`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 'Super Admin', NULL, '', 0, '2015-10-03 17:01:53', 1, '2016-06-21 02:51:14', 7),
(2, 'Additional USer', NULL, 'add_additional_data,manage_additional_data,manage_user_data', 1, '2016-06-21 02:46:46', 1, '2016-06-21 02:51:12', 7),
(3, 'Website Controller', NULL, 'add_additional_data,add_downloads,add_images,add_person,create_category,create_page,create_slider,create_sub_category,create_sub_sub_category,manage_additional_data,manage_category,manage_downloads,manage_gallery,manage_pages,manage_person,manage_slider,manage_sub_category,manage_sub_sub_category,manage_user_data', 1, '2016-06-22 05:58:22', NULL, NULL, 7),
(4, 'Student', NULL, 'my_book_request,my_issues_and_returns,my_notifications,send_book_request,view_library_books,view_attendance,view_class_routine,view_mark_sheet,view_notice,view_payment,view_subjects,view_teachers', 1, '2016-07-12 05:07:39', 1, '2016-07-28 05:30:28', 7),
(5, 'Teacher', NULL, 'daily_attendance,manage_attendance,create_class_routine,manage_class_routine,insert_marks,manage_marks,attendance_report,class_wise_marksheet,section_wise_marksheet,student_marksheet,send_sms,manage_assign_subject,manage_assign_teacher,manage_teacher', 1, '2016-07-13 05:11:11', NULL, NULL, 7),
(6, 'Parents', NULL, 'view_attendance_parents,view_class_routine_parents,view_mark_sheet_parents,view_notice_parents,view_payment_parents,view_subjects_parents,view_teachers_parents', 1, '2016-07-13 06:16:23', 1, '2016-07-13 06:51:14', 7),
(7, 'Admin', NULL, 'daily_attendance,download_csv_file,manage_attendance,upload_csv_file,create_board,manage_board,create_testimonial,create_transfer_certificate,student_mark_sheet,student_report_card,create_class,create_class_routine,create_section,manage_class,manage_class_routine,manage_section,add_additional_data,add_downloads,add_images,add_person,create_category,create_page,create_slider,create_sub_category,create_sub_sub_category,manage_additional_data,manage_category,manage_downloads,manage_gallery,manage_pages,manage_person,manage_slider,manage_sub_category,manage_sub_sub_category,manage_user_data,create_exam,create_grade,insert_marks,manage_exam_list,manage_grade,manage_marks,add_house,admit_student_to_hostel,assign_house_teacher,manage_checkin,manage_hostel_student,manage_house,manage_house_teacher,student_checkin,add_book,Add_library_member,book_issue,create_book_category,create_writer,general_settings,manage_book,manage_book_category,manage_book_request,manage_issue_and_return,manage_library_member,manage_notification,manage_settings,manage_writer,send_notification,create_parent,manage_parent,create_payment,create_payment_category,manage_payment,manage_payment_category,add_leave,leave_report,leave_settings,manage_leave,manage_leave_settings,manage_salary,manage_salary_settings,monthly_salary,salary_report,salary_settings,attendance_report,average_marksheet,class_wise_marksheet,payment_report,section_wise_marksheet,student_marksheet,create_designation,create_event,create_notice,create_subject,manage_designation,manage_event,manage_notice,manage_subject,view_events,send_sms,create_staff,manage_staff,add_student_info,admit_student,manage_student_admission,manage_student_info,assign_subject,assign_teacher,create_teacher,manage_assign_subject,manage_assign_teacher,manage_teacher,create_parents,create_role,create_student,create_teacher,create_user,manage_role,manage_user', 1, '2016-07-14 05:24:24', 1, '2016-08-27 12:53:33', 7);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_ID`, `ROLE_ID`, `COMPANY_ID`, `USER_NAME`, `USER_EMAIL`, `USER_PHONE`, `USER_TYPE`, `USER_PASSWORD`, `USER_PASSWORD_HISTORY`, `CREATED_BY`, `CREATED_DATE`, `UPDATED_BY`, `UPDATED_DATE`, `STATUS`) VALUES
(1, 0, 1, 'Admin', 'admin@base4.com', '', 'admin', 'AvPzL4oU37R9-2KTQOqYgMLWTcsCmFG3U8jLemJx4V8', 'UM_nEepm0nfpmSqeEWbv9MxxAMDg3FnHKyczZlf2w30,RiGKhnYpUQL-m6HZtVB3fP0qmpu4LvF4RLUvG-o9hRI', 1, '2015-09-16 11:12:13', 1, '2017-04-12 11:36:06', 9),
(9, 7, NULL, 'Admin', 'admin@base4bd.com', '', 'admin', 'AvPzL4oU37R9-2KTQOqYgMLWTcsCmFG3U8jLemJx4V8', NULL, 1, '2016-07-11 00:00:00', 9, '2016-08-13 06:02:46', 7);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
