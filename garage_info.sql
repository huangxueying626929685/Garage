-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2018 at 09:22 AM
-- Server version: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `garage`
--

-- --------------------------------------------------------

--
-- Table structure for table `garage_info`
--

CREATE TABLE IF NOT EXISTS `garage_info` (
`id` int(10) NOT NULL,
  `latitude` float NOT NULL,
  `longtitude` float NOT NULL,
  `address` varchar(25) COLLATE utf8_bin NOT NULL,
  `total_num` int(10) NOT NULL,
  `free_num` int(10) NOT NULL,
  `price_per_hour` float NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `garage_info`
--

INSERT INTO `garage_info` (`id`, `latitude`, `longtitude`, `address`, `total_num`, `free_num`, `price_per_hour`) VALUES
(1, 31.0429, 120.82, '江苏省苏州市吴江区聚力机械', 13, 15, 10),
(2, 32.0576, 118.932, '江苏省南京市2号车库', 100, 39, 8),
(3, 32.076, 118.856, '江苏省南京市3号车库', 80, 16, 6),
(4, 32.0875, 118.766, '江苏省南京市1号车库', 60, 41, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `garage_info`
--
ALTER TABLE `garage_info`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `garage_info`
--
ALTER TABLE `garage_info`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
