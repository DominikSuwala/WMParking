-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2017 at 09:32 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bays`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bay_entry`
--

CREATE TABLE `tbl_bay_entry` (
  `event_id` bigint(20) NOT NULL,
  `bay_id` smallint(6) NOT NULL,
  `enter_ts` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Triggers `tbl_bay_entry`
--
DELIMITER $$
CREATE TRIGGER `markForDelete` BEFORE UPDATE ON `tbl_bay_entry` FOR EACH ROW BEGIN
    
    INSERT INTO `tbl_visits`
    (`entry_ts`,`exit_ts`,`bay_id`)
    VALUES
    (old.`enter_ts`, new.`enter_ts`, old.`bay_id`);
    
    SET new.bay_id = -1;
    
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bay_entry`
--
ALTER TABLE `tbl_bay_entry`
  ADD PRIMARY KEY (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bay_entry`
--
ALTER TABLE `tbl_bay_entry`
  MODIFY `event_id` bigint(20) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
