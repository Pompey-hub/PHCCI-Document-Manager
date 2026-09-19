-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2026 at 05:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phcci_document_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_extension` varchar(20) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT 0,
  `file_path` text NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','deleted') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `user_id`, `folder_id`, `file_name`, `original_name`, `file_extension`, `file_type`, `file_size`, `file_path`, `uploaded_at`, `status`) VALUES
(3, 1, NULL, '1780451017_6a1f86c9864d4.rar', 'de_ascent_v1_map.rar', 'rar', 'application/x-compressed', 1715092, '../uploads/1/1780451017_6a1f86c9864d4.rar', '2026-06-03 01:43:37', 'active'),
(4, 1, NULL, '1780452426_6a1f8c4a660a6.rar', 'css_overpass_map.rar', 'rar', 'application/x-compressed', 7350495, '../uploads/1/1780452426_6a1f8c4a660a6.rar', '2026-06-03 02:07:06', 'deleted'),
(5, 1, NULL, '1780453540_6a1f90a45ad1e.rar', 'css_mirage_go_7a7ba_7765f.rar', 'rar', 'application/x-compressed', 11586498, '../uploads/1/1780453540_6a1f90a45ad1e.rar', '2026-06-03 02:25:40', 'active'),
(6, 1, NULL, '1781920197_6a35f1c58e8a3.rar', '1780451017_6a1f86c9864d4.rar', 'rar', 'application/x-compressed', 1715092, '../uploads/1/1781920197_6a35f1c58e8a3.rar', '2026-06-20 01:49:57', 'active'),
(7, 1, NULL, '1781920200_6a35f1c8cb5ca.rar', '1780451017_6a1f86c9864d4.rar', 'rar', 'application/x-compressed', 1715092, '../uploads/1/1781920200_6a35f1c8cb5ca.rar', '2026-06-20 01:50:00', 'active'),
(8, 1, NULL, '1781920662_6a35f3969b9c6.rar', 'de_cache_979f6.rar', 'rar', 'application/x-compressed', 7381626, '../uploads/1/1781920662_6a35f3969b9c6.rar', '2026-06-20 01:57:42', 'active'),
(9, 1, NULL, '1781927828_6a360f94c6b7d.rar', 'de_cache_979f6.rar', 'rar', 'application/x-compressed', 7381626, '../uploads/1/1781927828_6a360f94c6b7d.rar', '2026-06-20 03:57:08', 'deleted'),
(10, 1, NULL, '1781927853_6a360fadc4c34.rar', 'de_cache_979f6.rar', 'rar', 'application/x-compressed', 7381626, '../uploads/1/1781927853_6a360fadc4c34.rar', '2026-06-20 03:57:33', 'deleted'),
(11, 1, 6, '1781928152_6a3610d8df9d9.rar', 'de_cache_979f6.rar', 'rar', 'application/x-compressed', 7381626, '../uploads/1/1781928152_6a3610d8df9d9.rar', '2026-06-20 04:02:32', 'active'),
(12, 1, 6, '1781928168_6a3610e89f56f.rar', '1780451017_6a1f86c9864d4.rar', 'rar', 'application/x-compressed', 1715092, '../uploads/1/1781928168_6a3610e89f56f.rar', '2026-06-20 04:02:48', 'deleted'),
(13, 1, 6, '1781928173_6a3610edb5cb0.rar', '1780451017_6a1f86c9864d4.rar', 'rar', 'application/x-compressed', 1715092, '../uploads/1/1781928173_6a3610edb5cb0.rar', '2026-06-20 04:02:53', 'active'),
(14, 1, NULL, '1781934140_6a36283c04980.rar', 'de_cache_979f6.rar', 'rar', 'application/x-compressed', 7381626, '../uploads/1/1781934140_6a36283c04980.rar', '2026-06-20 05:42:20', 'active'),
(15, 1, 6, '1782090960_6a388cd00db30.rar', 'aim_blood_well.rar', 'rar', 'application/x-compressed', 552091, '../uploads/1/1782090960_6a388cd00db30.rar', '2026-06-22 01:16:00', 'active'),
(16, 1, 6, '1782369336_6a3ccc389391c.rar', '51school_2.rar', 'rar', 'application/x-compressed', 3066710, '../uploads/1/1782369336_6a3ccc389391c.rar', '2026-06-25 06:35:36', 'active'),
(17, 2, 20, '1782435703_6a3dcf7733e6f.rar', '51school_2.rar', 'rar', 'application/x-compressed', 3066710, '../uploads/2/1782435703_6a3dcf7733e6f.rar', '2026-06-26 01:01:43', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `file_shares`
--

CREATE TABLE `file_shares` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `shared_to` int(11) NOT NULL,
  `permission` enum('view','edit') DEFAULT 'view',
  `shared_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_shares`
--

INSERT INTO `file_shares` (`id`, `file_id`, `owner_id`, `shared_to`, `permission`, `shared_at`) VALUES
(1, 8, 1, 2, 'view', '2026-06-26 01:11:11');

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_folder_id` int(11) DEFAULT NULL,
  `folder_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','deleted') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `user_id`, `parent_folder_id`, `folder_name`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, NULL, 'folder', '2026-06-01 06:05:14', '2026-06-23 07:56:24', 'deleted'),
(2, 1, NULL, 'folder1', '2026-06-01 06:06:24', '2026-06-23 07:56:39', 'deleted'),
(3, 1, NULL, 'folder2', '2026-06-01 06:07:27', '2026-06-01 07:28:27', 'deleted'),
(4, 1, NULL, 'documents', '2026-06-01 06:07:52', '2026-06-03 05:53:22', 'deleted'),
(5, 1, NULL, 'MIS', '2026-06-01 06:19:16', '2026-06-01 06:19:16', 'active'),
(6, 1, NULL, 'HR', '2026-06-01 06:19:50', '2026-07-29 05:53:58', 'active'),
(7, 1, NULL, 'RECORDS', '2026-06-01 06:25:15', '2026-06-01 07:57:11', 'active'),
(8, 1, NULL, 'IT', '2026-06-01 06:33:51', '2026-06-23 07:43:52', 'active'),
(9, 1, NULL, 'MEDIATION', '2026-06-03 03:21:36', '2026-06-20 01:17:41', 'deleted'),
(10, 1, NULL, 'HR INSIDE', '2026-06-20 05:41:46', '2026-06-22 01:15:28', 'deleted'),
(11, 1, NULL, 'inside folder', '2026-06-22 00:20:03', '2026-06-22 00:20:18', 'deleted'),
(12, 1, NULL, 'inside folder', '2026-06-22 01:15:02', '2026-06-22 01:15:15', 'deleted'),
(13, 1, NULL, 'inside folder', '2026-06-22 01:16:15', '2026-06-23 06:51:53', 'deleted'),
(14, 1, NULL, 'inside folder', '2026-06-23 06:52:08', '2026-06-23 06:55:45', 'deleted'),
(15, 1, NULL, 'inside folder', '2026-06-23 06:59:18', '2026-06-23 07:43:23', 'active'),
(16, 1, 6, 'inside folder 1', '2026-06-23 07:00:57', '2026-06-23 07:01:18', 'deleted'),
(17, 1, NULL, 'outside folder1', '2026-06-23 07:01:10', '2026-06-25 00:22:08', 'deleted'),
(18, 1, 6, 'inside folder 2', '2026-06-25 00:22:17', '2026-06-25 06:35:27', 'deleted'),
(19, 1, 6, 'inside folder 3', '2026-06-25 06:35:21', '2026-06-25 06:35:21', 'active'),
(20, 2, NULL, 'shared_folder', '2026-06-26 01:01:35', '2026-06-26 02:06:57', 'active'),
(21, 2, 20, 'inside folder', '2026-06-26 07:35:34', '2026-06-26 07:35:34', 'active'),
(22, 1, NULL, 'test', '2026-07-08 03:20:23', '2026-07-08 03:20:23', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `folder_shares`
--

CREATE TABLE `folder_shares` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `shared_to` int(11) NOT NULL,
  `permission` enum('view','edit') DEFAULT 'view',
  `shared_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folder_shares`
--

INSERT INTO `folder_shares` (`id`, `folder_id`, `owner_id`, `shared_to`, `permission`, `shared_at`) VALUES
(1, 6, 1, 2, 'view', '2026-06-25 08:24:11'),
(2, 20, 2, 1, 'view', '2026-06-26 01:01:57'),
(3, 22, 1, 2, 'view', '2026-07-08 03:20:48'),
(4, 8, 1, 2, 'view', '2026-07-08 06:49:37'),
(5, 6, 1, 2, 'view', '2026-07-29 06:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('SuperAdmin','Admin','Staff') DEFAULT 'Staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Francisco', 'Tingzon', 'tingzonfrancisco@gmail.com', '$2y$10$JGeNFrz1SCSslYOM0NnVwO0cIPfG3D0OJU/6plNHBMboXT.Xi6mFG', 'Staff', '2026-05-26 03:48:13'),
(2, 'Jessie Jaike', 'Badidles', 'jessiejaikebaddles@gmail.com', '$2y$10$TgstgZ2oozL/RGEeP07yQOri82sFGd0Uf.uGSBUE3q4cAJLwBp0/S', 'Staff', '2026-05-26 06:58:52'),
(3, 'Kim ', 'Rosaldo', 'kbrosaldo@gmail.com', '$2y$10$Y/SuAms5EVAb6voz48bVE.3T3KCeHIqlyJWk/fxIEdO8PWluXk2Oi', 'Staff', '2026-07-08 09:00:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_file_user` (`user_id`),
  ADD KEY `fk_file_folder` (`folder_id`);

--
-- Indexes for table `file_shares`
--
ALTER TABLE `file_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_file_share_file` (`file_id`),
  ADD KEY `fk_file_share_owner` (`owner_id`),
  ADD KEY `fk_file_share_user` (`shared_to`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_folder_user` (`user_id`),
  ADD KEY `fk_parent_folder` (`parent_folder_id`);

--
-- Indexes for table `folder_shares`
--
ALTER TABLE `folder_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_folder_share_folder` (`folder_id`),
  ADD KEY `fk_folder_share_owner` (`owner_id`),
  ADD KEY `fk_folder_share_user` (`shared_to`);

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
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `file_shares`
--
ALTER TABLE `file_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `folder_shares`
--
ALTER TABLE `folder_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_file_folder` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_file_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_shares`
--
ALTER TABLE `file_shares`
  ADD CONSTRAINT `fk_file_share_file` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_file_share_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_file_share_user` FOREIGN KEY (`shared_to`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `fk_folder_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_parent_folder` FOREIGN KEY (`parent_folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `folder_shares`
--
ALTER TABLE `folder_shares`
  ADD CONSTRAINT `fk_folder_share_folder` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_folder_share_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_folder_share_user` FOREIGN KEY (`shared_to`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
