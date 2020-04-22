-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 22, 2020 at 04:43 PM
-- Server version: 5.6.47-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timeinby_appointment`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `agentID` int(11) NOT NULL,
  `agentName` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `password` text NOT NULL,
  `profession` varchar(255) NOT NULL,
  `photo` text NOT NULL,
  `payment_plan` int(11) NOT NULL DEFAULT '1',
  `usertypeID` int(11) UNSIGNED NOT NULL DEFAULT '2',
  `active` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agentID`, `agentName`, `username`, `description`, `email`, `phone`, `address`, `password`, `profession`, `photo`, `payment_plan`, `usertypeID`, `active`, `created_at`, `deleted_at`) VALUES
(1, 'James John', 'agent', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', 'ezzy.alexo@gmail.com', '0845231445', '37 Redcliffe Way WOODLANDS BH21 0BA', '$2y$11$kP2ykeFHqLfPH9ITCgifm.yPkC4KGrJIQtEkmt40duYUKC2iGhM4q', 'Doctor', 'assets/img/user.png', 1, 2, 1, '2020-02-23 00:00:00', NULL),
(2, 'Elon Hulk', '', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', 'elon.hulk@gmail.com', '0865412566', '20 Osborne Road KING\'S PYON HR4 7ZD', '', 'Baby Sitter', '', 1, 2, 1, '2020-02-23 00:00:00', NULL),
(4, 'Alexander Onyeagwa', 'ales', '', 'alesonyeagwa@gmail.com', '0265243315', 'St Marks Park Avenue', '$2y$11$at0Tlv/wbqlRkDnZi6XEU.CjXTZLoTTVFOWr0c6NCYmRvoQ14M3vi', '', '', 1, 2, 1, '2020-04-18 14:40:33', NULL),
(5, 'James Brown', '', '', 'piblaze@ymail.com', '0873256478', '1191 Gateway Road Portland, OR 97220', '$2y$11$at0Tlv/wbqlRkDnZi6XEU.CjXTZLoTTVFOWr0c6NCYmRvoQ14M3vi', '', '', 1, 2, 1, '2020-04-22 18:16:52', NULL),
(6, 'Liam McCabe', '', '', 'liamrocks@gmail.com', '0841523689', '20 Osborne Road KING\'S PYON HR4 7ZD', '', 'Baby Sitter', '', 1, 2, 1, '2020-02-23 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointmentID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `servName` varchar(255) NOT NULL,
  `totalCost` int(11) NOT NULL,
  `extraServiceIDs` text NOT NULL,
  `appointmentDate` varchar(255) NOT NULL,
  `timingID` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '2',
  `created_date` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointmentID`, `userID`, `serviceID`, `servName`, `totalCost`, `extraServiceIDs`, `appointmentDate`, `timingID`, `status`, `created_date`, `created_at`, `deleted_at`) VALUES
(22, 1, 1, 'Baby Sitting', 0, '[\"1\"]', '2020-04-27', 4, 1, '2020-04-15T13:53:38+00:00', '2020-04-15 13:53:38', NULL),
(23, 1, 1, 'Baby Sitting', 0, '[]', '2020-04-20', 1, 0, '2020-04-15T15:32:52+00:00', '2020-04-15 15:32:52', NULL),
(24, 1, 9, 'Nanny II', 85, '[\"18\",\"17\",\"19\"]', '2020-04-27', 10, 4, '2020-04-17T23:41:10+00:00', '2020-04-17 23:41:10', NULL),
(25, 1, 9, 'Nanny II', 85, '[\"17\",\"18\"]', '2020-04-27', 10, 2, '2020-04-22T02:30:12+01:00', '2020-04-22 02:30:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emailtoken`
--

CREATE TABLE `emailtoken` (
  `tokenID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `usertypeID` int(11) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `token` text NOT NULL,
  `generated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emailtoken`
--

INSERT INTO `emailtoken` (`tokenID`, `userID`, `usertypeID`, `purpose`, `token`, `generated_at`) VALUES
(3, 1, 2, 'password_change', '23267449', '2020-04-19 02:38:42'),
(4, 5, 2, 'email_verify', 'cG93eTZJdGovMTVmaTI0OG9vVUlIaFJjMzRkcUV5bW9jSWc3NWQvR3ZWSm9vNmxOdWpXWHk4amlCeW9KUGhTN3NzZVZhcUdpMnhralFCZHMrR1p2elE9PQ==', '2020-04-22 18:36:56');

-- --------------------------------------------------------

--
-- Table structure for table `extraservice`
--

CREATE TABLE `extraservice` (
  `esID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cost` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `extraservice`
--

INSERT INTO `extraservice` (`esID`, `serviceID`, `name`, `cost`, `created_at`, `deleted_at`) VALUES
(1, 1, 'House-keeping', 5, '2020-03-21 00:00:00', NULL),
(2, 1, 'Dancing', 0, '0000-00-00 00:00:00', '2020-04-16 17:42:15'),
(3, 1, 'Playing', 10, '0000-00-00 00:00:00', '2020-04-16 17:41:49'),
(4, 1, 'Singing', 20, '0000-00-00 00:00:00', NULL),
(5, 1, 'Wailing', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:16'),
(6, 1, 'Looking', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:20'),
(7, 1, 'Pitching', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:32'),
(8, 1, 'Sitting', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:30'),
(9, 1, 'Crying', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:28'),
(10, 1, 'Smiling', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:24'),
(11, 1, 'Running', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:23'),
(12, 1, 'Walking', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:37'),
(13, 1, 'Jumping', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:35'),
(14, 1, 'Skipping', 0, '0000-00-00 00:00:00', '2020-04-16 17:44:39'),
(15, 1, 'Cooking', 0, '2020-04-16 17:20:13', '2020-04-16 17:44:41'),
(16, 1, 'Reading', 0, '2020-04-16 17:20:13', NULL),
(17, 9, 'Napkin Change', 10, '2020-04-17 00:32:45', NULL),
(18, 9, 'Bottle Feeding', 15, '2020-04-17 00:33:28', NULL),
(19, 9, 'Another Extra Service', 0, '2020-04-17 00:34:19', '2020-04-17 00:34:26'),
(20, 16, '', 0, '2020-04-22 14:48:19', '2020-04-22 14:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `loginfailure`
--

CREATE TABLE `loginfailure` (
  `failureID` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loginfailure`
--

INSERT INTO `loginfailure` (`failureID`, `login`, `ip`, `created_at`) VALUES
(25, 'user@gmail.com', '::1', '2020-04-22 00:20:58'),
(24, '`a`', '::1', '2020-04-21 03:38:54'),
(23, '\'a\' = \'a\'', '::1', '2020-04-21 03:22:58'),
(26, 'user@gmail.com', '::1', '2020-04-22 00:22:30'),
(38, 'wil.smith@gmail.com', '::1', '2020-04-22 19:15:27'),
(32, 'agent', '::1', '2020-04-22 18:27:39'),
(40, 'z19122934@spwproject.xyz', '31.205.226.144', '2020-04-22 14:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `loginlog`
--

CREATE TABLE `loginlog` (
  `loginlogID` int(11) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `browser` varchar(128) DEFAULT NULL,
  `operatingsystem` varchar(128) DEFAULT NULL,
  `login` int(11) UNSIGNED DEFAULT NULL,
  `logout` int(11) UNSIGNED DEFAULT NULL,
  `usertypeID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `loginlog`
--

INSERT INTO `loginlog` (`loginlogID`, `ip`, `browser`, `operatingsystem`, `login`, `logout`, `usertypeID`, `userID`) VALUES
(144, '::1', 'Google Chrome', 'windows', 1581480519, 1581480819, 3, 1),
(145, '::1', 'Google Chrome', 'windows', 1581480876, 1581481176, 3, 1),
(146, '::1', 'Google Chrome', 'windows', 1581480920, 1581481220, 3, 1),
(147, '::1', 'Google Chrome', 'windows', 1581596995, 1581597295, 3, 1),
(148, '::1', 'Google Chrome', 'windows', 1581555714, 1581556014, 3, 1),
(149, '::1', 'Google Chrome', 'windows', 1581561717, 1581562017, 3, 1),
(150, '::1', 'Google Chrome', 'windows', 1581561822, 1581562122, 3, 1),
(151, '::1', 'Google Chrome', 'windows', 1582344096, 1582344396, 3, 1),
(152, '::1', 'Google Chrome', 'windows', 1582344810, 1582345110, 3, 1),
(153, '::1', 'Google Chrome', 'windows', 1582367178, 1582367478, 3, 1),
(154, '::1', 'Google Chrome', 'windows', 1582367915, 1582368215, 1, 1),
(155, '::1', 'Apple Safari', 'mac', 1582370186, NULL, 1, 1),
(156, '::1', 'Google Chrome', 'windows', 1582420242, 1582420542, 3, 1),
(157, '::1', 'Google Chrome', 'windows', 1582420302, 1582420602, 1, 1),
(158, '::1', 'Google Chrome', 'windows', 1582420400, 1582420700, 3, 1),
(159, '::1', 'Google Chrome', 'windows', 1582420452, 1582420752, 3, 1),
(160, '::1', 'Google Chrome', 'windows', 1582420503, 1582420803, 3, 1),
(161, '::1', 'Google Chrome', 'windows', 1582450358, 1582450658, 3, 1),
(162, '::1', 'Google Chrome', 'windows', 1582450374, 1582450674, 1, 1),
(163, '::1', 'Google Chrome', 'windows', 1583929197, 1583929497, 3, 1),
(164, '::1', 'Google Chrome', 'windows', 1584164977, 1584165277, 3, 1),
(165, '::1', 'Google Chrome', 'windows', 1584164990, 1584165290, 1, 1),
(166, '::1', 'Google Chrome', 'windows', 1584172540, 1584172840, 3, 1),
(167, '::1', 'Google Chrome', 'windows', 1584510796, 1584511096, 1, 1),
(168, '::1', 'Google Chrome', 'windows', 1584510979, 1584511279, 3, 1),
(169, '::1', 'Google Chrome', 'windows', 1584511164, 1584511464, 1, 1),
(170, '::1', 'Google Chrome', 'windows', 1584511212, 1584511512, 1, 1),
(171, '::1', 'Google Chrome', 'windows', 1584511231, 1584511531, 3, 1),
(172, '::1', 'Google Chrome', 'windows', 1584582541, 1584582841, 1, 1),
(173, '::1', 'Google Chrome', 'windows', 1584583795, 1584584095, 3, 1),
(174, '::1', 'Google Chrome', 'windows', 1584584323, 1584584623, 3, 1),
(175, '::1', 'Google Chrome', 'windows', 1584585177, 1584585477, 1, 1),
(176, '::1', 'Google Chrome', 'windows', 1584585518, 1584585818, 3, 1),
(177, '::1', 'Google Chrome', 'windows', 1584583212, 1584583512, 3, 1),
(178, '::1', 'Google Chrome', 'windows', 1584583615, 1584583915, 3, 1),
(179, '::1', 'Google Chrome', 'windows', 1584583787, 1584584087, 3, 1),
(180, '::1', 'Google Chrome', 'windows', 1584590814, 1584591114, 1, 1),
(181, '::1', 'Google Chrome', 'windows', 1584596313, 1584596613, 1, 1),
(182, '::1', 'Google Chrome', 'windows', 1584610923, 1584611223, 3, 1),
(183, '::1', 'Google Chrome', 'windows', 1584668758, 1584669058, 3, 1),
(184, '::1', 'Google Chrome', 'windows', 1584705008, 1584705308, 3, 1),
(185, '::1', 'Google Chrome', 'windows', 1584674193, 1584674493, 3, 1),
(186, '::1', 'Google Chrome', 'windows', 1584693610, 1584693910, 3, 1),
(187, '::1', 'Google Chrome', 'windows', 1584958804, 1584959104, 3, 1),
(188, '::1', 'Google Chrome', 'windows', 1585050029, 1585050329, 3, 1),
(189, '::1', 'Google Chrome', 'windows', 1585038520, 1585038820, 3, 1),
(190, '::1', 'Google Chrome', 'windows', 1585714680, 1585714980, 3, 1),
(191, '::1', 'Google Chrome', 'windows', 1586834193, 1586834493, 3, 1),
(192, '::1', 'Google Chrome', 'windows', 1586838823, 1586839123, 3, 1),
(193, '::1', 'Google Chrome', 'windows', 1586838861, 1586839161, 3, 1),
(194, '::1', 'Google Chrome', 'windows', 1586852802, 1586853102, 3, 1),
(195, '::1', 'Google Chrome', 'windows', 1586853671, 1586853971, 3, 1),
(196, '::1', 'Google Chrome', 'windows', 1586953746, 1586954046, 3, 1),
(197, '::1', 'Google Chrome', 'windows', 1586925474, NULL, NULL, 1),
(198, '::1', 'Google Chrome', 'windows', 1586926369, 1586926669, 2, 1),
(199, '::1', 'Google Chrome', 'windows', 1586927350, 1586927650, 2, 1),
(200, '::1', 'Google Chrome', 'windows', 1586929154, 1586929454, 3, 1),
(201, '::1', 'Google Chrome', 'windows', 1586929168, 1586929468, 2, 1),
(202, '::1', 'Google Chrome', 'windows', 1586942634, 1586942934, 2, 1),
(203, '::1', 'Google Chrome', 'windows', 1587005544, 1587005844, 2, 1),
(204, '::1', 'Google Chrome', 'windows', 1587035442, 1587035742, 2, 1),
(205, '::1', 'Google Chrome', 'windows', 1587010136, 1587010436, 2, 1),
(206, '::1', 'Google Chrome', 'windows', 1587087037, 1587087337, 2, 1),
(207, '::1', 'Google Chrome', 'windows', 1587126411, 1587126711, 2, 1),
(208, '::1', 'Google Chrome', 'windows', 1587089565, 1587089865, 1, 1),
(209, '::1', 'Google Chrome', 'windows', 1587089977, 1587090277, 2, 1),
(210, '::1', 'Google Chrome', 'windows', 1587090993, 1587091293, 1, 1),
(211, '::1', 'Google Chrome', 'windows', 1587091050, 1587091350, 2, 1),
(212, '::1', 'Google Chrome', 'windows', 1587093208, 1587093508, 1, 1),
(213, '::1', 'Google Chrome', 'windows', 1587093329, 1587093629, 2, 1),
(214, '::1', 'Google Chrome', 'windows', 1587094721, 1587095021, 2, 1),
(215, '::1', 'Google Chrome', 'windows', 1587097085, 1587097385, 1, 1),
(216, '::1', 'Google Chrome', 'windows', 1587097130, 1587097430, 2, 1),
(217, '::1', 'Google Chrome', 'windows', 1587107577, 1587107877, 1, 1),
(218, '::1', 'Google Chrome', 'windows', 1587107662, 1587107962, 2, 1),
(219, '::1', 'Google Chrome', 'windows', 1587109059, 1587109359, 2, 1),
(220, '::1', 'Google Chrome', 'windows', 1587115917, 1587116217, 2, 1),
(221, '::1', 'Google Chrome', 'windows', 1587117764, 1587118064, 2, 1),
(222, '::1', 'Google Chrome', 'windows', 1587118845, 1587119145, 2, 1),
(223, '::1', 'Google Chrome', 'windows', 1587119186, 1587119486, 3, 1),
(224, '::1', 'Google Chrome', 'windows', 1587120449, 1587120749, 1, 1),
(225, '::1', 'Google Chrome', 'windows', 1587120629, 1587120929, 3, 1),
(226, '::1', 'Google Chrome', 'windows', 1587124555, 1587124855, 2, 1),
(227, '::1', 'Google Chrome', 'windows', 1587211372, 1587211672, 3, 1),
(228, '::1', 'Google Chrome', 'windows', 1587211386, 1587211686, 1, 1),
(229, '::1', 'Google Chrome', 'windows', 1587212121, 1587212421, 3, 1),
(230, '::1', 'Google Chrome', 'windows', 1587212171, 1587212471, 1, 1),
(231, '::1', 'Google Chrome', 'windows', 1587212621, 1587212921, 3, 1),
(232, '::1', 'Google Chrome', 'windows', 1587182198, 1587182498, 2, 1),
(233, '::1', 'Google Chrome', 'windows', 1587182210, 1587182510, 2, 1),
(234, '::1', 'Google Chrome', 'windows', 1587182257, 1587182557, 2, 1),
(235, '::1', 'Google Chrome', 'windows', 1587182323, 1587182623, 2, 1),
(236, '::1', 'Google Chrome', 'windows', 1587182471, 1587182771, 2, 1),
(237, '::1', 'Google Chrome', 'windows', 1587182507, 1587182807, 2, 1),
(238, '::1', 'Google Chrome', 'windows', 1587182574, 1587182874, 2, 1),
(239, '::1', 'Google Chrome', 'windows', 1587182793, 1587183093, 2, 1),
(240, '::1', 'Google Chrome', 'windows', 1587184550, 1587184850, 1, 1),
(241, '::1', 'Google Chrome', 'windows', 1587186939, 1587187239, 1, 1),
(242, '::1', 'Google Chrome', 'windows', 1587187970, 1587188270, 1, 1),
(243, '::1', 'Google Chrome', 'windows', 1587189031, 1587189331, 2, 4),
(244, '::1', 'Google Chrome', 'windows', 1587263916, 1587264216, 2, 1),
(245, '::1', 'Google Chrome', 'windows', 1587264305, 1587264605, 2, 4),
(246, '::1', 'Google Chrome', 'windows', 1587267416, 1587267716, 2, 4),
(247, '::1', 'Google Chrome', 'windows', 1587267435, 1587267735, 1, 1),
(248, '::1', 'Google Chrome', 'windows', 1587268047, 1587268347, 1, 1),
(249, '::1', 'Google Chrome', 'windows', 1587269504, 1587269804, 2, 4),
(250, '::1', 'Google Chrome', 'windows', 1587281289, 1587281589, 2, 4),
(251, '::1', 'Google Chrome', 'windows', 1587281655, 1587281955, 2, 4),
(252, '::1', 'Google Chrome', 'windows', 1587346228, 1587346528, 3, 1),
(253, '::1', 'Google Chrome', 'windows', 1587346251, 1587346551, 2, 1),
(254, '::1', 'Google Chrome', 'windows', 1587346389, 1587346689, 3, 1),
(255, '::1', 'Google Chrome', 'windows', 1587347523, 1587347823, 2, 1),
(256, '::1', 'Google Chrome', 'windows', 1587347980, 1587348280, 1, 1),
(257, '::1', 'Google Chrome', 'windows', 1587455098, 1587455398, 3, 1),
(258, '::1', 'Google Chrome', 'windows', 1587555087, 1587555387, 1, 1),
(259, '::1', 'Google Chrome', 'windows', 1587516158, 1587516458, 3, 1),
(260, '::1', 'Google Chrome', 'windows', 1587519826, NULL, 2, 1),
(261, '::1', 'Google Chrome', 'windows', 1587521846, 1587522146, 1, 1),
(262, '::1', 'Google Chrome', 'windows', 1587524179, 1587524479, 3, 1),
(263, '::1', 'Google Chrome', 'windows', 1587554564, 1587554864, 3, 1),
(264, '::1', 'Google Chrome', 'windows', 1587556258, 1587556558, 3, 1),
(265, '::1', 'Google Chrome', 'windows', 1587556318, 1587556618, 3, 1),
(266, '::1', 'Google Chrome', 'windows', 1587527947, NULL, 3, 1),
(267, '::1', 'Google Chrome', 'windows', 1587528038, 1587528338, 1, 1),
(268, '::1', 'Google Chrome', 'windows', 1587533288, NULL, 1, 1),
(269, '::1', 'Google Chrome', 'windows', 1587533384, 1587533684, 2, 4),
(270, '::1', 'Google Chrome', 'windows', 1587535022, NULL, 2, 4),
(271, '::1', 'Google Chrome', 'windows', 1587536157, NULL, 3, 5),
(272, '31.205.226.144', 'Google Chrome', 'windows', 1587537263, NULL, 1, 1),
(273, '31.205.226.144', 'Google Chrome', 'windows', 1587537302, 1587537602, 2, 4),
(274, '31.205.226.144', 'Google Chrome', 'windows', 1587538521, NULL, 3, 5),
(275, '31.205.226.144', 'Google Chrome', 'windows', 1587542319, NULL, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `pendingverification`
--

CREATE TABLE `pendingverification` (
  `vID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `userTypeID` int(11) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `generated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `description` tinytext,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'index-agent', 'View agents', NULL, 1, NULL, NULL, NULL),
(2, 'admin-dashboard', 'dashboard', NULL, 1, NULL, NULL, NULL),
(3, 'index-user', 'View users', NULL, 1, NULL, NULL, NULL),
(4, 'get_agents-agent', 'List agents', NULL, 1, NULL, NULL, NULL),
(5, 'view-agent', 'View agent', NULL, 1, NULL, NULL, NULL),
(6, 'block-agent', 'Block agent', NULL, 1, NULL, NULL, NULL),
(7, 'unblock-agent', 'Unblock agent', NULL, 1, NULL, NULL, NULL),
(8, 'get_users-user', 'List users', NULL, 1, NULL, NULL, NULL),
(9, 'view-user', 'View user', NULL, 1, NULL, NULL, NULL),
(10, 'block-user', 'Block user', NULL, 1, NULL, NULL, NULL),
(11, 'unblock-user', 'Unblock user', NULL, 1, NULL, NULL, NULL),
(12, 'add-user', 'Add user', NULL, 1, NULL, NULL, NULL),
(13, 'get_appointments-dashboard', 'List Appointments', 'List Appointments', 1, '2020-04-16 23:00:00', NULL, NULL),
(14, 'book_appointment-dashboard', 'Book Appointment', NULL, 1, '2020-04-16 23:00:00', NULL, NULL),
(15, 'cancel_appointment-dashboard', 'Cancel Appointment', 'Cancel Appointment', 1, NULL, NULL, NULL),
(16, 'approve_appointment-dashboard', 'Approve Appointment', 'Approve Appointment', 1, '2020-04-16 23:00:00', NULL, NULL),
(17, 'get_agent_services-dashboard', 'Agent Services', 'List Agent Services', 1, '2020-04-23 23:00:00', NULL, NULL),
(18, 'update_service_information-dashboard', 'Update service information', 'Update service information', 1, '2020-04-16 23:00:00', NULL, NULL),
(19, 'delete_service-dashboard', 'Delete service', 'Delete service', 1, '2020-04-16 23:00:00', NULL, NULL),
(20, 'add_service-dashboard', 'Add Service', 'Add service', 1, '2020-04-16 23:00:00', NULL, NULL),
(21, 'delete_timing-dashboard', 'Delete timing', 'Delete timing', 1, '2020-04-16 23:00:00', NULL, NULL),
(22, 'save_timing-dashboard', 'Save Timing', 'Save timing', 1, '2020-04-16 23:00:00', NULL, NULL),
(23, 'save_extra_services-dashboard', 'Add Extra Services', 'Add Extra Services', 1, '2020-04-16 23:00:00', NULL, NULL),
(24, 'delete_extra_service-dashboard', 'Delete Extra Service', 'Delete Extra Service', 1, '2020-04-16 23:00:00', NULL, NULL),
(25, 'index-logview', 'View Logs', 'View Logs', 1, '2020-04-16 23:00:00', NULL, NULL),
(26, 'update_profile-agent', 'Agent update profile', 'Agent profile update', 1, '2020-04-16 23:00:00', NULL, NULL),
(27, 'change_password-agent', 'Agent Change Password', 'Agent Change Password', 1, '2020-04-16 23:00:00', NULL, NULL),
(28, 'change_password-user', 'User Change Password', 'User Change Password', 1, '2020-04-16 23:00:00', NULL, NULL),
(29, 'change_password-admin', 'Admin change profile', 'Admin change profile', 1, '2020-04-16 23:00:00', NULL, NULL),
(30, 'complete_appointment-dashboard', 'Complete Appointment', 'Complete Appointment', 1, '2020-04-16 23:00:00', NULL, NULL),
(31, 'index-dashboard', 'View dashboard', NULL, 1, '2020-04-20 23:00:00', NULL, NULL),
(32, 'get_grouped_services-dashboard', 'Get grouped services', NULL, 1, '2020-04-20 23:00:00', NULL, NULL),
(33, 'sendMessage-dashboard', 'Contact Send Message', 'Contact Send Message', 1, '2020-04-21 23:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_roles`
--

CREATE TABLE `permission_roles` (
  `prID` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permission_roles`
--

INSERT INTO `permission_roles` (`prID`, `role_id`, `permission_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 2, 13),
(15, 3, 13),
(16, 3, 14),
(17, 2, 15),
(18, 3, 15),
(19, 2, 16),
(20, 2, 17),
(21, 2, 18),
(22, 2, 19),
(23, 2, 20),
(24, 2, 21),
(25, 2, 22),
(26, 2, 23),
(27, 2, 24),
(28, 1, 25),
(29, 2, 26),
(30, 2, 27),
(31, 3, 28),
(32, 1, 29),
(33, 2, 30),
(34, 1, 31),
(35, 2, 31),
(36, 3, 31),
(37, 3, 32),
(38, 2, 33),
(39, 3, 33);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `display_name` varchar(30) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin', 'admin', 1, NULL, NULL, NULL),
(2, 'agent', 'agent', 'agent', 1, '2020-03-18 23:00:00', '2020-03-18 23:00:00', NULL),
(3, 'user', 'user', 'user', 1, '2020-03-18 23:00:00', '2020-03-18 23:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE `roles_users` (
  `ruserID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `usertypeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles_users`
--

INSERT INTO `roles_users` (`ruserID`, `user_id`, `role_id`, `usertypeID`) VALUES
(1, 1, 1, 1),
(2, 1, 3, 3),
(3, 5, 3, 3),
(4, 1, 2, 2),
(5, 2, 2, 2),
(7, 4, 2, 2),
(8, 5, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `securityanswer`
--

CREATE TABLE `securityanswer` (
  `answerID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  `usertypeID` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `securityanswer`
--

INSERT INTO `securityanswer` (`answerID`, `questionID`, `answer`, `userID`, `usertypeID`, `created_at`) VALUES
(1, 2, 'Nigeria', 4, 2, '2020-04-18 14:40:33'),
(2, 2, 'Nigeria', 1, 2, '2020-04-18 14:40:33'),
(3, 2, 'Nigeria', 5, 2, '2020-04-22 18:16:52');

-- --------------------------------------------------------

--
-- Table structure for table `securityquestion`
--

CREATE TABLE `securityquestion` (
  `questionID` int(11) NOT NULL,
  `question` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `securityquestion`
--

INSERT INTO `securityquestion` (`questionID`, `question`, `created_at`) VALUES
(1, 'What was your childhood nickname?', '2020-04-18 00:00:00'),
(2, 'In what city did you meet your spouse/significant other?', '2020-04-18 00:00:00'),
(3, 'What is the name of your favorite childhood friend?', '2020-04-18 00:00:00'),
(4, 'What street did you live on in third grade?', '2020-04-18 00:00:00'),
(5, 'What is your oldest sibling’s birthday month and year?', '2020-04-18 00:00:00'),
(6, 'What is the middle name of your youngest child?', '2020-04-18 00:00:00'),
(7, 'What is your oldest sibling\'s middle name?', '2020-04-18 00:00:00'),
(8, 'What school did you attend for sixth grade?', '2020-04-18 00:00:00'),
(9, 'What was your childhood phone number including area code? ', '2020-04-18 00:00:00'),
(10, 'What is your oldest cousin\'s first and last name?', '2020-04-18 00:00:00'),
(11, 'What was the name of your first stuffed animal?', '2020-04-18 00:00:00'),
(12, 'In what city or town did your mother and father meet?', '2020-04-18 00:00:00'),
(13, 'Where were you when you had your first kiss?', '2020-04-18 00:00:00'),
(14, 'What is the first name of the boy or girl that you first kissed?', '2020-04-18 00:00:00'),
(15, 'What was the last name of your third grade teacher?\r\nIn what city does your nearest sibling live?', '2020-04-18 00:00:00'),
(16, 'In what city does your nearest sibling live?', '2020-04-18 00:00:00'),
(17, 'What is your youngest brother’s birthday month and year?', '2020-04-18 00:00:00'),
(18, 'What is your maternal grandmother\'s maiden name', '2020-04-18 00:00:00'),
(19, 'In what city or town was your first job?', '2020-04-18 00:00:00'),
(20, 'What is the name of the place your wedding reception was held?', '2020-04-18 00:00:00'),
(21, 'What is the name of a college you applied to but didn\'t attend?', '2020-04-18 00:00:00'),
(22, 'Where were you when you first heard about 9/11?', '2020-04-18 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `serviceID` int(11) NOT NULL,
  `serviceCategoryID` int(11) NOT NULL,
  `agentID` int(11) NOT NULL,
  `serviceName` varchar(255) NOT NULL,
  `cost` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`serviceID`, `serviceCategoryID`, `agentID`, `serviceName`, `cost`, `status`, `created_at`, `deleted_at`) VALUES
(1, 8, 1, 'Baby Sitting', 10, 0, '2020-03-20 00:00:00', NULL),
(2, 8, 2, 'Baby sitting', 10, 1, '2020-03-20 00:00:00', NULL),
(5, 8, 1, 'Nanny', 40, 1, '2020-04-16 22:46:00', '2020-04-16 23:27:52'),
(6, 8, 1, 'Nanny', 70, 1, '2020-04-16 23:41:19', NULL),
(7, 8, 1, 'Nanny II', 20, 1, '2020-04-16 23:46:19', '2020-04-16 23:51:55'),
(8, 8, 1, 'Nanny II', 60, 1, '2020-04-16 23:53:31', '2020-04-16 23:55:25'),
(9, 8, 1, 'Nanny II', 60, 1, '2020-04-16 23:55:40', NULL),
(10, 7, 1, 'Cooking', 40, 1, '2020-04-17 00:50:20', '2020-04-17 01:04:27'),
(11, 3, 1, 'Painter', 30, 1, '2020-04-17 01:05:06', '2020-04-17 01:08:53'),
(12, 3, 1, 'Painter', 40, 1, '2020-04-17 01:09:08', '2020-04-17 01:14:47'),
(13, 3, 1, 'Painter', 50, 1, '2020-04-17 01:15:01', NULL),
(14, 7, 4, 'Cooking Expert', 20, 1, '2020-04-19 20:29:01', '2020-04-19 20:29:38'),
(15, 7, 4, 'Cooking Expert', 30, 1, '2020-04-19 20:30:06', '2020-04-19 20:39:20'),
(16, 7, 4, 'Cooking Expert', 50, 1, '2020-04-19 20:39:49', '2020-04-22 14:52:31'),
(17, 7, 4, 'Cooking', 30, 1, '2020-04-22 14:52:53', NULL),
(18, 8, 4, 'Something', 10, 1, '2020-04-22 14:54:15', '2020-04-22 14:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `servicecategory`
--

CREATE TABLE `servicecategory` (
  `serviceCategoryID` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `servicecategory`
--

INSERT INTO `servicecategory` (`serviceCategoryID`, `categoryName`, `created_at`, `deleted_at`) VALUES
(1, 'Academic', '2020-02-12', NULL),
(2, 'Accountancy / Finance', '2020-02-12', NULL),
(3, 'Architecture / Design', '2020-02-12', NULL),
(4, 'Banking / Insurance', '2020-02-12', NULL),
(5, 'Big Data / Business Analysis', '2020-02-12', NULL),
(6, 'Call-Centre / Customer Service', '2020-02-12', NULL),
(7, 'Chef', '2020-02-12', NULL),
(8, 'Childcare', '2020-02-12', NULL),
(9, 'Construction / Engineering', '2020-02-12', NULL),
(10, 'Drivers', '2020-02-12', NULL),
(11, 'Education / Training', '2020-02-12', NULL),
(12, 'Energy / Renewable Energy', '2020-02-12', NULL),
(13, 'Financial Services', '2020-02-12', NULL),
(14, 'Fitness and Leisure', '2020-02-12', NULL),
(15, 'Franchise / Business Opportunity', '2020-02-12', NULL),
(16, 'Hair and Beauty', '2020-02-12', NULL),
(17, 'Healthcare / Medical / Nursing', '2020-02-12', NULL),
(18, 'Hotels', '2020-02-12', NULL),
(19, 'IT', '2020-02-12', NULL),
(20, 'Legal', '2020-02-12', NULL),
(21, 'Managers / Supervisors', '2020-02-12', NULL),
(22, 'Manufacturing / Engineering', '2020-02-12', NULL),
(23, 'Marketing / Market Research', '2020-02-12', NULL),
(24, 'Miscellaneous', '2020-02-12', NULL),
(25, 'Motors', '2020-02-12', NULL),
(26, 'Online / Digital Media', '2020-02-12', NULL),
(27, 'Pharmaceutical / Science / Agricultural', '2020-02-12', NULL),
(28, 'Promotions / Merchandising', '2020-02-12', NULL),
(29, 'Property / Facilities Management', '2020-02-12', NULL),
(30, 'Restaurants / Catering', '2020-02-12', NULL),
(31, 'Sales', '2020-02-12', NULL),
(32, 'Security', '2020-02-12', NULL),
(33, 'Telecoms / Tech Support', '2020-02-12', NULL),
(34, 'Trades / Operative / Manual', '2020-02-12', NULL),
(35, 'Travel / Tourism', '2020-02-12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `servicetiming`
--

CREATE TABLE `servicetiming` (
  `timingID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `appday` varchar(50) NOT NULL,
  `starttime` varchar(255) NOT NULL,
  `endtime` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `slots` int(11) NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `servicetiming`
--

INSERT INTO `servicetiming` (`timingID`, `serviceID`, `appday`, `starttime`, `endtime`, `status`, `slots`, `created_at`, `deleted_at`) VALUES
(1, 1, 'mon', '09:30', '11:30', 1, 4, '2020-04-14', NULL),
(2, 1, 'tue', '09:30', '11:30', 1, 4, '2020-04-14', NULL),
(3, 1, 'wed', '09:30', '12:30', 1, 4, '2020-04-14', NULL),
(4, 1, 'mon', '01:30', '03:30', 1, 1, '2020-04-15', NULL),
(5, 1, 'mon', '12:10', '13:30', 1, 1, '2020-04-16', '2020-04-16'),
(6, 1, 'mon', '10:10', '12:30', 1, 1, '2020-04-16', '2020-04-16'),
(7, 1, 'mon', '11:10', '15:30', 0, 1, '2020-04-16', NULL),
(8, 1, 'mon', '12:25', '22:30', 1, 1, '2020-04-16', '2020-04-16'),
(9, 1, 'mon', '12:00', '14:00', 1, 1, '2020-04-16', '2020-04-16'),
(10, 9, 'mon', '12:30', '18:30', 1, 1, '2020-04-17', NULL),
(11, 16, 'mon', '12:30', '13:30', 1, 1, '2020-04-22', '2020-04-22');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `fieldoption` varchar(100) NOT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`fieldoption`, `value`) VALUES
('address', 'Abuja'),
('backend_theme', 'default'),
('captcha_status', '1'),
('currency_code', 'NGN'),
('currency_symbol', 'N'),
('email', 'alesonyeagwa@gmail.com'),
('fontend_theme', 'default'),
('fontorbackend', '0'),
('footer', 'Copyright &copy; iTest'),
('google_analytics', ''),
('language', 'english'),
('language_status', '1'),
('note', '1'),
('phone', '08146034864'),
('photo', 'site.png'),
('purchase_code', ''),
('purchase_username', ''),
('recaptcha_secret_key', ''),
('recaptcha_site_key', ''),
('school_type', 'classbase'),
('school_year', '1'),
('sname', 'iTest'),
('updateversion', '1.0');

-- --------------------------------------------------------

--
-- Table structure for table `systemadmin`
--

CREATE TABLE `systemadmin` (
  `systemadminID` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `dob` date NOT NULL,
  `sex` varchar(10) NOT NULL,
  `religion` varchar(25) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `phone` tinytext,
  `address` text,
  `jod` date NOT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(128) NOT NULL,
  `usertypeID` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_date` datetime NOT NULL,
  `create_userID` int(11) NOT NULL,
  `create_username` varchar(40) NOT NULL,
  `create_usertype` varchar(20) NOT NULL,
  `active` int(11) NOT NULL,
  `systemadminextra1` varchar(128) DEFAULT NULL,
  `systemadminextra2` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `systemadmin`
--

INSERT INTO `systemadmin` (`systemadminID`, `name`, `dob`, `sex`, `religion`, `email`, `phone`, `address`, `jod`, `photo`, `username`, `password`, `usertypeID`, `create_date`, `modify_date`, `create_userID`, `create_username`, `create_usertype`, `active`, `systemadminextra1`, `systemadminextra2`) VALUES
(1, 'Alex', '2019-05-28', 'Male', 'Unknown', 'x19122934@spwproject.xyz', '', '', '2019-05-28', 'defualt.png', 'admin', '$2y$11$rLIRTl83nne/vVP40D.ZsuxjA0h3mZIhTV3FJ.DcsRxV14gmMxNeG', 1, '2019-05-28 01:24:20', '2019-05-28 01:24:20', 0, 'admin', 'Admin', 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `uuid` varchar(50) NOT NULL DEFAULT 'uuid()'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `uuid`) VALUES
(1, 'uuid()');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `usertypeID` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `name`, `username`, `email`, `phone`, `address`, `password`, `photo`, `active`, `usertypeID`, `created`) VALUES
(1, 'James Bons', 'user', 'user@email.com', '0265243315', '', '$2y$11$rLIRTl83nne/vVP40D.ZsuxjA0h3mZIhTV3FJ.DcsRxV14gmMxNeG', '', 1, 3, '2020-02-12 00:00:00'),
(5, 'Will Smith', '', 'will.smith@gmail.com', '0253214578', '', '$2y$11$rLIRTl83nne/vVP40D.ZsuxjA0h3mZIhTV3FJ.DcsRxV14gmMxNeG', '', 1, 3, '2020-03-19 21:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

CREATE TABLE `usertype` (
  `usertypeID` int(11) UNSIGNED NOT NULL,
  `usertype` varchar(60) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_date` datetime NOT NULL,
  `create_userID` int(11) NOT NULL,
  `create_username` varchar(40) NOT NULL,
  `create_usertype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usertype`
--

INSERT INTO `usertype` (`usertypeID`, `usertype`, `create_date`, `modify_date`, `create_userID`, `create_username`, `create_usertype`) VALUES
(1, 'Admin', '2019-05-27 00:00:00', '2019-05-27 00:00:00', 1, 'admin', '1'),
(2, 'Agent', '2019-05-27 00:00:00', '2019-05-27 00:00:00', 1, 'admin', '1'),
(3, 'Users', '2019-05-27 00:00:00', '2019-05-27 00:00:00', 1, 'admin', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agentID`),
  ADD KEY `usertypeID` (`usertypeID`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointmentID`);

--
-- Indexes for table `emailtoken`
--
ALTER TABLE `emailtoken`
  ADD PRIMARY KEY (`tokenID`);

--
-- Indexes for table `extraservice`
--
ALTER TABLE `extraservice`
  ADD PRIMARY KEY (`esID`);

--
-- Indexes for table `loginfailure`
--
ALTER TABLE `loginfailure`
  ADD PRIMARY KEY (`failureID`);

--
-- Indexes for table `loginlog`
--
ALTER TABLE `loginlog`
  ADD PRIMARY KEY (`loginlogID`);

--
-- Indexes for table `pendingverification`
--
ALTER TABLE `pendingverification`
  ADD PRIMARY KEY (`vID`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `permission_roles`
--
ALTER TABLE `permission_roles`
  ADD PRIMARY KEY (`prID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UK_user_roles_role_Name` (`name`);

--
-- Indexes for table `roles_users`
--
ALTER TABLE `roles_users`
  ADD PRIMARY KEY (`ruserID`);

--
-- Indexes for table `securityanswer`
--
ALTER TABLE `securityanswer`
  ADD PRIMARY KEY (`answerID`);

--
-- Indexes for table `securityquestion`
--
ALTER TABLE `securityquestion`
  ADD PRIMARY KEY (`questionID`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`serviceID`);

--
-- Indexes for table `servicecategory`
--
ALTER TABLE `servicecategory`
  ADD PRIMARY KEY (`serviceCategoryID`);

--
-- Indexes for table `servicetiming`
--
ALTER TABLE `servicetiming`
  ADD PRIMARY KEY (`timingID`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`fieldoption`);

--
-- Indexes for table `systemadmin`
--
ALTER TABLE `systemadmin`
  ADD PRIMARY KEY (`systemadminID`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `usertype`
--
ALTER TABLE `usertype`
  ADD PRIMARY KEY (`usertypeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `agentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `emailtoken`
--
ALTER TABLE `emailtoken`
  MODIFY `tokenID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `extraservice`
--
ALTER TABLE `extraservice`
  MODIFY `esID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `loginfailure`
--
ALTER TABLE `loginfailure`
  MODIFY `failureID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `loginlog`
--
ALTER TABLE `loginlog`
  MODIFY `loginlogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `pendingverification`
--
ALTER TABLE `pendingverification`
  MODIFY `vID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `permission_roles`
--
ALTER TABLE `permission_roles`
  MODIFY `prID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles_users`
--
ALTER TABLE `roles_users`
  MODIFY `ruserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `securityanswer`
--
ALTER TABLE `securityanswer`
  MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `securityquestion`
--
ALTER TABLE `securityquestion`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `serviceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `servicecategory`
--
ALTER TABLE `servicecategory`
  MODIFY `serviceCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `servicetiming`
--
ALTER TABLE `servicetiming`
  MODIFY `timingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `systemadmin`
--
ALTER TABLE `systemadmin`
  MODIFY `systemadminID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usertype`
--
ALTER TABLE `usertype`
  MODIFY `usertypeID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
