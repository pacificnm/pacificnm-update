-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2016 at 10:13 PM
-- Server version: 10.0.28-MariaDB-0+deb8u1
-- PHP Version: 5.6.27-0+deb8u1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `beta`
--

-- --------------------------------------------------------

--
-- Table structure for table `update`
--

CREATE TABLE IF NOT EXISTS `update` (
`update_id` int(20) NOT NULL,
  `update_date_check` int(11) NOT NULL,
  `module_id` int(20) unsigned NOT NULL,
  `module_version` varchar(100) NOT NULL,
  `update_status` varchar(255) NOT NULL
) ENGINE=InnoDB;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `update`
--
ALTER TABLE `update`
 ADD PRIMARY KEY (`update_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `update`
--
ALTER TABLE `update`
MODIFY `update_id` int(20) NOT NULL AUTO_INCREMENT;SET FOREIGN_KEY_CHECKS=1;
