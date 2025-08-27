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
-- Database: `barangay_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangay_officials`
--

CREATE TABLE `barangay_officials` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(25) NOT NULL,
  `address` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_officials`
--

INSERT INTO `barangay_officials` (`id`, `full_name`, `contact_number`, `email`, `address`, `position`, `profile_picture`) VALUES
(19, 'HON. NOEL S. BIALA', '', '', '', 'Punong Barangay', ''),
(26, 'MRS. LEIZL M. RAMIREZ', '', '', '', 'Barangay Secretary', ''),
(27, 'MR. JOSHUA D. DELOS REYES', '', '', '', 'Barangay Treasurer', ''),
(29, 'HON. DIEGO G. CASTRO', '', '', '', 'SK Chairman', ''),
(30, 'HON. CHRISTOPHER P. CASTRO', '', '', '', 'Barangay Kagawad', ''),
(32, 'HON. RAYMOND V. LACASTE', '', '', '', 'Barangay Kagawad', ''),
(33, 'HON. JOEY G. PASCUA', '', '', '', 'Barangay Kagawad', ''),
(34, 'HON. JUAN C. CAAGUSAN', '', '', '', 'Barangay Kagawad', ''),
(35, 'HON. PHILIP F. BIALA', '', '', '', 'Barangay Kagawad', ''),
(36, 'HON. ABRAHAM A. VISPERAS', '', '', '', 'Barangay Kagawad', ''),
(37, 'HON. RENATO S. MARQUEZ', '', '', '', 'Barangay Kagawad', ''),
(38, 'Ricardo R. Halog, Jr.', '09558923149', 'recardo09398658022@gmail.', 'dasdaljk', 'Punong Barangay', '');

-- --------------------------------------------------------

--
-- Table structure for table `certification`
--

CREATE TABLE `certification` (
  `id` int(11) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `address` varchar(255) NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `residency_start_date` date DEFAULT NULL,
  `age` int(11) NOT NULL,
  `requestor` varchar(50) DEFAULT NULL,
  `barangay` varchar(255) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `civil_status` enum('Single','Married','Widowed','Divorced') NOT NULL,
  `contact_type` enum('Mobile','Landline','Email') DEFAULT NULL,
  `mothers_name` varchar(50) NOT NULL,
  `fathers_name` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `height` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `citizenship` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `occupation_status` varchar(50) DEFAULT NULL,
  `occupation` varchar(50) DEFAULT NULL,
  `business_name` varchar(255) NOT NULL,
  `adding_occupation` varchar(50) DEFAULT NULL,
  `unit_room_floor` varchar(50) DEFAULT NULL,
  `building_name` varchar(50) DEFAULT NULL,
  `lot` varchar(50) DEFAULT NULL,
  `block` varchar(50) DEFAULT NULL,
  `phase` varchar(50) DEFAULT NULL,
  `house_number` varchar(50) NOT NULL,
  `street` varchar(50) DEFAULT NULL,
  `subdivision` varchar(50) DEFAULT NULL,
  `zone_no` varchar(50) NOT NULL,
  `address_type` varchar(50) NOT NULL,
  `certificate_type` varchar(255) NOT NULL,
  `count_certificate` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `legal_age_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certification`
--

INSERT INTO `certification` (`id`, `id_number`, `first_name`, `middle_name`, `last_name`, `suffix`, `sex`, `address`, `birthplace`, `birthdate`, `residency_start_date`, `age`, `requestor`, `barangay`, `issue_date`, `civil_status`, `contact_type`, `mothers_name`, `fathers_name`, `contact`, `height`, `weight`, `citizenship`, `religion`, `occupation_status`, `occupation`, `business_name`, `adding_occupation`, `unit_room_floor`, `building_name`, `lot`, `block`, `phase`, `house_number`, `street`, `subdivision`, `zone_no`, `address_type`, `certificate_type`, `count_certificate`, `created_at`, `legal_age_date`) VALUES
(285, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'adfad', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', '', '', '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-06-30 23:35:32', '0000-00-00'),
(286, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'cararak arotjas4', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', '', '', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', '', '', '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-RESIDENCY', '', '2024-07-01 02:40:30', '0000-00-00'),
(287, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'ricardo', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', '', '', '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 02:47:30', '0000-00-00'),
(288, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'adgv', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', '', '', '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:05:33', '0000-00-00'),
(289, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'fetwtg', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'ocltai-rwikg]a]', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:07:47', '0000-00-00'),
(290, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'dsoctlwta]g', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'etvwtwg', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:10:31', '0000-00-00'),
(291, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'dst agkeb', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'ewopkatsrjtm', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:12:50', '0000-00-00'),
(292, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'dsgkdsjgs', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'srwjhij', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:13:44', '0000-00-00'),
(293, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'sgkjs', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'Municipality of Manaoag', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:15:46', '0000-00-00'),
(294, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'tkvjea oj', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'aewtkviyea', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:19:57', '0000-00-00'),
(295, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'aslskjemh', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'International Container Terminal Services', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:21:19', '0000-00-00'),
(296, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'wectkjaehy', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'International Container Terminal Services', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:22:11', '0000-00-00'),
(297, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'ajfalkfj', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'International Container Terminal Services', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:22:45', '0000-00-00'),
(298, 'TORRES-WCPN1D41E', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal manaoag pangasinan', '1999-11-11', '2024-07-01', 24, 'skgl;k', 'Pantal, Manaoag, Pangasinan', '2024-07-01', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09558923149', '178', '60', 'Filipino', 'Manaoag Dos', 'Municipality of Manaoag', 'Self employee', 'International Container Terminal Services', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-01 03:23:27', '0000-00-00'),
(299, 'TORRES-F7KIHCP1M', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal Manaoag Pangasinan', '1999-11-11', '2024-07-10', 24, 'Carlos R Halog', 'Pantal', '2024-07-10', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo R. Halog Jr.', '09558923149', '187', '60', 'Filipino', 'Manaoag Dos', 'self employee', 'Web Developer', 'dfgkdf;lgkdf', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-PERMIT', '', '2024-07-10 15:04:25', '0000-00-00'),
(300, 'TORRES-F7KIHCP1M', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', '', 'Pantal Manaoag Pangasinan', '1999-11-11', '2024-07-14', 24, 'sgsdg', 'Pantal', '2024-07-14', 'Single', 'Mobile', 'Flordeliza R. Halog', 'Ricardo R. Halog Jr.', '09558923149', '187', '60', 'Filipino', 'Manaoag Dos', 'self employee', 'Web Developer', 'cxvSDGSgs', NULL, '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', 'Permanent', 'BARANGAY-CLEARANCE', '', '2024-07-13 22:46:51', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(7) DEFAULT '#007bff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Divorced') NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `contact_type` enum('Mobile','Landline','Email') DEFAULT NULL,
  `mothers_name` varchar(255) NOT NULL,
  `fathers_name` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `height` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `citizenship` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `occupation_status` varchar(50) DEFAULT NULL,
  `occupation` varchar(50) DEFAULT NULL,
  `adding_occupation` varchar(50) DEFAULT NULL,
  `unit_room_floor` varchar(50) DEFAULT NULL,
  `building_name` varchar(50) DEFAULT NULL,
  `lot` varchar(50) DEFAULT NULL,
  `block` varchar(50) DEFAULT NULL,
  `phase` varchar(50) DEFAULT NULL,
  `house_number` varchar(50) NOT NULL,
  `street` varchar(50) DEFAULT NULL,
  `subdivision` varchar(50) DEFAULT NULL,
  `zone_no` varchar(50) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address_type` enum('Permanent','Present','Birth Place') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `id_number`, `first_name`, `middle_name`, `last_name`, `suffix`, `sex`, `birthplace`, `birthdate`, `age`, `civil_status`, `barangay`, `contact_type`, `mothers_name`, `fathers_name`, `contact`, `height`, `weight`, `citizenship`, `religion`, `occupation_status`, `occupation`, `adding_occupation`, `unit_room_floor`, `building_name`, `lot`, `block`, `phase`, `house_number`, `street`, `subdivision`, `zone_no`, `profile_picture`, `qr_code`, `created_at`, `address_type`) VALUES
(47, 'TORRES-F7KIHCP1M', 'Ricardo', 'Ragojos', 'Halog', 'Jr.', 'Male', 'Pantal Manaoag Pangasinan', '1999-11-11', 24, 'Single', 'Pantal', 'Mobile', 'Flordeliza R. Halog', 'Ricardo R. Halog Jr.', '09558923149', '187', '60', 'Filipino', 'Manaoag Dos', 'self employee', 'Web Developer', '', '', 'Halog Compound', '', '', '', '551', 'Sitio Madriaga', '', '7', '../uploads/120836660_1204392729931158_7782782165583981211_n.jpg', '../qr-codes/668e9f437289f.png', '2024-07-10 14:48:35', 'Permanent'),
(48, 'TORRES-ITYIO4N3D', 'Michaela', 'Peter', 'Armstrong', 'Jr.', 'Male', 'Manaoag, Pangasinan', '2000-12-12', 60, 'Single', 'Torres mapandan pangasinan', 'Mobile', 'Flordeliza R. Halog', 'Ricardo F. Halog Sr.', '09318468327', '178', '60', 'Filipino', 'Roman Catholic', 'previous', 'Self employee', '', '', 'sddasd', '1', '122', '', 'Halog Compound', 'pantal', 'waewae', '7', '', '../qr-codes/6694cbcaa42e2.png', '2024-07-15 07:12:10', 'Permanent');

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
  `security_settings` int(1) NOT NULL DEFAULT 0,
  `passcode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `background_image`, `favicon`, `created_at`, `updated_at`, `capture_image`, `logo`, `security_settings`, `passcode`) VALUES
(16, 21, '../assets/445609468_447506957915290_6030978488922668036_n.jpg', '../assets/torres.png', '2024-06-26 07:02:56', '2024-07-29 05:49:37', '0', '../assets/torres.png', 0, '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'),
(18, 29, '', '../assets/torres.png', '2024-06-29 02:54:59', '2024-06-29 08:05:03', '', '../assets/torres.png', 0, ''),
(19, 28, '', '../assets/torres.png', '2024-06-29 03:04:40', '2024-06-30 07:25:19', '', '../assets/torres.png', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','captain') NOT NULL DEFAULT 'staff',
  `image_path` varchar(255) DEFAULT NULL,
  `login_time` timestamp NULL DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `barangay` varchar(255) DEFAULT '',
  `profile_picture` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`, `role`, `image_path`, `login_time`, `middlename`, `lastname`, `suffix`, `age`, `birthdate`, `barangay`, `profile_picture`, `reset_token`) VALUES
(21, 'admin@gmail.com', 'Romel', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin', 'logins/1719759143.png', '2024-07-30 11:30:55', 'Castro', 'Aquino', '', 20, '2003-08-26', ' Torres Mapanda Pangasinan', '447641512_2663966870431311_6004538159833643640_n.jpg', ''),
(28, 'captain@gmail.com', 'Barangay', '2780b8eef998d6eaca5dbfe00e4043e626c79bc214195e1848af17a85d51519c', 'captain', NULL, '2024-07-13 18:55:21', 'Captain', 'Torres', '0', 24, '0001-01-01', 'Torres mapandan pangasinan', '450366307_122119708736325932_117611171693487544_n.jpg', NULL),
(29, 'staff@gmail.com', 'Staff', '1562206543da764123c21bd524674f0a8aaf49c8a89744c97352fe677f7e4006', 'staff', NULL, '2024-07-13 19:43:54', 'Barangay', 'Torres', '0', 24, '0001-01-01', 'Torres mapandan pangasinan', '52915d71126a11969e9398c73cdcc38c.jpg', NULL);

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
(550, 21, 'admin@gmail.com', 'Romel', 'Logged in', 'admin', '2024-07-15 07:10:46', 'logins/1719759143.png'),
(551, 21, 'admin@gmail.com', 'Romel', 'Logged in', 'admin', '2024-07-29 05:41:42', 'logins/1719759143.png'),
(552, 21, 'admin@gmail.com', 'Romel', 'Logged in', 'admin', '2024-07-29 05:49:31', 'logins/1719759143.png'),
(553, 21, 'admin@gmail.com', 'Romel', 'Logged in', 'admin', '2024-07-30 11:30:55', 'logins/1719759143.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
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
-- AUTO_INCREMENT for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `certification`
--
ALTER TABLE `certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=554;

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
