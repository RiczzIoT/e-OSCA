-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2024 at 04:31 AM
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
-- Database: `senior`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `start`, `end`) VALUES
(2, 'okitnana', '2024-07-26 08:30:00', '2024-07-27 00:00:00'),
(3, 'asdasdasda', '2024-07-24 07:29:00', '2024-07-24 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `senior`
--

CREATE TABLE `senior` (
  `id` int(11) NOT NULL,
  `fingerprint_id` varchar(255) DEFAULT NULL,
  `id_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `sex` varchar(50) NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Divorced') NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `municipal` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `contact_type` enum('Mobile','Landline','Email') DEFAULT NULL,
  `contact` varchar(50) NOT NULL,
  `height` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `citizenship` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `senior`
--

INSERT INTO `senior` (`id`, `fingerprint_id`, `id_number`, `first_name`, `middle_name`, `last_name`, `suffix`, `sex`, `birthplace`, `birthdate`, `age`, `civil_status`, `barangay`, `municipal`, `province`, `contact_type`, `contact`, `height`, `weight`, `citizenship`, `religion`, `profile_picture`, `qr_code`, `created_at`) VALUES
(65, NULL, '27956', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', 'Pantal manaoag pangasinan', '1960-11-11', 63, 'Single', 'Brgy. Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09558923149', NULL, NULL, 'Filipino', 'Manaoag Dos', '../uploads/asdasd.png', '../qr-codes/66a6d3ded0cda.png', '2024-07-28 23:27:26'),
(66, NULL, '28792', 'Romel', 'Castro', 'Aquino', 'Jr.', 'Male', 'Torres Mapandan Pangasinan', '1960-08-26', 63, 'Single', 'Brgy. Torres, Mapandan, Pangasinan', 'Mapandan', 'Pangasinan', 'Mobile', '09318468327', NULL, NULL, 'Filipino', 'Roman Catholic', '../uploads/BSIT.png', '../qr-codes/66a723cad6c95.png', '2024-07-29 05:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `background_image` varchar(255) NOT NULL,
  `favicon` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `capture_image` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `security_settings` varchar(5) DEFAULT NULL,
  `passcode` varchar(255) NOT NULL,
  `auth_code` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `background_image`, `favicon`, `created_at`, `updated_at`, `capture_image`, `logo`, `security_settings`, `passcode`, `auth_code`) VALUES
(16, 21, '../assets/314897888_142517905202148_799826278592392511_n.jpg', '../assets/manaoag.png', '2024-06-26 07:02:56', '2024-07-31 04:40:36', '0', '../assets/manaoag.png', '0', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 0),
(36, 74, '', '', '2024-07-28 23:40:26', '2024-07-31 04:36:57', '0', NULL, '0', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `visitor` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','mayor') DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `login_time` timestamp NULL DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `barangay` varchar(255) DEFAULT '',
  `profile_picture` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `authenticator_secret` varchar(255) DEFAULT NULL,
  `google_auth_secret` varchar(255) NOT NULL,
  `qrCodeUrl` varchar(255) NOT NULL,
  `qr_code_url` varchar(255) NOT NULL,
  `capture_image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `visitor`, `name`, `password`, `role`, `image_path`, `login_time`, `middlename`, `lastname`, `suffix`, `age`, `birthdate`, `barangay`, `profile_picture`, `reset_token`, `qr_code_path`, `authenticator_secret`, `google_auth_secret`, `qrCodeUrl`, `qr_code_url`, `capture_image_path`) VALUES
(21, 'admin@gmail.com', 'superadmin', 'Victor', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin', 'logins/1722099392.png', '2024-07-28 08:02:11', 'asd', 'Fernandez', 'iii', 20, '2003-08-26', 'Laoac, Manaoag, Pangasinan', '272266288_502436367908634_8439006652958756550_n.jpg', '', NULL, 'YHE4LBWASLOAESRP', '', '', '', './uploads/capture_21_1722173945.png'),
(75, 'mayor@gmail.com', '', 'Ricardo', 'ffd81ce261b771ac02fd35e01c22c4f9009d600318eaba27146f41fc9af166c3', 'mayor', NULL, NULL, 'Ragojos', 'Halog', '0', 24, '1999-11-11', 'Brgy. Poblacion, Manaoag, Pangasinan', 'asdasd.png', NULL, NULL, 'NZNDJLDGD32UT52R', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `email`, `name`, `action`, `role`, `log_time`, `image_path`) VALUES
(723, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-28 12:38:13', NULL),
(724, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-28 13:16:51', NULL),
(725, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-28 13:39:18', NULL),
(726, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-28 13:53:03', NULL),
(727, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-28 17:13:27', NULL),
(728, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-29 00:10:48', NULL),
(729, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-29 02:29:42', NULL),
(730, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-29 05:03:36', NULL),
(731, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-29 05:04:26', NULL),
(732, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-31 04:28:29', NULL),
(733, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-31 04:29:43', NULL),
(734, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-31 04:31:07', NULL),
(735, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-07-31 04:35:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `validate`
--

CREATE TABLE `validate` (
  `id` int(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `validate`
--

INSERT INTO `validate` (`id`, `password`) VALUES
(1, '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `senior`
--
ALTER TABLE `senior`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD UNIQUE KEY `id_number_2` (`id_number`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `senior`
--
ALTER TABLE `senior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=736;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `fk_user_logs_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
