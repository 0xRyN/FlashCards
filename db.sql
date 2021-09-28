-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 05, 2020 at 07:06 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cardFront` varchar(60) NOT NULL DEFAULT 'Front',
  `cardBack` varchar(40) NOT NULL DEFAULT 'Back',
  `cardGroup` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `category` varchar(15) NOT NULL DEFAULT 'other',
  `cardOrder` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=203 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `cardFront`, `cardBack`, `cardGroup`, `name`, `category`, `cardOrder`) VALUES
(138, '7+8=?', '15', '33', 'primary lvl', 'Math', 0),
(139, '3+19=?', '22', '33', 'primary lvl', 'Math', 1),
(140, '6-2=?', '4', '33', 'primary lvl', 'Math', 2),
(141, '3-14=?', '-11', '33', 'primary lvl', 'Math', 3),
(142, '5+3+5=?', '13', '33', 'primary lvl', 'Math', 4),
(134, 'Is python a programming language?', 'yes', '32', 'Real or fake', 'Programming', 1),
(135, 'Is trixor a programming language?', 'no', '32', 'Real or fake', 'Programming', 2),
(136, 'Is perl a programming language?', 'yes', '32', 'Real or fake', 'Programming', 3),
(137, 'Is matlab a programming language?', 'yes', '32', 'Real or fake', 'Programming', 4),
(133, 'Is zopiu a programming language?', 'no', '32', 'Real or fake', 'Programming', 0),
(153, 'IP2 stands for?', 'Initiation &agrave; la programmation', '37', 'SUBJECT NAME', 'Fun', 0),
(154, 'IS1 stands for?', 'Introduction aux syst&egrave;mes', '37', 'SUBJECT NAME', 'Fun', 1),
(155, 'MI2 stands for?', 'Math&eacute;matiques', '37', 'SUBJECT NAME', 'Fun', 2),
(156, 'CI2 stands for?', 'Concepts Informatiques', '37', 'SUBJECT NAME', 'Fun', 3),
(157, 'IO2 stands for?', 'Internet et Outils', '37', 'SUBJECT NAME', 'Fun', 4),
(158, 'What is the capital of Romania?', 'Bucharest', '38', 'Capitals', 'Geography', 0),
(159, 'What is the capital of New Zealand?', 'Wellington', '38', 'Capitals', 'Geography', 1),
(160, 'What is the capital of Switzerland?', 'Bern', '38', 'Capitals', 'Geography', 2),
(161, 'What is the capital of Morocco?', 'Rabat', '38', 'Capitals', 'Geography', 3),
(162, 'What is the capital of Australia?', 'Canberra', '38', 'Capitals', 'Geography', 4),
(163, 'Berlin wall fall?', '1989', '39', 'Wars', 'History', 0),
(164, 'End of WW2?', '1945', '39', 'Wars', 'History', 1),
(165, 'Beginning of WW2?', '1939', '39', 'Wars', 'History', 2),
(166, 'End of WW1?', '1918', '39', 'Wars', 'History', 3),
(167, 'Beginning of WW1?', '1914', '39', 'Wars', 'History', 4),
(168, 'Who is it produced by?', 'Marvel Studios', '40', 'Endgame', 'Other', 0),
(169, 'When was the movie released?', '2019', '40', 'Endgame', 'Other', 1),
(170, 'In million $, how much was the budget?', '356', '40', 'Endgame', 'Other', 2),
(171, 'Who produced it?', 'Kevin Feige', '40', 'Endgame', 'Other', 3),
(172, 'Which superhero dies at the end?', 'Ironman', '40', 'Endgame', 'Other', 4),
(181, '7x6=?', '42', '42', 'More math', 'Math', 3),
(180, '7x9=?', '63', '42', 'More math', 'Math', 2),
(179, '2x4+5x0=?', '8', '42', 'More math', 'Math', 1),
(178, '3+6x2=?', '15', '42', 'More math', 'Math', 0),
(182, '2x3=?', '6', '42', 'More math', 'Math', 4),
(183, 'Le quotient de 724/13=?', '55', '43', 'Divisions', 'Math', 0),
(184, 'Le quotient de 236/54=?', '4', '43', 'Divisions', 'Math', 1),
(185, 'Le quotient de 20/3=?', '6', '43', 'Divisions', 'Math', 2),
(186, '50/4=?', '12.5', '43', 'Divisions', 'Math', 3),
(187, '60/5=?', '12', '43', 'Divisions', 'Math', 4),
(188, 'Which country is the largest (by area)?', 'Russia', '44', 'Which one...', 'Geography', 0),
(189, 'Which country has the biggest GDP per capita?', 'Luxembourg', '44', 'Which one...', 'Geography', 1),
(190, 'Which country has the largest population?', 'China', '44', 'Which one...', 'Geography', 2),
(191, 'Which country has the highest GDP?', 'United States', '44', 'Which one...', 'Geography', 3),
(192, 'Which country is the most visited?', 'France', '44', 'Which one...', 'Geography', 4),
(193, 'What is the slogan of Ajax?', 'Stronger than dirt', '45', 'Slogans', 'Fun', 0),
(194, 'What is the slogan of Nespresso?', 'What else?', '45', 'Slogans', 'Fun', 1),
(195, 'What is the slogan of Coca-cola?', 'Open Happiness', '45', 'Slogans', 'Fun', 2),
(196, 'What is the slogan of Apple?', 'Think Different', '45', 'Slogans', 'Fun', 3),
(197, 'What is the slogan of Nike?', 'Just Do It', '45', 'Slogans', 'Fun', 4),
(198, 'dont report?', 'me', '46', 'Prog :)', 'Programming', 0),
(199, 'dont delete?', 'it', '46', 'Prog :)', 'Programming', 1),
(200, 'this is a good question?', 'yes', '46', 'Prog :)', 'Programming', 2),
(201, 'Add me on instagram?', 'plz', '46', 'Prog :)', 'Programming', 3),
(202, 'What is if?', 'else', '46', 'Prog :)', 'Programming', 4);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `catname` varchar(15) NOT NULL DEFAULT 'other',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `catname`) VALUES
(10, 'Programming'),
(9, 'Math'),
(8, 'Fun'),
(7, 'Other'),
(11, 'Geography'),
(12, 'History');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(12) NOT NULL,
  `category` varchar(15) NOT NULL DEFAULT 'other',
  `uid` int(20) NOT NULL,
  `played` int(20) DEFAULT NULL,
  `liked` int(20) DEFAULT 0,
  `score` int(20) DEFAULT NULL,
  `level` int(5) NOT NULL DEFAULT 1,
  `reports` int(11) NOT NULL DEFAULT 0,
  `description` varchar(150) NOT NULL DEFAULT 'No description',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `category`, `uid`, `played`, `liked`, `score`, `level`, `reports`, `description`) VALUES
(38, 'Capitals', 'Geography', 25, 12, 4, 700, 2, 0, 'You know the capital of France... but what about the others?'),
(33, 'primary lvl', 'Math', 22, 27, 3, 2600, 1, 0, '2+2 is 4 minus 1 that&#039;s 3. Quick Maths'),
(32, 'Real or fake', 'Programming', 22, 29, 3, 2500, 1, 1, 'Do you know every programming language?'),
(37, 'SUBJECT NAME', 'Fun', 22, 6, 0, 110, 3, 1, 'Our semesters...'),
(39, 'Wars', 'History', 25, 26, 5, 2400, 1, 1, 'Bad news'),
(40, 'Endgame', 'Other', 1, 11, 7, 600, 2, 1, 'Avengers - Endgame, the 4th movie !!'),
(42, 'More math', 'Math', 23, 16, 3, 1200, 1, 0, '1x2=2'),
(43, 'Divisions', 'Math', 27, 56, 9, 4900, 2, 2, '10/3 = un peu plus que 3.333333'),
(44, 'Which one...', 'Geography', 28, 8, 1, 700, 1, 2, 'Questions about big things'),
(45, 'Slogans', 'Fun', 26, 93, 15, 7600, 2, 1, 'Do you know the slogan of the companies we live with everyday?'),
(46, 'Prog :)', 'Programming', 27, 23, 0, 240, 1, 19, 'This is pretty good');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `permissions`) VALUES
(1, 'Default', '{\"redactor\": 0, \"admin\": 0}'),
(2, 'Redacteur', '{\"redactor\": 1, \"admin\": 0}'),
(3, 'Administrateur', '{\"redactor\": 1, \"admin\": 1}');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reqid` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `reqid`, `username`) VALUES
(25, 23, 'julie'),
(26, 27, 'maxime');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `name` varchar(40) NOT NULL,
  `joined` datetime NOT NULL DEFAULT current_timestamp(),
  `gid` int(11) NOT NULL DEFAULT 1,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `userScore` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `name`, `joined`, `gid`, `date`, `userScore`) VALUES
(22, 'las', '1468ba2f85add9a672609e922f8c23d1513c1d389fd21b17c34d645586b9aaa8', '14be271321fbbbf2fb4dcae22688ac74', 'Louis', '2020-04-30 19:21:56', 3, '2020-04-30', 89),
(1, 'superuser', '8d51d4c86c9c351eea8bdb0eedcef9af517c80554709463d4da0198f7bb80b1c', '2498285612cfa737fc90c6a50b810baf', 'superuser', '2020-04-27 13:07:25', 3, '2020-04-27', 25),
(25, 'creator', '2c9b8f7b2207a9238a3514276cc8357c0adfd7f8a033122fb860e30951563b75', 'faf50b76c8160beb9728de1a96a379d8', 'creator', '2020-05-02 20:07:02', 1, '2020-05-02', 2),
(23, 'julie', '74e24adf6e6882ef12c74e6546c0a36fccda83b3ce71a9430521b3e480187cb3', 'd768aab849486ff76bf8f704309d1aa8', 'Julie', '2020-05-02 11:39:59', 2, '2020-05-02', 1),
(26, 'rayan', '462a370de918161bce29a5ffaf2abbc331804c68fedcc23a4b90993018956d28', 'd228e499cce8f426531c85a0feb4814c', 'Rayan', '2020-05-04 13:24:59', 3, '2020-05-04', 43),
(27, 'maxime', 'f84b86258682ac42338ccd0f70fddefdf288935f9ebfef1f3a50a0d04c28741a', '57621a6d7a954323ec002bfe67b4ce77', 'Maxime', '2020-05-04 13:47:11', 2, '2020-05-04', 12),
(28, 'marina', '0a9693603ee568da3c4addc2ebe54dfcd1fdfcee1197d75623a2f1aee4d1c81e', '903e5d523d54c5ccb287f1fdca3d0d21', 'Marina', '2020-05-04 13:47:39', 2, '2020-05-04', 16);

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

DROP TABLE IF EXISTS `users_session`;
CREATE TABLE IF NOT EXISTS `users_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_session`
--

INSERT INTO `users_session` (`id`, `user_id`, `hash`) VALUES
(68, 17, '9bc156d9bdce63b4d7e45abee6ac7bda722c163a6f00c148bbab74be26f84ae1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
