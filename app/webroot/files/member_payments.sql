-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2018 at 04:15 PM
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
  `payment_mode` varchar(10) DEFAULT NULL,
  `cheque_reference_number` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `credited_date` date DEFAULT NULL,
  `society_bank_id` int(3) DEFAULT NULL,
  `bank_slip_no` varchar(21) DEFAULT NULL,
  `member_bank_id` int(3) DEFAULT NULL,
  `member_bank_ifsc` varchar(15) DEFAULT NULL,
  `member_bank_branch` varchar(30) DEFAULT NULL,
  `entry_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_payments`
--

INSERT INTO `member_payments` (`id`, `society_id`, `receipt_id`, `member_id`, `bill_generated_id`, `amount_paid`, `payment_mode`, `cheque_reference_number`, `payment_date`, `credited_date`, `society_bank_id`, `bank_slip_no`, `member_bank_id`, `member_bank_ifsc`, `member_bank_branch`, `entry_date`) VALUES
(1, 2, '', 5, 4, 500.00, '3', '434', '2018-02-12', '2018-02-13', 4, NULL, 1, '43434', 'KAMOTHE', '2018-02-12 09:15:58'),
(2, 2, '', 5, 4, 500.00, '3', '76777', '2018-02-13', '2018-02-12', 16, NULL, 2, '767767', 'KAMOTHE', '2018-02-12 09:16:28'),
(3, 2, '', 5, 4, 800.00, '3', '434334', '2018-02-13', '2018-02-14', 16, NULL, 1, '34434', '34434', '2018-02-12 09:21:14'),
(4, 2, '', 5, 12, 50.00, '3', '5455', '2018-02-12', '2018-02-06', 4, NULL, 1, '5454', '554545', '2018-02-12 09:22:56');

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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
