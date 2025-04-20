-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 17, 2025 at 09:18 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_db`
--
CREATE DATABASE IF NOT EXISTS `if0_38778302_quiz_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `if0_38778302_quiz_db`;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`) VALUES
(1, 1, '235235', '2345345', '345345', '345', '345345', 'B'),
(2, 2, 'ewr', '23r', '23r', '23r', '23r23', 'D'),
(3, 2, '23r23r', '23r23r', '23r', '23r23r', '23r23', 'D'),
(4, 2, 'WERTER', 'ERTER', 'ERTQE', 'QWERQW', 'QWEQW', 'D'),
(5, 3, '124532', '2345', '345', '34534', '135', 'B'),
(6, 4, '124532', '2345', '345', '34534', '135', 'D'),
(7, 5, 'What is your IQ?', '80', '90', '200', '50', 'D'),
(8, 5, 'What is others think of you IQ?', '50', '20', '200', '120', 'B'),
(9, 6, '23r', '23r', '34t', '34t34t', '34t', 'C'),
(10, 6, '34t34t', '34t34t', '34t34', '34t34', '34t34', 'D'),
(11, 7, 'Am I good at valorant?', 'no', 'sure', 'super', 'professional', 'D'),
(12, 7, 'What is my rank?', 'Radiant', 'Bronze', 'Diamond', 'Master', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `Creator` varchar(100) DEFAULT NULL,
  `CreatedTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `duration` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `description`, `Creator`, `CreatedTime`, `duration`) VALUES
(5, 'TestIQ', NULL, 'Robert', '2025-04-16 01:48:11', 45),
(6, '123123', NULL, 'Robert', '2025-04-16 06:12:11', 23),
(7, 'Hello,World', NULL, 'Locker', '2025-04-16 08:04:50', 15);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `score` int NOT NULL,
  `finished_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `quiz_id`, `score`, `finished_time`) VALUES
(1, 2, 5, 1, '2025-04-16 05:59:33'),
(2, 2, 5, 0, '2025-04-16 06:17:40'),
(3, 2, 5, 0, '2025-04-16 06:17:47'),
(4, 3, 5, 0, '2025-04-16 07:30:43'),
(5, 4, 5, 0, '2025-04-16 07:31:12'),
(6, 5, 5, 2, '2025-04-16 07:32:22'),
(7, 5, 6, 1, '2025-04-16 07:41:51'),
(8, 3, 6, 0, '2025-04-16 08:01:04'),
(9, 3, 5, 1, '2025-04-16 08:01:28'),
(10, 3, 7, 2, '2025-04-16 08:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'Chung', '$2y$10$Pa79.KEYrRzozjS3UtRJ5eRgIjmZWvLvo2M.diVd5ITCMLISlJNKm'),
(2, 'Robert', '$2y$10$dO.KZUiAVsQ.fs2W2TZ7aOI5aXYxrBbVj1/Dh8g73yaHxfsp5icoO'),
(3, 'Locker', '$2y$10$j.2DcNGa5GmMhTV8..XCeeZzjzeQXQjlKFbvNzn4ucKNd4I9ghzhC'),
(4, 'Edgar', '$2y$10$jIG45pluR911.IowCeCxaexhs4kKir4nzqwVXBdVomm4BkzKX3n/K'),
(5, 'Law', '$2y$10$x1KsKxHAZe.ezxVGWMal5.6EVXvKStWyRpKzYtwrZh6g56jRD0aQK');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
