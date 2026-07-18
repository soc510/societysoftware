-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2018 at 04:29 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elogistics`
--

-- --------------------------------------------------------

--
-- Table structure for table `member_payments`
--

CREATE TABLE `member_payments` (
  `id` int(50) NOT NULL,
  `society_id` int(11) NOT NULL,
  `receipt_id` varchar(20) NOT NULL,
  `member_id` int(5) NOT NULL,
  `bill_generated_id` int(5) NOT NULL,
  `amount_paid` float(10,2) NOT NULL,
  `principal_balance` float(15,2) NOT NULL,
  `interest_balance` float(15,2) NOT NULL,
  `balance_amount` float(15,2) NOT NULL,
  `payment_mode` varchar(10) DEFAULT NULL,
  `cheque_reference_number` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `credited_date` date DEFAULT NULL,
  `society_bank_id` int(3) DEFAULT NULL,
  `bank_slip_no` varchar(21) DEFAULT NULL,
  `member_bank_id` int(3) DEFAULT NULL,
  `member_bank_ifsc` varchar(15) DEFAULT NULL,
  `member_bank_branch` varchar(30) DEFAULT NULL,
  `entry_date` datetime NOT NULL,
  `bill_type` enum('reg','sup') NOT NULL DEFAULT 'reg',
  `cdate` datetime NOT NULL,
  `udate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_payments`
--

INSERT INTO `member_payments` (`id`, `society_id`, `receipt_id`, `member_id`, `bill_generated_id`, `amount_paid`, `principal_balance`, `interest_balance`, `balance_amount`, `payment_mode`, `cheque_reference_number`, `payment_date`, `credited_date`, `society_bank_id`, `bank_slip_no`, `member_bank_id`, `member_bank_ifsc`, `member_bank_branch`, `entry_date`, `bill_type`, `cdate`, `udate`) VALUES
(2, 2, '', 5, 4, 2000.00, -730.86, 600.00, -130.86, '1', '', '2018-03-05', NULL, 17, NULL, NULL, '', '', '2018-03-05 08:26:07', 'reg', '2018-03-05 08:26:07', '2018-03-05 08:26:07'),
(5, 2, '', 5, 6, 500.00, -961.72, 598.00, -363.72, '1', '', '2018-03-14', NULL, 17, NULL, NULL, '', '', '2018-03-05 08:52:50', 'reg', '2018-03-05 08:52:50', '2018-03-05 08:52:50'),
(6, 2, '', 5, 8, 700.00, -892.58, 598.00, -294.58, '1', '', '2018-03-05', NULL, 4, NULL, NULL, '', '', '2018-03-05 09:04:12', 'reg', '2018-03-05 09:04:12', '2018-03-05 09:04:12'),
(7, 2, '', 5, 10, 500.00, 660.80, 600.00, 1260.80, '1', '', '2018-03-01', NULL, 17, NULL, NULL, '', '', '2018-03-05 09:23:57', 'sup', '2018-03-05 09:23:57', '2018-03-05 09:23:57'),
(17, 6, '', 29, 35, 500.00, 500.00, 0.00, 500.00, '3', 'rt89', '2017-04-05', '2017-04-10', 75, NULL, 1, 'AN89', 'Kamothe', '2018-03-06 06:52:29', 'reg', '2018-03-06 06:52:29', '2018-03-06 06:52:29'),
(18, 6, '', 30, 34, 1000.00, 0.00, 0.00, 0.00, '3', 'fg456', '2017-04-02', '2017-04-05', 75, NULL, 1, 'fg456', 'Kamothe', '2018-03-06 06:55:10', 'reg', '2018-03-06 06:55:10', '2018-03-06 06:55:10'),
(19, 6, '', 29, 35, 700.00, 300.00, 0.00, 300.00, '3', 'rt456', '2017-04-08', '2017-04-12', 75, NULL, 1, 'tyrt456', 'Panvel', '2018-03-06 09:02:57', 'reg', '2018-03-06 09:02:57', '2018-03-06 09:02:57'),
(20, 6, '', 31, 41, 1000.00, 1000.00, 17.00, 1017.00, '3', 'fg456', '2017-05-02', '2017-05-10', 75, NULL, 1, 'fg56', 'panvel', '2018-03-06 07:35:01', 'reg', '2018-03-06 07:35:01', '2018-03-06 07:35:01'),
(21, 6, '', 31, 41, 500.00, 1500.00, 17.00, 1517.00, '3', 'fg457', '2017-05-12', '2017-05-13', 75, NULL, 1, 'fg457', 'panvel', '2018-03-06 07:59:30', 'reg', '2018-03-06 07:59:30', '2018-03-06 07:59:30'),
(22, 6, '', 32, 44, 1000.00, 1000.00, 17.00, 1017.00, '2', 'boi123', '2017-05-02', '2017-05-05', 75, NULL, 1, 'boi123', 'kamothe', '2018-03-06 08:10:55', 'reg', '2018-03-06 08:10:55', '2018-03-06 08:10:55'),
(23, 6, '', 33, 45, 700.00, 600.00, 17.00, 617.00, '1', '', '2018-03-14', NULL, 75, NULL, NULL, '', '', '2018-03-06 09:18:28', 'reg', '2018-03-06 09:18:28', '2018-03-06 09:18:28'),
(24, 6, '', 33, 45, 700.00, 1300.00, 17.00, 1317.00, '1', '', '2018-03-06', NULL, 75, NULL, NULL, '', '', '2018-03-06 09:05:32', 'reg', '2018-03-06 09:05:32', '2018-03-06 09:05:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `member_payments`
--
ALTER TABLE `member_payments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `member_payments`
--
ALTER TABLE `member_payments`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
