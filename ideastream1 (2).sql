-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2025 at 05:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ideastream1`
--

-- --------------------------------------------------------

--
-- Table structure for table `ideas`
--

CREATE TABLE `ideas` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tags` varchar(255) NOT NULL DEFAULT '',
  `video_url` text DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ideas`
--

INSERT INTO `ideas` (`id`, `title`, `description`, `tags`, `video_url`, `user_email`, `view_count`) VALUES
(1, 'phone', 'adad', '', 'https://www.youtube.com/embed/66QUfRlAKOI?si=tXfAcYuzm_HSjEUZ', NULL, 1),
(2, 'phone', 'adad', '', 'https://www.youtube.com/embed/66QUfRlAKOI?si=tXfAcYuzm_HSjEUZ', NULL, 1),
(4, 'a', 'a', '', 'https://www.youtube.com/embed/w-dI8DhB1Bk?si=P8Rf4-KuL3l6r5yz', NULL, 0),
(5, 'sf', 'sf', '', 'https://www.youtube.com/embed/w-dI8DhB1Bk?si=P8Rf4-KuL3l6r5yz', NULL, 1),
(6, 'ad', 'adcx', '', 'https://www.youtube.com/embed/w-dI8DhB1Bk?si=P8Rf4-KuL3l6r5yz', NULL, 0),
(7, 'Abc', 'Test file', '', 'https://www.youtube.com/embed/2VLqZtded_0?si=hnpAAmxhBuKmPB_Z', NULL, 12),
(9, 'Small Business Idea 1', 'Test 2', '', 'https://www.youtube.com/embed/uv87Sa8JP20?si=pCk15SSYoJfKMNeb', NULL, 1),
(10, 'Test 3', 'Trial 1', '', 'https://www.youtube.com/embed/xa2iafnxi9U?si=kgl45WDK7gVbtKxD', NULL, 0),
(11, 'Test 3', 'Trial 2', '', 'https://www.youtube.com/embed/0pTqr2M1z0E?si=QdZFSBG-wtwvRTew', 'abc@gmail.com', 0),
(12, 'Test 3', 'trial 3', '', 'https://www.youtube.com/embed/VlzXmQ1fOo8?si=VipL5lOh5PYqe_1A', 'abc@gmail.com', 3),
(13, 'Test 4', 'Trial 1', '', 'https://www.youtube.com/embed/4Ka_j1VWQO8?si=cAZLlwSRr0kCNNLQ', 'b@gmail.com', 4),
(14, 'Test 4', 'Trial 2', '', 'https://www.youtube.com/embed/-fnLoTcVpso?si=SxWGKo3Sx40nBR7y', 'b@gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `idea_comments`
--

CREATE TABLE `idea_comments` (
  `id` int(11) NOT NULL,
  `idea_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_likes`
--

CREATE TABLE `idea_likes` (
  `id` int(11) NOT NULL,
  `idea_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idea_shares`
--

CREATE TABLE `idea_shares` (
  `id` int(11) NOT NULL,
  `idea_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default-profile.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_pic`) VALUES
(3, 'Max', 'abc@gmail.com', '$2y$10$ateFACkKQ3A2St/VgGPei.OsLb3Zw600Lsp9VqY4PobZJMUpEdGxC', 'uploads/68026e2c487cc.jpg'),
(4, 'Max008', 'b@gmail.com', '$2y$10$zAdToL3fahUaVKhoVRnAl.jMc64eLE.z.QElnK9HBn0PqsffrGINa', 'default-profile.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ideas`
--
ALTER TABLE `ideas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `idea_comments`
--
ALTER TABLE `idea_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idea_id` (`idea_id`);

--
-- Indexes for table `idea_likes`
--
ALTER TABLE `idea_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idea_id` (`idea_id`,`user_email`);

--
-- Indexes for table `idea_shares`
--
ALTER TABLE `idea_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idea_id` (`idea_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ideas`
--
ALTER TABLE `ideas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `idea_comments`
--
ALTER TABLE `idea_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `idea_likes`
--
ALTER TABLE `idea_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `idea_shares`
--
ALTER TABLE `idea_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `idea_comments`
--
ALTER TABLE `idea_comments`
  ADD CONSTRAINT `idea_comments_ibfk_1` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `idea_likes`
--
ALTER TABLE `idea_likes`
  ADD CONSTRAINT `idea_likes_ibfk_1` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `idea_shares`
--
ALTER TABLE `idea_shares`
  ADD CONSTRAINT `idea_shares_ibfk_1` FOREIGN KEY (`idea_id`) REFERENCES `ideas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
