-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2020 at 12:11 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ratingreview`
--

-- --------------------------------------------------------

--
-- Table structure for table `question_rating`
--

CREATE TABLE `question_rating` (
  `id` int(11) NOT NULL,
  `form_key` varchar(255) NOT NULL,
  `q_one` text NOT NULL,
  `q_two` int(11) NOT NULL,
  `q_three` int(11) NOT NULL,
  `q_four` text NOT NULL,
  `q_five` int(11) NOT NULL,
  `q_six` text NOT NULL,
  `q_seven` text NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `rated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_rating`
--

INSERT INTO `question_rating` (`id`, `form_key`, `q_one`, `q_two`, `q_three`, `q_four`, `q_five`, `q_six`, `q_seven`, `user_ip`, `rated_at`) VALUES
(1, '', 'No', 55, 66, '', 65, 'Yes', 'No', '', '2020-12-15 10:53:16'),
(2, '', '', 0, 0, '', 0, '', '', '', '2020-12-15 10:53:26'),
(3, '7475d0243518b61b04a2eb2d5c3e7d71', '', 0, 0, '', 0, '', '', '::1', '2020-12-15 10:57:40'),
(4, '7475d0243518b61b04a2eb2d5c3e7d71', 'No', 56, 65, 'Never', 66, 'Yes', 'Yes', '::1', '2020-12-15 11:03:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `question_rating`
--
ALTER TABLE `question_rating`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `question_rating`
--
ALTER TABLE `question_rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
