-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 22, 2026 at 10:06 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `term_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

CREATE TABLE `Account` (
  `AccountNumber` int(11) NOT NULL,
  `LoginName` varchar(50) NOT NULL,
  `Balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `AccountName` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Account`
--

INSERT INTO `Account` (`AccountNumber`, `LoginName`, `Balance`, `AccountName`) VALUES
(27, 'jsmith', 2271.69, 'Checking'),
(28, 'jsmith', 7857.82, 'Savings'),
(29, 'jsmith', 270.26, 'Cash'),
(30, 'mjohnson', 1540.86, 'Checking'),
(31, 'mjohnson', 5034.86, 'Savings'),
(32, 'mjohnson', 54.50, 'Cash'),
(33, 'rwilliams', 2942.40, 'Checking'),
(34, 'rwilliams', 11715.30, 'Savings'),
(35, 'rwilliams', 409.81, 'Cash'),
(36, 'pbrown', 762.91, 'Checking'),
(37, 'pbrown', 2999.30, 'Savings'),
(38, 'pbrown', 22.00, 'Cash'),
(39, 'djones', 3778.56, 'Checking'),
(40, 'djones', 9266.06, 'Savings'),
(41, 'djones', 116.51, 'Cash'),
(42, 'lmiller', 1346.81, 'Checking'),
(43, 'lmiller', 6610.40, 'Savings'),
(44, 'lmiller', 332.61, 'Cash'),
(45, 'mdavis', 2644.66, 'Checking'),
(46, 'mdavis', 7231.36, 'Savings'),
(47, 'mdavis', 98.01, 'Cash'),
(48, 'bwilson', 916.26, 'Checking'),
(49, 'bwilson', 4157.60, 'Savings'),
(50, 'bwilson', 34.75, 'Cash'),
(51, 'rmoore', 5049.60, 'Checking'),
(52, 'rmoore', 14736.86, 'Savings'),
(53, 'rmoore', 457.51, 'Cash'),
(54, 'dtaylor', 2034.81, 'Checking'),
(55, 'dtaylor', 8708.35, 'Savings'),
(56, 'dtaylor', 220.51, 'Cash'),
(57, 'janderson', 3516.11, 'Checking'),
(58, 'janderson', 10773.61, 'Savings'),
(59, 'janderson', 340.26, 'Cash'),
(60, 'mthomas', 1192.41, 'Checking'),
(61, 'mthomas', 2568.45, 'Savings'),
(62, 'mthomas', 15.00, 'Cash'),
(63, 'cjackson', 4268.40, 'Checking'),
(64, 'cjackson', 13251.11, 'Savings'),
(65, 'cjackson', 598.21, 'Cash'),
(66, 'hwhite', 1933.61, 'Checking'),
(67, 'hwhite', 5901.60, 'Savings'),
(68, 'hwhite', 120.61, 'Cash'),
(69, 'dharris', 3129.21, 'Checking'),
(70, 'dharris', 8988.51, 'Savings'),
(71, 'dharris', 282.41, 'Cash'),
(72, 'bmartin', 703.61, 'Checking'),
(73, 'bmartin', 1780.50, 'Savings'),
(74, 'bmartin', 6.00, 'Cash'),
(75, 'mthompson', 5706.40, 'Checking'),
(76, 'mthompson', 17708.61, 'Savings'),
(77, 'mthompson', 735.21, 'Cash'),
(78, 'sgarcia', 1545.71, 'Checking'),
(79, 'sgarcia', 5314.40, 'Savings'),
(80, 'sgarcia', 59.61, 'Cash'),
(81, 'wmartinez', 4071.21, 'Checking'),
(82, 'wmartinez', 10266.76, 'Savings'),
(83, 'wmartinez', 395.11, 'Cash'),
(84, 'drobinson', 2423.91, 'Checking'),
(85, 'drobinson', 7594.90, 'Savings'),
(86, 'drobinson', 167.11, 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `Budget`
--

CREATE TABLE `Budget` (
  `BudgetID` int(11) NOT NULL,
  `AccountNumber` int(11) NOT NULL,
  `Category` enum('Groceries','Transportation','Entertainment','Utilities','Food') NOT NULL,
  `Threshold` decimal(10,2) NOT NULL,
  `Frequency` enum('Weekly','Monthly') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Budget`
--

INSERT INTO `Budget` (`BudgetID`, `AccountNumber`, `Category`, `Threshold`, `Frequency`) VALUES
(10, 27, 'Groceries', 200.00, 'Monthly'),
(11, 27, 'Utilities', 150.00, 'Monthly'),
(12, 27, 'Transportation', 60.00, 'Weekly'),
(13, 28, 'Groceries', 250.00, 'Monthly'),
(14, 28, 'Food', 50.00, 'Weekly'),
(15, 28, 'Entertainment', 40.00, 'Monthly'),
(16, 29, 'Groceries', 80.00, 'Monthly'),
(17, 29, 'Transportation', 30.00, 'Weekly'),
(18, 30, 'Groceries', 180.00, 'Monthly'),
(19, 30, 'Utilities', 130.00, 'Monthly'),
(20, 30, 'Transportation', 70.00, 'Monthly'),
(21, 31, 'Groceries', 150.00, 'Monthly'),
(22, 31, 'Food', 40.00, 'Weekly'),
(23, 31, 'Entertainment', 30.00, 'Monthly'),
(24, 32, 'Groceries', 60.00, 'Monthly'),
(25, 32, 'Food', 20.00, 'Weekly'),
(26, 32, 'Transportation', 25.00, 'Monthly'),
(27, 33, 'Groceries', 220.00, 'Monthly'),
(28, 33, 'Utilities', 100.00, 'Monthly'),
(29, 33, 'Entertainment', 100.00, 'Monthly'),
(30, 34, 'Groceries', 300.00, 'Monthly'),
(31, 34, 'Utilities', 150.00, 'Monthly'),
(32, 34, 'Food', 50.00, 'Weekly'),
(33, 35, 'Groceries', 100.00, 'Monthly'),
(34, 35, 'Transportation', 30.00, 'Weekly'),
(35, 36, 'Groceries', 120.00, 'Monthly'),
(36, 36, 'Utilities', 100.00, 'Monthly'),
(37, 36, 'Entertainment', 20.00, 'Monthly'),
(38, 37, 'Groceries', 100.00, 'Monthly'),
(39, 37, 'Food', 30.00, 'Weekly'),
(40, 37, 'Utilities', 50.00, 'Monthly'),
(41, 38, 'Groceries', 40.00, 'Monthly'),
(42, 38, 'Food', 15.00, 'Weekly'),
(43, 39, 'Groceries', 200.00, 'Monthly'),
(44, 39, 'Utilities', 160.00, 'Monthly'),
(45, 39, 'Transportation', 80.00, 'Monthly'),
(46, 40, 'Groceries', 250.00, 'Monthly'),
(47, 40, 'Food', 60.00, 'Weekly'),
(48, 40, 'Entertainment', 50.00, 'Monthly'),
(49, 41, 'Groceries', 80.00, 'Monthly'),
(50, 41, 'Food', 30.00, 'Weekly'),
(51, 41, 'Transportation', 30.00, 'Monthly'),
(52, 42, 'Groceries', 150.00, 'Monthly'),
(53, 42, 'Utilities', 80.00, 'Monthly'),
(54, 42, 'Entertainment', 30.00, 'Monthly'),
(55, 43, 'Groceries', 180.00, 'Monthly'),
(56, 43, 'Food', 40.00, 'Weekly'),
(57, 43, 'Utilities', 120.00, 'Monthly'),
(58, 44, 'Groceries', 120.00, 'Monthly'),
(59, 44, 'Transportation', 30.00, 'Weekly'),
(60, 45, 'Groceries', 180.00, 'Monthly'),
(61, 45, 'Utilities', 130.00, 'Monthly'),
(62, 45, 'Transportation', 70.00, 'Monthly'),
(63, 46, 'Groceries', 150.00, 'Monthly'),
(64, 46, 'Food', 45.00, 'Weekly'),
(65, 46, 'Entertainment', 35.00, 'Monthly'),
(66, 47, 'Groceries', 80.00, 'Monthly'),
(67, 47, 'Transportation', 30.00, 'Weekly'),
(68, 47, 'Food', 20.00, 'Monthly'),
(69, 48, 'Groceries', 100.00, 'Monthly'),
(70, 48, 'Utilities', 80.00, 'Monthly'),
(71, 48, 'Entertainment', 25.00, 'Monthly'),
(72, 49, 'Groceries', 120.00, 'Monthly'),
(73, 49, 'Food', 30.00, 'Weekly'),
(74, 49, 'Utilities', 90.00, 'Monthly'),
(75, 50, 'Groceries', 50.00, 'Monthly'),
(76, 50, 'Food', 15.00, 'Weekly'),
(77, 51, 'Groceries', 250.00, 'Monthly'),
(78, 51, 'Utilities', 180.00, 'Monthly'),
(79, 51, 'Entertainment', 120.00, 'Monthly'),
(80, 52, 'Groceries', 300.00, 'Monthly'),
(81, 52, 'Food', 70.00, 'Weekly'),
(82, 52, 'Transportation', 80.00, 'Monthly'),
(83, 53, 'Groceries', 150.00, 'Monthly'),
(84, 53, 'Food', 40.00, 'Weekly'),
(85, 53, 'Entertainment', 40.00, 'Monthly'),
(86, 54, 'Groceries', 160.00, 'Monthly'),
(87, 54, 'Utilities', 100.00, 'Monthly'),
(88, 54, 'Entertainment', 25.00, 'Monthly'),
(89, 55, 'Groceries', 180.00, 'Monthly'),
(90, 55, 'Food', 40.00, 'Weekly'),
(91, 55, 'Utilities', 120.00, 'Monthly'),
(92, 56, 'Groceries', 100.00, 'Monthly'),
(93, 56, 'Transportation', 30.00, 'Weekly'),
(94, 57, 'Groceries', 200.00, 'Monthly'),
(95, 57, 'Utilities', 140.00, 'Monthly'),
(96, 57, 'Transportation', 75.00, 'Monthly'),
(97, 58, 'Groceries', 250.00, 'Monthly'),
(98, 58, 'Food', 55.00, 'Weekly'),
(99, 58, 'Entertainment', 45.00, 'Monthly'),
(100, 59, 'Groceries', 120.00, 'Monthly'),
(101, 59, 'Food', 35.00, 'Weekly'),
(102, 59, 'Transportation', 30.00, 'Monthly'),
(103, 60, 'Groceries', 100.00, 'Monthly'),
(104, 60, 'Utilities', 70.00, 'Monthly'),
(105, 60, 'Entertainment', 20.00, 'Monthly'),
(106, 61, 'Groceries', 100.00, 'Monthly'),
(107, 61, 'Food', 25.00, 'Weekly'),
(108, 61, 'Utilities', 90.00, 'Monthly'),
(109, 62, 'Groceries', 30.00, 'Monthly'),
(110, 62, 'Food', 12.00, 'Weekly'),
(111, 63, 'Groceries', 240.00, 'Monthly'),
(112, 63, 'Utilities', 170.00, 'Monthly'),
(113, 63, 'Entertainment', 110.00, 'Monthly'),
(114, 64, 'Groceries', 280.00, 'Monthly'),
(115, 64, 'Food', 65.00, 'Weekly'),
(116, 64, 'Transportation', 75.00, 'Monthly'),
(117, 65, 'Groceries', 160.00, 'Monthly'),
(118, 65, 'Food', 38.00, 'Weekly'),
(119, 65, 'Entertainment', 35.00, 'Monthly'),
(120, 66, 'Groceries', 160.00, 'Monthly'),
(121, 66, 'Utilities', 90.00, 'Monthly'),
(122, 66, 'Entertainment', 25.00, 'Monthly'),
(123, 67, 'Groceries', 170.00, 'Monthly'),
(124, 67, 'Food', 40.00, 'Weekly'),
(125, 67, 'Utilities', 120.00, 'Monthly'),
(126, 68, 'Groceries', 110.00, 'Monthly'),
(127, 68, 'Transportation', 30.00, 'Weekly'),
(128, 69, 'Groceries', 190.00, 'Monthly'),
(129, 69, 'Utilities', 130.00, 'Monthly'),
(130, 69, 'Transportation', 70.00, 'Monthly'),
(131, 70, 'Groceries', 220.00, 'Monthly'),
(132, 70, 'Food', 50.00, 'Weekly'),
(133, 70, 'Entertainment', 40.00, 'Monthly'),
(134, 71, 'Groceries', 110.00, 'Monthly'),
(135, 71, 'Food', 30.00, 'Weekly'),
(136, 71, 'Transportation', 30.00, 'Monthly'),
(137, 72, 'Groceries', 60.00, 'Monthly'),
(138, 72, 'Utilities', 80.00, 'Monthly'),
(139, 72, 'Entertainment', 20.00, 'Monthly'),
(140, 73, 'Groceries', 80.00, 'Monthly'),
(141, 73, 'Food', 20.00, 'Weekly'),
(142, 73, 'Utilities', 80.00, 'Monthly'),
(143, 74, 'Groceries', 20.00, 'Monthly'),
(144, 74, 'Food', 12.00, 'Weekly'),
(145, 75, 'Groceries', 280.00, 'Monthly'),
(146, 75, 'Utilities', 200.00, 'Monthly'),
(147, 75, 'Entertainment', 130.00, 'Monthly'),
(148, 76, 'Groceries', 320.00, 'Monthly'),
(149, 76, 'Food', 80.00, 'Weekly'),
(150, 76, 'Transportation', 90.00, 'Monthly'),
(151, 77, 'Groceries', 180.00, 'Monthly'),
(152, 77, 'Food', 45.00, 'Weekly'),
(153, 77, 'Entertainment', 40.00, 'Monthly'),
(154, 78, 'Groceries', 150.00, 'Monthly'),
(155, 78, 'Utilities', 90.00, 'Monthly'),
(156, 78, 'Entertainment', 25.00, 'Monthly'),
(157, 79, 'Groceries', 160.00, 'Monthly'),
(158, 79, 'Food', 40.00, 'Weekly'),
(159, 79, 'Utilities', 110.00, 'Monthly'),
(160, 80, 'Groceries', 80.00, 'Monthly'),
(161, 80, 'Transportation', 30.00, 'Weekly'),
(162, 81, 'Groceries', 220.00, 'Monthly'),
(163, 81, 'Utilities', 150.00, 'Monthly'),
(164, 81, 'Transportation', 80.00, 'Monthly'),
(165, 82, 'Groceries', 260.00, 'Monthly'),
(166, 82, 'Food', 60.00, 'Weekly'),
(167, 82, 'Entertainment', 50.00, 'Monthly'),
(168, 83, 'Groceries', 140.00, 'Monthly'),
(169, 83, 'Food', 38.00, 'Weekly'),
(170, 83, 'Entertainment', 35.00, 'Monthly'),
(171, 84, 'Groceries', 170.00, 'Monthly'),
(172, 84, 'Utilities', 100.00, 'Monthly'),
(173, 84, 'Entertainment', 30.00, 'Monthly'),
(174, 85, 'Groceries', 180.00, 'Monthly'),
(175, 85, 'Food', 45.00, 'Weekly'),
(176, 85, 'Utilities', 120.00, 'Monthly'),
(177, 86, 'Groceries', 100.00, 'Monthly'),
(178, 86, 'Transportation', 30.00, 'Weekly');

-- --------------------------------------------------------

--
-- Table structure for table `Transactions`
--

CREATE TABLE `Transactions` (
  `TransactionNumber` int(11) NOT NULL,
  `AccountNumber` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Category` enum('Groceries','Transportation','Entertainment','Utilities','Food') NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Transactions`
--

INSERT INTO `Transactions` (`TransactionNumber`, `AccountNumber`, `Name`, `Amount`, `Category`, `Date`) VALUES
(259, 27, 'Walmart', 54.32, 'Groceries', '2026-02-05'),
(260, 27, 'Shell Gas', 48.00, 'Transportation', '2026-02-08'),
(261, 27, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(262, 27, 'Electric Bill', 110.00, 'Utilities', '2026-02-15'),
(263, 28, 'Publix', 62.18, 'Groceries', '2026-02-12'),
(264, 28, 'Uber', 22.50, 'Transportation', '2026-02-14'),
(265, 28, 'McDonalds', 12.50, 'Food', '2026-02-10'),
(266, 28, 'Water Bill', 45.00, 'Utilities', '2026-02-20'),
(267, 29, 'Farmers Market', 35.00, 'Groceries', '2026-02-08'),
(268, 29, 'Chipotle', 14.75, 'Food', '2026-02-12'),
(269, 29, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(270, 29, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(271, 30, 'Walmart', 70.15, 'Groceries', '2026-02-04'),
(272, 30, 'Shell Gas', 55.00, 'Transportation', '2026-02-07'),
(273, 30, 'Disney Plus', 13.99, 'Entertainment', '2026-02-01'),
(274, 30, 'Electric Bill', 120.00, 'Utilities', '2026-02-15'),
(275, 31, 'Publix', 38.90, 'Groceries', '2026-02-14'),
(276, 31, 'Uber', 35.00, 'Transportation', '2026-02-14'),
(277, 31, 'Wendys', 11.25, 'Food', '2026-02-09'),
(278, 31, 'Internet Bill', 79.99, 'Utilities', '2026-02-15'),
(279, 32, 'Taco Bell', 12.50, 'Food', '2026-02-10'),
(280, 32, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(281, 32, 'Farmers Market', 25.00, 'Groceries', '2026-02-18'),
(282, 32, 'Candy', 8.00, 'Food', '2026-02-20'),
(283, 33, 'Publix', 55.60, 'Groceries', '2026-02-05'),
(284, 33, 'Shell Gas', 65.00, 'Transportation', '2026-02-05'),
(285, 33, 'Concert', 85.00, 'Entertainment', '2026-02-12'),
(286, 33, 'Water Bill', 52.00, 'Utilities', '2026-02-15'),
(287, 34, 'Whole Foods', 110.80, 'Groceries', '2026-02-03'),
(288, 34, 'Uber', 22.50, 'Transportation', '2026-02-12'),
(289, 34, 'Chick-fil-A', 16.40, 'Food', '2026-02-08'),
(290, 34, 'Electric Bill', 135.00, 'Utilities', '2026-02-15'),
(291, 35, 'Farmers Market', 40.00, 'Groceries', '2026-02-08'),
(292, 35, 'McDonalds', 14.20, 'Food', '2026-02-05'),
(293, 35, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(294, 35, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(295, 36, 'Walmart', 42.10, 'Groceries', '2026-02-03'),
(296, 36, 'Shell Gas', 40.00, 'Transportation', '2026-02-06'),
(297, 36, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(298, 36, 'Electric Bill', 95.00, 'Utilities', '2026-02-15'),
(299, 37, 'Publix', 33.75, 'Groceries', '2026-02-11'),
(300, 37, 'Uber', 18.00, 'Transportation', '2026-02-09'),
(301, 37, 'Subway', 10.95, 'Food', '2026-02-14'),
(302, 37, 'Water Bill', 38.00, 'Utilities', '2026-02-15'),
(303, 38, 'Taco Bell', 12.00, 'Food', '2026-02-10'),
(304, 38, 'Farmers Market', 18.00, 'Groceries', '2026-02-15'),
(305, 38, 'Bus Pass', 15.00, 'Transportation', '2026-02-05'),
(306, 38, 'Candy', 8.00, 'Food', '2026-02-20'),
(307, 39, 'Walmart', 88.45, 'Groceries', '2026-02-04'),
(308, 39, 'Shell Gas', 72.00, 'Transportation', '2026-02-07'),
(309, 39, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(310, 39, 'Electric Bill', 145.00, 'Utilities', '2026-02-15'),
(311, 40, 'Whole Foods', 95.20, 'Groceries', '2026-02-10'),
(312, 40, 'Uber', 30.00, 'Transportation', '2026-02-13'),
(313, 40, 'Chick-fil-A', 18.75, 'Food', '2026-02-08'),
(314, 40, 'Internet Bill', 89.99, 'Utilities', '2026-02-15'),
(315, 41, 'Farmers Market', 35.00, 'Groceries', '2026-02-08'),
(316, 41, 'Panera', 18.50, 'Food', '2026-02-14'),
(317, 41, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(318, 41, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(319, 42, 'Walmart', 51.20, 'Groceries', '2026-02-05'),
(320, 42, 'Shell Gas', 46.00, 'Transportation', '2026-02-07'),
(321, 42, 'Disney Plus', 13.99, 'Entertainment', '2026-02-01'),
(322, 42, 'Water Bill', 42.00, 'Utilities', '2026-02-15'),
(323, 43, 'Publix', 44.80, 'Groceries', '2026-02-13'),
(324, 43, 'Uber', 25.00, 'Transportation', '2026-02-11'),
(325, 43, 'McDonalds', 11.80, 'Food', '2026-02-10'),
(326, 43, 'Electric Bill', 108.00, 'Utilities', '2026-02-15'),
(327, 44, 'Farmers Market', 55.00, 'Groceries', '2026-02-08'),
(328, 44, 'Wendys', 12.40, 'Food', '2026-02-14'),
(329, 44, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(330, 44, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(331, 45, 'Walmart', 64.35, 'Groceries', '2026-02-04'),
(332, 45, 'Shell Gas', 57.00, 'Transportation', '2026-02-06'),
(333, 45, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(334, 45, 'Electric Bill', 118.00, 'Utilities', '2026-02-15'),
(335, 46, 'Publix', 49.90, 'Groceries', '2026-02-12'),
(336, 46, 'Uber', 28.00, 'Transportation', '2026-02-14'),
(337, 46, 'Chipotle', 15.75, 'Food', '2026-02-09'),
(338, 46, 'Internet Bill', 74.99, 'Utilities', '2026-02-15'),
(339, 47, 'Farmers Market', 40.00, 'Groceries', '2026-02-08'),
(340, 47, 'Taco Bell', 12.00, 'Food', '2026-02-14'),
(341, 47, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(342, 47, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(343, 48, 'Walmart', 38.75, 'Groceries', '2026-02-03'),
(344, 48, 'Shell Gas', 36.00, 'Transportation', '2026-02-05'),
(345, 48, 'Disney Plus', 13.99, 'Entertainment', '2026-02-01'),
(346, 48, 'Water Bill', 35.00, 'Utilities', '2026-02-15'),
(347, 49, 'Publix', 29.60, 'Groceries', '2026-02-11'),
(348, 49, 'Uber', 15.00, 'Transportation', '2026-02-09'),
(349, 49, 'McDonalds', 9.80, 'Food', '2026-02-08'),
(350, 49, 'Electric Bill', 88.00, 'Utilities', '2026-02-15'),
(351, 50, 'Farmers Market', 22.00, 'Groceries', '2026-02-08'),
(352, 50, 'Subway', 10.25, 'Food', '2026-02-12'),
(353, 50, 'Bus Pass', 15.00, 'Transportation', '2026-02-05'),
(354, 50, 'Candy', 8.00, 'Food', '2026-02-20'),
(355, 51, 'Whole Foods', 115.40, 'Groceries', '2026-02-04'),
(356, 51, 'Shell Gas', 80.00, 'Transportation', '2026-02-07'),
(357, 51, 'Concert', 95.00, 'Entertainment', '2026-02-13'),
(358, 51, 'Electric Bill', 160.00, 'Utilities', '2026-02-15'),
(359, 52, 'Walmart', 98.75, 'Groceries', '2026-02-10'),
(360, 52, 'Uber', 42.00, 'Transportation', '2026-02-14'),
(361, 52, 'Chick-fil-A', 22.40, 'Food', '2026-02-09'),
(362, 52, 'Internet Bill', 99.99, 'Utilities', '2026-02-15'),
(363, 53, 'Farmers Market', 80.00, 'Groceries', '2026-02-08'),
(364, 53, 'Panera', 21.50, 'Food', '2026-02-14'),
(365, 53, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(366, 53, 'Bus Pass', 25.00, 'Transportation', '2026-02-05'),
(367, 54, 'Walmart', 58.20, 'Groceries', '2026-02-05'),
(368, 54, 'Shell Gas', 50.00, 'Transportation', '2026-02-07'),
(369, 54, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(370, 54, 'Water Bill', 47.00, 'Utilities', '2026-02-15'),
(371, 55, 'Publix', 46.40, 'Groceries', '2026-02-13'),
(372, 55, 'Uber', 20.00, 'Transportation', '2026-02-11'),
(373, 55, 'Wendys', 13.25, 'Food', '2026-02-10'),
(374, 55, 'Electric Bill', 112.00, 'Utilities', '2026-02-15'),
(375, 56, 'Farmers Market', 45.00, 'Groceries', '2026-02-08'),
(376, 56, 'McDonalds', 10.50, 'Food', '2026-02-14'),
(377, 56, 'Disney Plus', 13.99, 'Entertainment', '2026-02-01'),
(378, 56, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(379, 57, 'Walmart', 76.90, 'Groceries', '2026-02-04'),
(380, 57, 'Shell Gas', 63.00, 'Transportation', '2026-02-06'),
(381, 57, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(382, 57, 'Electric Bill', 128.00, 'Utilities', '2026-02-15'),
(383, 58, 'Whole Foods', 92.50, 'Groceries', '2026-02-11'),
(384, 58, 'Uber', 32.00, 'Transportation', '2026-02-13'),
(385, 58, 'Chipotle', 16.90, 'Food', '2026-02-09'),
(386, 58, 'Internet Bill', 84.99, 'Utilities', '2026-02-15'),
(387, 59, 'Farmers Market', 60.00, 'Groceries', '2026-02-08'),
(388, 59, 'Chick-fil-A', 19.75, 'Food', '2026-02-14'),
(389, 59, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(390, 59, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(391, 60, 'Walmart', 35.60, 'Groceries', '2026-02-03'),
(392, 60, 'Shell Gas', 32.00, 'Transportation', '2026-02-05'),
(393, 60, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(394, 60, 'Water Bill', 30.00, 'Utilities', '2026-02-15'),
(395, 61, 'Publix', 27.80, 'Groceries', '2026-02-11'),
(396, 61, 'Uber', 12.00, 'Transportation', '2026-02-09'),
(397, 61, 'Subway', 9.75, 'Food', '2026-02-08'),
(398, 61, 'Electric Bill', 82.00, 'Utilities', '2026-02-15'),
(399, 62, 'Farmers Market', 15.00, 'Groceries', '2026-02-08'),
(400, 62, 'Taco Bell', 10.00, 'Food', '2026-02-12'),
(401, 62, 'Bus Pass', 12.00, 'Transportation', '2026-02-05'),
(402, 62, 'Candy', 8.00, 'Food', '2026-02-20'),
(403, 63, 'Whole Foods', 108.60, 'Groceries', '2026-02-04'),
(404, 63, 'Shell Gas', 78.00, 'Transportation', '2026-02-07'),
(405, 63, 'Concert', 90.00, 'Entertainment', '2026-02-12'),
(406, 63, 'Electric Bill', 155.00, 'Utilities', '2026-02-15'),
(407, 64, 'Walmart', 92.40, 'Groceries', '2026-02-10'),
(408, 64, 'Uber', 40.00, 'Transportation', '2026-02-14'),
(409, 64, 'Chick-fil-A', 21.50, 'Food', '2026-02-09'),
(410, 64, 'Internet Bill', 94.99, 'Utilities', '2026-02-15'),
(411, 65, 'Farmers Market', 90.00, 'Groceries', '2026-02-08'),
(412, 65, 'Panera', 20.80, 'Food', '2026-02-14'),
(413, 65, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(414, 65, 'Bus Pass', 25.00, 'Transportation', '2026-02-05'),
(415, 66, 'Walmart', 60.40, 'Groceries', '2026-02-05'),
(416, 66, 'Shell Gas', 52.00, 'Transportation', '2026-02-07'),
(417, 66, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(418, 66, 'Water Bill', 44.00, 'Utilities', '2026-02-15'),
(419, 67, 'Publix', 48.20, 'Groceries', '2026-02-13'),
(420, 67, 'Uber', 24.00, 'Transportation', '2026-02-11'),
(421, 67, 'McDonalds', 12.20, 'Food', '2026-02-10'),
(422, 67, 'Electric Bill', 114.00, 'Utilities', '2026-02-15'),
(423, 68, 'Farmers Market', 50.00, 'Groceries', '2026-02-08'),
(424, 68, 'Chipotle', 15.40, 'Food', '2026-02-14'),
(425, 68, 'Disney Plus', 13.99, 'Entertainment', '2026-02-01'),
(426, 68, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(427, 69, 'Walmart', 72.80, 'Groceries', '2026-02-04'),
(428, 69, 'Shell Gas', 60.00, 'Transportation', '2026-02-06'),
(429, 69, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(430, 69, 'Electric Bill', 122.00, 'Utilities', '2026-02-15'),
(431, 70, 'Whole Foods', 88.70, 'Groceries', '2026-02-12'),
(432, 70, 'Uber', 29.00, 'Transportation', '2026-02-13'),
(433, 70, 'Wendys', 13.80, 'Food', '2026-02-09'),
(434, 70, 'Internet Bill', 79.99, 'Utilities', '2026-02-15'),
(435, 71, 'Farmers Market', 55.00, 'Groceries', '2026-02-08'),
(436, 71, 'Taco Bell', 12.60, 'Food', '2026-02-14'),
(437, 71, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(438, 71, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(439, 72, 'Walmart', 30.40, 'Groceries', '2026-02-03'),
(440, 72, 'Shell Gas', 28.00, 'Transportation', '2026-02-05'),
(441, 72, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(442, 72, 'Water Bill', 28.00, 'Utilities', '2026-02-15'),
(443, 73, 'Publix', 24.60, 'Groceries', '2026-02-11'),
(444, 73, 'Uber', 10.00, 'Transportation', '2026-02-09'),
(445, 73, 'McDonalds', 8.90, 'Food', '2026-02-08'),
(446, 73, 'Electric Bill', 76.00, 'Utilities', '2026-02-15'),
(447, 74, 'Farmers Market', 10.00, 'Groceries', '2026-02-08'),
(448, 74, 'Taco Bell', 9.00, 'Food', '2026-02-12'),
(449, 74, 'Bus Pass', 12.00, 'Transportation', '2026-02-05'),
(450, 74, 'Candy', 8.00, 'Food', '2026-02-20'),
(451, 75, 'Whole Foods', 125.60, 'Groceries', '2026-02-04'),
(452, 75, 'Shell Gas', 88.00, 'Transportation', '2026-02-07'),
(453, 75, 'Concert', 105.00, 'Entertainment', '2026-02-13'),
(454, 75, 'Electric Bill', 175.00, 'Utilities', '2026-02-15'),
(455, 76, 'Walmart', 108.90, 'Groceries', '2026-02-10'),
(456, 76, 'Uber', 48.00, 'Transportation', '2026-02-14'),
(457, 76, 'Chick-fil-A', 24.50, 'Food', '2026-02-09'),
(458, 76, 'Internet Bill', 109.99, 'Utilities', '2026-02-15'),
(459, 77, 'Farmers Market', 100.00, 'Groceries', '2026-02-08'),
(460, 77, 'Panera', 23.80, 'Food', '2026-02-14'),
(461, 77, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(462, 77, 'Bus Pass', 25.00, 'Transportation', '2026-02-05'),
(463, 78, 'Walmart', 56.30, 'Groceries', '2026-02-05'),
(464, 78, 'Shell Gas', 48.00, 'Transportation', '2026-02-07'),
(465, 78, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(466, 78, 'Water Bill', 40.00, 'Utilities', '2026-02-15'),
(467, 79, 'Publix', 43.70, 'Groceries', '2026-02-13'),
(468, 79, 'Uber', 22.00, 'Transportation', '2026-02-11'),
(469, 79, 'Chipotle', 14.90, 'Food', '2026-02-10'),
(470, 79, 'Electric Bill', 105.00, 'Utilities', '2026-02-15'),
(471, 80, 'Farmers Market', 35.00, 'Groceries', '2026-02-08'),
(472, 80, 'McDonalds', 11.40, 'Food', '2026-02-14'),
(473, 80, 'Disney Plus', 13.99, 'Entertainment', '2026-02-01'),
(474, 80, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(475, 81, 'Whole Foods', 98.80, 'Groceries', '2026-02-04'),
(476, 81, 'Shell Gas', 74.00, 'Transportation', '2026-02-06'),
(477, 81, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(478, 81, 'Electric Bill', 140.00, 'Utilities', '2026-02-15'),
(479, 82, 'Walmart', 86.50, 'Groceries', '2026-02-11'),
(480, 82, 'Uber', 36.00, 'Transportation', '2026-02-13'),
(481, 82, 'Chick-fil-A', 20.75, 'Food', '2026-02-09'),
(482, 82, 'Internet Bill', 89.99, 'Utilities', '2026-02-15'),
(483, 83, 'Farmers Market', 70.00, 'Groceries', '2026-02-08'),
(484, 83, 'Panera', 19.90, 'Food', '2026-02-14'),
(485, 83, 'Spotify', 9.99, 'Entertainment', '2026-02-01'),
(486, 83, 'Bus Pass', 25.00, 'Transportation', '2026-02-05'),
(487, 84, 'Walmart', 62.10, 'Groceries', '2026-02-05'),
(488, 84, 'Shell Gas', 54.00, 'Transportation', '2026-02-07'),
(489, 84, 'Disney Plus', 13.99, 'Entertainment', '2026-02-01'),
(490, 84, 'Water Bill', 46.00, 'Utilities', '2026-02-15'),
(491, 85, 'Publix', 50.30, 'Groceries', '2026-02-13'),
(492, 85, 'Uber', 26.00, 'Transportation', '2026-02-11'),
(493, 85, 'Wendys', 12.80, 'Food', '2026-02-10'),
(494, 85, 'Electric Bill', 116.00, 'Utilities', '2026-02-15'),
(495, 86, 'Farmers Market', 45.00, 'Groceries', '2026-02-08'),
(496, 86, 'Taco Bell', 11.90, 'Food', '2026-02-14'),
(497, 86, 'Netflix', 15.99, 'Entertainment', '2026-02-01'),
(498, 86, 'Bus Pass', 20.00, 'Transportation', '2026-02-05'),
(499, 48, 'Bread', 30.00, 'Food', '2026-04-20');

--
-- Triggers `Transactions`
--


-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `LoginName` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `LoginPassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`LoginName`, `Name`, `LoginPassword`) VALUES
('bmartin', 'Betty Martin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('bwilson', 'Barbara Wilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('cjackson', 'Charles Jackson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('dharris', 'Daniel Harris', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('djones', 'David Jones', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('drobinson', 'Donna Robinson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('dtaylor', 'Dorothy Taylor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('hwhite', 'Helen White', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('janderson', 'James Anderson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('jsmith', 'John Smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Juan', 'Juan', '$2y$10$iDHLALSEGbtqHxhQGzpDouxDyT4rAqxvhXCHSLmc2JhbqWPoPqZIy'),
('lmiller', 'Linda Miller', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('mdavis', 'Michael Davis', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('mjohnson', 'Mary Johnson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('mthomas', 'Margaret Thomas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('mthompson', 'Mark Thompson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('pbrown', 'Patricia Brown', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('rmoore', 'Richard Moore', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('rwilliams', 'Robert Williams', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('sgarcia', 'Sandra Garcia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('William', 'William', '$2y$10$4HsmkAt3AQp0aW.VUaoSw.2GjV2JOdUaQG9S2m.LYNdL6kJgwSxnC'),
('wmartinez', 'William Martinez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Account`
--
ALTER TABLE `Account`
  ADD PRIMARY KEY (`AccountNumber`),
  ADD KEY `LoginName` (`LoginName`);

--
-- Indexes for table `Budget`
--
ALTER TABLE `Budget`
  ADD PRIMARY KEY (`BudgetID`),
  ADD KEY `AccountNumber` (`AccountNumber`);

--
-- Indexes for table `Transactions`
--
ALTER TABLE `Transactions`
  ADD PRIMARY KEY (`TransactionNumber`),
  ADD KEY `AccountNumber` (`AccountNumber`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`LoginName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Account`
--
ALTER TABLE `Account`
  MODIFY `AccountNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `Budget`
--
ALTER TABLE `Budget`
  MODIFY `BudgetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `Transactions`
--
ALTER TABLE `Transactions`
  MODIFY `TransactionNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=500;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Account`
--
ALTER TABLE `Account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`LoginName`) REFERENCES `Users` (`LoginName`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Budget`
--
ALTER TABLE `Budget`
  ADD CONSTRAINT `budget_ibfk_1` FOREIGN KEY (`AccountNumber`) REFERENCES `Account` (`AccountNumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Transactions`
--
ALTER TABLE `Transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`AccountNumber`) REFERENCES `Account` (`AccountNumber`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
