-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2025 at 08:49 PM
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
-- Table structure for table `delete_logs`
--

CREATE TABLE `delete_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `senior_id` int(11) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipient_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `recipient_id`, `email`, `subject`, `body`, `timestamp`, `is_read`) VALUES
(5, 21, '21', 'admin@gmail.com', 'Starting Over Again', 'And when I hold you in my arms I promise you\r\nYou\'re gonna feel a love that\'s beautiful and new\r\nThis time I\'ll love you even better than I ever did before\r\nAnd you\'ll be in my heart forevermore\r\nWe were just too young to know\r\nWe fell in love and let it go\r\nSo easy to say the words goodbye\r\nSo hard to let the feeling die\r\nI know how much I need you now\r\nThe time is turning back somehow\r\nAs soon as our hearts and souls unite\r\nI know for sure we\'ll get the feeling right\r\nAnd now we\'re starting over again\r\nIt\'s not the easiest thing to do\r\nI\'m feeling inside again\r\n\'Cause every time I look at you\r\nI know we\'re starting over again\r\nThis time we\'ll love all the pain away\r\nWelcome home, my lover and friend\r\nWe are starting over, over again', '2024-08-04 01:57:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `senior`
--

CREATE TABLE `senior` (
  `id` int(11) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `sex` varchar(50) NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `civil_status` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `municipal` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `contact_type` enum('Mobile','Landline','Email') DEFAULT NULL,
  `contact` varchar(50) NOT NULL,
  `emergency` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sss` varchar(255) DEFAULT NULL,
  `gsis` varchar(255) DEFAULT NULL,
  `philhealth` varchar(255) DEFAULT NULL,
  `classification` varchar(255) DEFAULT NULL,
  `blood_type` varchar(255) NOT NULL,
  `highest_educ_attainment` varchar(255) NOT NULL,
  `employment_status` varchar(255) NOT NULL,
  `monthly_pension` text NOT NULL,
  `citizenship` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','pending','deleted') DEFAULT 'active',
  `deleted_by` int(11) DEFAULT NULL,
  `requested_by` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `delete_requested_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `senior`
--

INSERT INTO `senior` (`id`, `id_number`, `first_name`, `middle_name`, `last_name`, `suffix`, `sex`, `birthplace`, `birthdate`, `age`, `civil_status`, `barangay`, `municipal`, `province`, `contact_type`, `contact`, `emergency`, `email`, `sss`, `gsis`, `philhealth`, `classification`, `blood_type`, `highest_educ_attainment`, `employment_status`, `monthly_pension`, `citizenship`, `religion`, `profile_picture`, `qr_code`, `created_at`, `status`, `deleted_by`, `requested_by`, `approved`, `delete_requested_by`) VALUES
(104, '62308', 'John Peter', 'M.', 'Lacaste', 'III', 'Male', 'Pantal', '1960-11-11', 64, 'Married', 'Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09558923149', '09558923149', 'ricardohaloglll@gmail.com', '234234234235', '24234234234', '345345345345', 'Pensioner', 'B+', 'College', '', 'P45,000.00-P49,999.00', 'Filipino', 'Roman Catholic', '../uploads/9e6c127b-9eb6-4243-8431-e7cfd7052440.jpg', '../../qr-codes/674694efab321.png', '2024-11-12 12:05:03', 'active', NULL, '56', 0, NULL),
(107, '63845', 'Rizaldy', 'S.', 'Malapit', '', 'Male', 'Manaoag', '1945-03-12', 79, 'Married', 'Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09388609993', '', 'xjohnstepx@gmail.com', '8723827872', '2783278738178', 'y68238328623', 'Pensioner', 'O+', 'College', '', 'P20,000.00-P24,999.00', 'Filipino', 'Islam', '', '../../qr-codes/6739eed39708a.png', '2024-11-12 13:55:05', 'active', NULL, '56', 0, NULL),
(108, '93013', 'Ashlley Mae', 'Soriano', 'Salas', '', 'Female', 'Manaoag Community ', '1964-05-03', 60, 'Married', 'Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09187588051', '', 'ashlleymaesalas@gmail.com', '8374978473', '2902909209', '82828782787', 'Pensioner', 'O+', 'College', '', 'P35,000.00-P39,999.00', 'Filipino', 'Islam', '../uploads/454204614_901944125094908_5840748963320083505_n.jpg', '../../qr-codes/6739ed68dfa69.png', '2024-11-13 13:05:49', 'active', NULL, NULL, 0, NULL),
(109, '76466', 'Ariel', 'C.', 'Gonzales', '', 'Male', 'Batangas City', '1964-04-01', 60, 'Single', 'Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09480851465', '922', 'arielgonzales@gmail.com', '72382hsb33', '9202811834', '34343555', 'Pensioner', 'A+', 'Masters Degree', '', 'P45,000.00-P49,999.00', 'Filipino', 'Iglesia ni Cristo', '../uploads/451140052_450414684572897_6349068482611184931_n.jpg', '../../qr-codes/67469277e53df.png', '2024-11-13 13:15:39', 'active', NULL, NULL, 0, NULL),
(110, '65447', 'John Lloyd', 'A.', 'Canadido', 'Jr.', 'Male', 'Manaoag Community ', '1943-02-02', 81, 'Married', 'Pugaro', 'Manaoag', 'Pangasinan', 'Mobile', '09070968435', '', 'marra@gmail.com', '', '', '', 'Pensioner', 'O+', 'High School', '', 'P45,000.00-P49,999.00', 'Filipino', 'Evangelicals (PCEC)', '', '../../qr-codes/673b3920c2aa2.png', '2024-11-17 12:57:38', 'active', NULL, NULL, 0, NULL),
(111, '25807', 'John Victor', 'Quebral', 'Baltazar', '', 'Male', 'Baritao Community Hospital', '1959-12-31', 64, 'Married', 'Pantal', 'Manaoag', 'Pangasinan', '', '09934366932', '', 'brinxler45@gmail.com', '028282', '1221', 'udx45372xxc1', 'Pensioner', 'A-', 'College', '', 'P30,000.00-P34,999.00', 'Filipino', 'Roman Catholic', '../uploads/242398410_418453522973586_6079717649573028918_n.jpg', '../../qr-codes/67468d959f991.png', '2024-11-27 03:09:38', 'active', NULL, NULL, 0, NULL),
(112, '91183', 'Kelly Mark', 'Arinduga', 'Garcia', 'Sr.', 'Male', 'Manaoag City Pangasinan', '1958-02-04', 66, 'Married', 'Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09638790565', '', 'justkellymark@gmail.com', '8028382981', '9393977c88', '87166626', 'Pensioner', 'A+', 'College', '', 'Above P50,000.00', 'Filipino', 'Roman Catholic', '../uploads/IMG_20240401_173613_801.jpg', '../../qr-codes/674691c341768.png', '2024-11-27 03:27:37', 'active', NULL, NULL, 0, NULL),
(113, '22446', 'Gerdenxyl', 'A.', 'Hermogeno', 'M.D.', 'Male', 'Manaoag Pangsinan', '1962-12-05', 61, 'Widowed', 'Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09164901007', '', 'hermogenodenxyl@gmail.com', '115423134', '79472784728', '2e51521525', 'Pensioner', 'B+', 'Doctoral', '', 'Above P50,000.00', 'Filipino', 'Iglesia ni Cristo', '', '../../qr-codes/6746949c2be41.png', '2024-11-27 03:39:45', 'active', NULL, NULL, 0, NULL),
(114, '77437', 'Rodulfo', 'R.', 'Estrada', 'Jr.', 'Male', 'Manaoag Pangasinan', '1963-01-08', 61, 'Married', 'Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09082525222', '', 'rodulfoestrada@gmail.com', '191872626', '62671676376', '413329927', 'Pensioner', 'B+', 'High School', '', 'P45,000.00-P49,999.00', 'Filipino', 'Jehovas Witnesses', '', '../../qr-codes/674695e90c5af.png', '2024-11-27 03:45:21', 'pending', NULL, '21', 0, NULL),
(119, '38656', 'Divina', 'Aquino', 'Mangonon', '', 'Female', 'Dagupan,City', '1955-01-27', 69, 'Married', 'Poblacion', 'Manaoag', 'Pangasinan', 'Mobile', '094990898421', '', 'Manaoag@gmail.com', '87473487874', '587745874877', '8457487547', 'Pensioner', 'A+', 'Masters Degree', '', 'P35,000.00-P39,999.00', 'Filipino', 'Roman Catholic', '', '../../qr-codes/674d3ba4ac902.png', '2024-12-02 04:45:59', 'active', NULL, NULL, 0, NULL),
(120, '48555', 'Jomari', 'Moyano', 'Aquino', '', 'Male', 'Manaoag,Pangasinan', '1964-02-11', 60, 'Married', 'Pantal', 'Manaoag', 'Pangasinan', 'Mobile', '09157175254', '', 'oscaaamanaoag@mail.com', '536563253262635', '98376526', '12635247657869', 'Pensioner', 'B+', 'Vocational', '', 'Above P50,000.00', 'Filipino', 'Roman Catholic', '', '../../qr-codes/675116501cf8f.png', '2024-12-05 02:55:49', 'active', NULL, NULL, 0, NULL);

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
  `demo` int(11) DEFAULT NULL,
  `sms_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `background_image`, `favicon`, `created_at`, `updated_at`, `capture_image`, `logo`, `security_settings`, `demo`, `sms_url`) VALUES
(39, 21, '../assets/462617470_516449747808960_5012806154503844641_n.jpg', '../assets/manaoag.png', '2024-07-30 08:06:22', '2024-12-05 03:39:37', '0', '../assets/manaoag.png', '0', 0, 'http://192.168.43.149:8080/'),
(46, 55, '', '../assets/manaoag.png', '2024-10-16 11:40:46', '2024-10-28 14:21:40', '0', '../assets/manaoag.png', '0', 0, ''),
(48, 56, '', '../assets/manaoag.png', '2024-10-29 07:21:05', '2024-11-13 12:17:26', '0', '../assets/manaoag.png', '0', 0, 'http://192.168.100.141:8080/v1/sms/');

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
  `suffix` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `barangay` varchar(255) DEFAULT '',
  `profile_picture` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `visitor`, `name`, `password`, `role`, `image_path`, `login_time`, `middlename`, `lastname`, `suffix`, `age`, `birthdate`, `barangay`, `profile_picture`, `reset_token`, `qr_code_path`) VALUES
(21, 'admin@gmail.com', 'superadmin', 'John', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin', './logins/1730685129.png', '2024-12-11 00:35:38', '', 'Fernandez', '', 22, '2001-12-31', '', '242398410_418453522973586_6079717649573028918_n-removebg.png', '', NULL),
(56, 'staff@gmail.com', '', 'Ariel', '1562206543da764123c21bd524674f0a8aaf49c8a89744c97352fe677f7e4006', 'staff', './logins/1731498786.png', '2024-12-05 04:40:46', 'Columbino', 'Gonzales', '0', 24, '1999-11-11', 'Brgy. Poblacion, Manaoag, Pangasinan', 'IMG_20240401_173926_998.jpg', NULL, NULL);

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
  `log_time` datetime NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `email`, `name`, `action`, `role`, `log_time`, `image_path`) VALUES
(957, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-11-11 18:22:46', './logins/1730685129.png'),
(958, 56, 'staff@gmail.com', 'Michaela', 'Logged in', 'staff', '2024-11-11 18:25:41', './logins/1731320741.png'),
(959, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-11-11 18:26:14', './logins/1730685129.png'),
(960, 56, 'staff@gmail.com', 'Michaela', 'Logged in', 'staff', '2024-11-11 18:26:46', './logins/1731320806.png'),
(961, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-11-11 18:27:12', './logins/1730685129.png'),
(962, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-11-11 19:05:36', './logins/1730685129.png'),
(963, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-11-11 19:07:23', './logins/1730685129.png'),
(964, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-11-11 19:08:48', './logins/1730685129.png'),
(965, 56, 'staff@gmail.com', 'Michaela', 'Logged in', 'staff', '2024-11-11 19:09:09', './logins/1731323349.png'),
(966, 21, 'admin@gmail.com', 'Victor', 'Logged in', 'admin', '2024-11-11 19:09:44', './logins/1730685129.png'),
(967, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-12 20:00:07', './logins/1730685129.png'),
(968, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-12 22:08:05', './logins/1730685129.png'),
(969, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-13 19:43:56', './logins/1730685129.png'),
(970, 56, 'staff@gmail.com', 'Michaela', 'Logged in', 'staff', '2024-11-13 19:53:06', './logins/1731498786.png'),
(971, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-13 20:17:16', './logins/1730685129.png'),
(972, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-16 20:51:43', './logins/1730685129.png'),
(973, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-17 20:54:09', './logins/1730685129.png'),
(974, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-18 20:41:02', './logins/1730685129.png'),
(975, 56, 'staff@gmail.com', 'Xander', 'Logged in', 'staff', '2024-11-18 21:09:45', './logins/1731498786.png'),
(976, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-22 20:46:06', './logins/1730685129.png'),
(978, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-27 11:02:06', './logins/1730685129.png'),
(979, 56, 'staff@gmail.com', 'Xander', 'Logged in', 'staff', '2024-11-27 12:00:49', './logins/1731498786.png'),
(980, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-27 12:02:25', './logins/1730685129.png'),
(981, 56, 'staff@gmail.com', 'Ariel', 'Logged in', 'staff', '2024-11-27 12:06:31', './logins/1731498786.png'),
(982, 21, 'admin@gmail.com', 'John Victor', 'Logged in', 'admin', '2024-11-27 12:07:32', './logins/1730685129.png'),
(983, 56, 'staff@gmail.com', 'Ariel', 'Logged in', 'staff', '2024-11-29 12:25:31', './logins/1731498786.png'),
(984, 56, 'staff@gmail.com', 'Ariel', 'Logged in', 'staff', '2024-11-29 13:53:33', './logins/1731498786.png'),
(985, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-11-29 14:57:44', './logins/1730685129.png'),
(986, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-11-29 14:58:23', './logins/1730685129.png'),
(987, 56, 'staff@gmail.com', 'Ariel', 'Logged in', 'staff', '2024-11-29 15:18:47', './logins/1731498786.png'),
(988, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-11-29 15:19:22', './logins/1730685129.png'),
(989, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-11-30 21:49:44', './logins/1730685129.png'),
(990, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-11-30 22:04:54', './logins/1730685129.png'),
(991, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-11-30 22:05:14', './logins/1730685129.png'),
(992, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-11-30 22:06:06', './logins/1730685129.png'),
(993, 56, 'staff@gmail.com', 'Ariel', 'Logged in', 'staff', '2024-11-30 22:28:21', './logins/1731498786.png'),
(994, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-11-30 22:31:16', './logins/1730685129.png'),
(995, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-02 11:48:39', './logins/1730685129.png'),
(996, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-02 12:39:29', './logins/1730685129.png'),
(997, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-03 12:36:23', './logins/1730685129.png'),
(998, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-04 05:10:49', './logins/1730685129.png'),
(999, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-04 05:12:50', './logins/1730685129.png'),
(1000, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-05 10:25:19', './logins/1730685129.png'),
(1001, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-05 11:04:18', './logins/1730685129.png'),
(1002, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-05 11:36:46', './logins/1730685129.png'),
(1003, 56, 'staff@gmail.com', 'Ariel', 'Logged in', 'staff', '2024-12-05 12:40:47', './logins/1731498786.png'),
(1004, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-09 11:24:05', './logins/1730685129.png'),
(1005, 21, 'admin@gmail.com', 'John', 'Logged in', 'admin', '2024-12-11 08:35:38', './logins/1730685129.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delete_logs`
--
ALTER TABLE `delete_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `senior_id` (`senior_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
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
-- AUTO_INCREMENT for table `delete_logs`
--
ALTER TABLE `delete_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `senior`
--
ALTER TABLE `senior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1006;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delete_logs`
--
ALTER TABLE `delete_logs`
  ADD CONSTRAINT `delete_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `delete_logs_ibfk_2` FOREIGN KEY (`senior_id`) REFERENCES `senior` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
