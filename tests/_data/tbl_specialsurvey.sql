-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 07, 2022 at 03:12 AM
-- Server version: 5.5.16-log
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gamis_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_specialsurvey`
--

CREATE TABLE `tbl_specialsurvey` (
  `id` bigint(20) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `middle_name` varchar(32) DEFAULT NULL,
  `gender` varchar(16) DEFAULT NULL,
  `age` tinyint(3) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `civil_status` varchar(32) DEFAULT NULL,
  `house_no` varchar(32) DEFAULT NULL,
  `sitio` varchar(32) DEFAULT NULL,
  `purok` varchar(32) DEFAULT NULL,
  `barangay` varchar(32) DEFAULT NULL,
  `municipality` varchar(32) DEFAULT NULL,
  `province` varchar(32) DEFAULT NULL,
  `religion` varchar(32) DEFAULT NULL,
  `criteria1_color_id` int(11) DEFAULT NULL,
  `criteria2_color_id` int(11) DEFAULT NULL,
  `criteria3_color_id` int(11) DEFAULT NULL,
  `criteria4_color_id` int(11) DEFAULT NULL,
  `criteria5_color_id` int(11) DEFAULT NULL,
  `date_survey` date DEFAULT NULL,
  `remarks` varchar(128) DEFAULT NULL,
  `record_status` tinyint(2) NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `updated_by` bigint(20) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `survey_name` varchar(255) DEFAULT NULL,
  `household_no` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_specialsurvey`
--
ALTER TABLE `tbl_specialsurvey`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_specialsurvey`
--
ALTER TABLE `tbl_specialsurvey`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
