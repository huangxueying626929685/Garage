-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-05 15:57:38
-- 服务器版本： 5.5.39
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
-- 表的结构 `back_danpianji`
--

CREATE TABLE IF NOT EXISTS `back_danpianji` (
`id` int(10) NOT NULL,
  `now_time` varchar(100) COLLATE utf8_bin NOT NULL,
  `back` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `back_danpianji`
--

INSERT INTO `back_danpianji` (`id`, `now_time`, `back`) VALUES
(1, '', '');

-- --------------------------------------------------------

--
-- 表的结构 `danpianji`
--

CREATE TABLE IF NOT EXISTS `danpianji` (
`id` int(10) NOT NULL,
  `time1` varchar(20) COLLATE utf8_bin NOT NULL,
  `device_id` int(20) NOT NULL,
  `car_num` varchar(100) COLLATE utf8_bin NOT NULL,
  `floor_1` varchar(20) COLLATE utf8_bin NOT NULL,
  `all_floor` varchar(100) COLLATE utf8_bin NOT NULL,
  `datas` varchar(500) COLLATE utf8_bin NOT NULL,
  `len` int(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `danpianji`
--

INSERT INTO `danpianji` (`id`, `time1`, `device_id`, `car_num`, `floor_1`, `all_floor`, `datas`, `len`) VALUES
(1, '2018-06-01 20:09:49', 1, '豫FSQ818', '0', '101010110', '{"id":"1","car":"D4A5FSQ818","f_1":"0","fall":"0","all":"00101010110"}', 70);

-- --------------------------------------------------------

--
-- 表的结构 `garage_info`
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
-- 转存表中的数据 `garage_info`
--

INSERT INTO `garage_info` (`id`, `latitude`, `longtitude`, `address`, `total_num`, `free_num`, `price_per_hour`) VALUES
(1, 31.0429, 120.82, '江苏省苏州市吴江区聚力机械', 13, 15, 10),
(2, 32.0576, 118.932, '江苏省南京市2号车库', 100, 39, 8),
(3, 32.076, 118.856, '江苏省南京市3号车库', 80, 16, 6),
(4, 32.0875, 118.766, '江苏省南京市1号车库', 60, 41, 9);

-- --------------------------------------------------------

--
-- 表的结构 `late_order`
--

CREATE TABLE IF NOT EXISTS `late_order` (
`id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `car_num` varchar(20) COLLATE utf8_bin NOT NULL,
  `action_time` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `cancel_time` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `order_time` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `start_time` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `money` float NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=130 ;

--
-- 转存表中的数据 `late_order`
--

INSERT INTO `late_order` (`id`, `username`, `car_num`, `action_time`, `cancel_time`, `order_time`, `start_time`, `money`) VALUES
(129, '18959204245', '豫FSQ818', '2018-06-05 08:50', '', '2018-06-05 10:12', '', 67.6667);

-- --------------------------------------------------------

--
-- 表的结构 `manager`
--

CREATE TABLE IF NOT EXISTS `manager` (
`id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `manager`
--

INSERT INTO `manager` (`id`, `username`, `password`) VALUES
(1, 'admin', '123456');

-- --------------------------------------------------------

--
-- 表的结构 `member`
--

CREATE TABLE IF NOT EXISTS `member` (
`id` int(20) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(20) COLLATE utf8_bin NOT NULL,
  `car_num` varchar(20) CHARACTER SET utf8 NOT NULL,
  `car_type` varchar(20) CHARACTER SET utf8 NOT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `member`
--

INSERT INTO `member` (`id`, `username`, `password`, `car_num`, `car_type`, `status`) VALUES
(1, '18959204245', '123456', '赣F16712', 'Aventador LP700-4', 1),
(26, '18795968928', '123456', '粤CKC236', '白色保时捷', 1),
(27, '18362903376', '123456', '豫FSQ818', '黑色丰田', 1);

-- --------------------------------------------------------

--
-- 表的结构 `no1_car_place`
--

CREATE TABLE IF NOT EXISTS `no1_car_place` (
`id` int(20) NOT NULL,
  `cp_num` varchar(20) COLLATE utf8_bin NOT NULL,
  `fall_io_point` varchar(10) COLLATE utf8_bin NOT NULL,
  `status` int(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `no1_car_place`
--

INSERT INTO `no1_car_place` (`id`, `cp_num`, `fall_io_point`, `status`) VALUES
(1, '101', '0', 1),
(2, '102', '0', 1),
(3, '201', '1', 1),
(4, '202', '2', 1),
(5, '301', '3', 1),
(6, '302', '4', 0),
(7, '401', '5', 0),
(8, '402', '6', 0),
(9, '501', '7', 1),
(10, '502', '8', 1),
(11, '601', '9', 1),
(12, '602', '10', 1),
(13, '603', '11', 1);

-- --------------------------------------------------------

--
-- 表的结构 `no1_floor_1`
--

CREATE TABLE IF NOT EXISTS `no1_floor_1` (
`id` int(10) NOT NULL,
  `high_floor_cp_num` varchar(10) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `no1_floor_1`
--

INSERT INTO `no1_floor_1` (`id`, `high_floor_cp_num`) VALUES
(1, '201');

-- --------------------------------------------------------

--
-- 表的结构 `order_history`
--

CREATE TABLE IF NOT EXISTS `order_history` (
`id` int(20) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `car_num` varchar(20) COLLATE utf8_bin NOT NULL,
  `garage_num` int(10) NOT NULL,
  `cp_num` int(10) NOT NULL,
  `start_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `leave_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `money` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `order_info`
--

CREATE TABLE IF NOT EXISTS `order_info` (
`order_id` int(10) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `car_num` varchar(20) COLLATE utf8_bin NOT NULL,
  `garage_num` int(11) NOT NULL,
  `action_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `start_time` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=229 ;

--
-- 转存表中的数据 `order_info`
--

INSERT INTO `order_info` (`order_id`, `username`, `car_num`, `garage_num`, `action_time`, `start_time`) VALUES
(224, '18795968928', '粤CKC236', 1, '2018-06-08 18:50', '2018-06-28 18:07'),
(227, '18959204245', '赣F16712', 3, '2018-06-07 09:15', '2018-06-07 09:15'),
(228, '18959204245', '赣F16712', 2, '2018-06-07 10:24', '2018-06-07 10:26');

-- --------------------------------------------------------

--
-- 表的结构 `parking_info`
--

CREATE TABLE IF NOT EXISTS `parking_info` (
`parking_id` int(20) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `car_num` varchar(20) COLLATE utf8_bin NOT NULL,
  `garage_num` int(20) NOT NULL,
  `cp_num` varchar(20) COLLATE utf8_bin NOT NULL,
  `finish_parking` int(10) NOT NULL,
  `confirm_parking` int(10) NOT NULL,
  `action_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `order_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `start_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `leave_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `money` float NOT NULL,
  `pay_status` int(20) NOT NULL,
  `confirm_out` int(10) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=363 ;

--
-- 转存表中的数据 `parking_info`
--

INSERT INTO `parking_info` (`parking_id`, `username`, `car_num`, `garage_num`, `cp_num`, `finish_parking`, `confirm_parking`, `action_time`, `order_time`, `start_time`, `leave_time`, `money`, `pay_status`, `confirm_out`) VALUES
(362, '18959204245', '赣F16712', 2, '201', 1, 0, '2018-05-11 09:57', '2018-05-30 10:56', '2018-04-28 18:07', '2018-06-07 10:25', 0.01, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `running_data`
--

CREATE TABLE IF NOT EXISTS `running_data` (
  `shengjiangdianji` varchar(20) COLLATE utf8_bin NOT NULL,
  `xianweikaiguan` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `back_danpianji`
--
ALTER TABLE `back_danpianji`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `danpianji`
--
ALTER TABLE `danpianji`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `garage_info`
--
ALTER TABLE `garage_info`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `late_order`
--
ALTER TABLE `late_order`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `no1_car_place`
--
ALTER TABLE `no1_car_place`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `no1_floor_1`
--
ALTER TABLE `no1_floor_1`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_info`
--
ALTER TABLE `order_info`
 ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `parking_info`
--
ALTER TABLE `parking_info`
 ADD PRIMARY KEY (`parking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `back_danpianji`
--
ALTER TABLE `back_danpianji`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `danpianji`
--
ALTER TABLE `danpianji`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `garage_info`
--
ALTER TABLE `garage_info`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `late_order`
--
ALTER TABLE `late_order`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=130;
--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `no1_car_place`
--
ALTER TABLE `no1_car_place`
MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `no1_floor_1`
--
ALTER TABLE `no1_floor_1`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_info`
--
ALTER TABLE `order_info`
MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=229;
--
-- AUTO_INCREMENT for table `parking_info`
--
ALTER TABLE `parking_info`
MODIFY `parking_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=363;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
