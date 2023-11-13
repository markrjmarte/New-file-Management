-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2023 at 06:31 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slsu_fm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `announce_to` tinyint(4) DEFAULT 0,
  `date_uploaded` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `message` text NOT NULL,
  `title` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`id`, `announce_to`, `date_uploaded`, `message`, `title`) VALUES
(36, 0, '2023-11-13 09:34:13', 'sdsdsdsds', 'Test to announcement'),
(37, 0, '2023-11-13 11:10:46', 'dsd sd s s d sd', 'sdsdsd s'),
(38, 0, '2023-11-13 11:45:44', 'sdsdsdsd', 'sdsds');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(30) NOT NULL,
  `folder_id` int(30) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_path` text NOT NULL,
  `is_public` tinyint(4) DEFAULT -1,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `name`, `description`, `user_id`, `folder_id`, `file_type`, `file_path`, `is_public`, `date_updated`) VALUES
(169, 'teamwork', 'Test', 23, 0, 'png', '1699808580_teamwork.png', 0, '2023-11-13 01:03:30'),
(170, '1697336760_1697160900_Advance-Database-Systems (1)', 'sdsdsdsdsd ', 23, 0, 'docx', '1699808760_1697336760_1697160900_Advance-Database-Systems (1).docx', 1, '2023-11-13 01:07:08'),
(171, 'dashboard', 'From announcement: Test to announcement', 1, 0, 'txt', '1699839240_dashboard.txt', 0, '2023-11-13 09:34:13'),
(172, 'QMSREST(Marte)', 'share to all', 1, 0, 'docx', '1699839240_QMSREST(Marte).docx', 0, '2023-11-13 09:34:54'),
(173, '1699808760_1697336760_1697160900_Advance-Database-Systems (1) (3)', 'sdsdsdsdsd', 23, 0, 'docx', '1699840800_1699808760_1697336760_1697160900_Advance-Database-Systems (1) (3).docx', 1, '2023-11-13 10:01:03'),
(174, 'profile', 'From announcement: sdsds', 1, 0, 'jpg', '1699847100_profile.jpg', 23, '2023-11-13 11:46:25');

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `parent_id` int(30) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `user_id`, `name`, `parent_id`) VALUES
(29, 1, 'Test folder 1', 0),
(30, 1, 'sdsdsdsd', 0),
(31, 1, 'sdsdsdsdsd', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `by_who` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `is_public` tinyint(4) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0,
  `kind` int(4) NOT NULL DEFAULT 0,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `by_who`, `description`, `is_public`, `status`, `kind`, `date_updated`) VALUES
(10, '1', 'Test to announcement', 0, 1, 0, '2023-11-13 09:34:13'),
(11, '1', 'share to all', 0, 1, 1, '2023-11-13 09:34:54'),
(12, '23', 'sdsdsdsdsd', 1, 1, 1, '2023-11-13 10:01:03'),
(13, '1', 'sdsdsd s', 0, 1, 0, '2023-11-13 11:10:46'),
(14, '1', 'sdsds', 0, 1, 0, '2023-11-13 11:45:44'),
(15, '1', 'From announcement: sdsds', 23, 1, 1, '2023-11-13 11:46:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1+admin , 2 = users',
  `adress` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `about` varchar(200) NOT NULL,
  `profile_image` varchar(60) NOT NULL,
  `job` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`, `adress`, `phone`, `email`, `about`, `profile_image`, `job`) VALUES
(1, 'Mark RJ S. Marte', 'admin', 'admin123', 1, 'Bogo, Tomas Oppus Southern Leyte', '09058172767', 'markrjmarte9@gmail.com', '', 'ID.jpg', 'Senior Software Developer'),
(23, 'Alona Jean ', 'alonajean', 'alonajean123', 2, 'Cambite, Tomas Oppus Southern Leyte', '09058172767', 'alonajean21@gmail.com', 'shesssssh', '387325037_735875058376969_198901648154600357_n.jpg', 'uncalibrated<3'),
(38, 'sdsd', 'sdsds', 'sdsd', 2, 'sdsd', 'ssdsdsdsdsd', '', '', 'product-1.jpg', 'sdsd'),
(39, 'sdsd', '1', '1', 2, 'dsds', 'dsdsds', '12121', '', 'profile-img.jpg', 'sds'),
(40, 'sd', 'sd', 'sdsd', 2, 'sdsd', 'sdsd', '', '', '', 'sdw'),
(41, '21', '1', '1', 2, '21', '21', '1', '', '', '12');

-- --------------------------------------------------------

--
-- Table structure for table `users_logs`
--

CREATE TABLE `users_logs` (
  `status` varchar(200) NOT NULL,
  `users` varchar(200) NOT NULL,
  `dates` varchar(200) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_logs`
--

INSERT INTO `users_logs` (`status`, `users`, `dates`, `id`) VALUES
('Login', 'admin', '2023-11-13 00:40:34', 692),
('Created a user', '1', '2023-11-13 00:41:02', 693),
('Deleted a user', 'admin', '2023-11-13 00:43:10', 694),
('Deleted a user', 'admin', '2023-11-13 00:43:14', 695),
('Created a user', 'sdsds', '2023-11-13 00:44:53', 696),
('updated user info', 'sdsds', '2023-11-13 00:51:20', 697),
('updated user info', 'alonajean', '2023-11-13 00:51:46', 698),
('Created a user', '1', '2023-11-13 00:54:25', 699),
('Login', 'alonajean', '2023-11-13 00:56:07', 700),
('updated user info', '1', '2023-11-13 00:59:06', 701),
('Added a file teamwork.png', 'alonajean', '2023-11-13 01:03:18', 702),
('Shared teamwork.png to all users ', 'alonajean', '2023-11-13 01:03:31', 703),
('Created a user', 'sd', '2023-11-13 01:06:30', 704),
('Added a file 1697336760_1697160900_Advance-Database-Systems (1).docx', 'alonajean', '2023-11-13 01:06:59', 705),
('Shared 1697336760_1697160900_Advance-Database-Systems (1).docx to Mark RJ S. Marte ', 'alonajean', '2023-11-13 01:07:08', 706),
('Logout', 'alonajean', '2023-11-13 01:09:30', 707),
('Logout', 'admin', '2023-11-13 01:09:33', 708),
('Login', 'admin', '2023-11-13 01:09:50', 709),
('Login', 'admin', '2023-11-13 01:18:03', 710),
('Created a user', '1', '2023-11-13 01:18:47', 711),
('Login', 'admin', '2023-11-13 09:21:59', 712),
('added an announcement', 'admin', '2023-11-13 09:34:13', 713),
('Added a file QMSREST(Marte).docx', 'admin', '2023-11-13 09:34:36', 714),
('Shared QMSREST(Marte).docx to all users ', 'admin', '2023-11-13 09:34:54', 715),
('Login', 'alonajean', '2023-11-13 09:35:36', 716),
('Added a file 1699808760_1697336760_1697160900_Advance-Database-Systems (1) (3).docx', 'alonajean', '2023-11-13 10:00:51', 717),
('Shared 1699808760_1697336760_1697160900_Advance-Database-Systems (1) (3).docx to Mark RJ S. Marte ', 'alonajean', '2023-11-13 10:01:03', 718),
('Login', 'admin', '2023-11-13 10:56:43', 719),
('added an announcement', 'admin', '2023-11-13 11:10:46', 720),
('added an announcement', 'admin', '2023-11-13 11:45:44', 721),
('Shared profile.jpg to Alona Jean  ', 'admin', '2023-11-13 11:46:25', 722),
('Login', 'admin', '2023-11-13 12:10:21', 723),
('Login', 'admin', '2023-11-13 12:16:28', 724),
('Logout', 'admin', '2023-11-13 12:25:19', 725),
('Login', 'alonajean', '2023-11-13 12:37:52', 726),
('Logout', 'alonajean', '2023-11-13 12:42:22', 727),
('Login', 'alonajean', '2023-11-13 12:43:07', 728),
('Logout', 'alonajean', '2023-11-13 12:45:25', 729),
('Login', 'admin', '2023-11-13 12:47:26', 730),
('Login', 'alonajean', '2023-11-13 12:47:55', 731),
('Logout', 'alonajean', '2023-11-13 12:51:48', 732),
('Login', 'alonajean', '2023-11-13 12:52:04', 733),
('Login', 'admin', '2023-11-13 13:11:36', 734),
('Login', 'admin', '2023-11-13 13:14:39', 735),
('Logout', 'admin', '2023-11-13 13:22:19', 736),
('Login', 'admin', '2023-11-13 13:22:28', 737),
('Login', 'alonajean', '2023-11-13 13:23:16', 738),
('Logout', 'admin', '2023-11-13 13:30:46', 739),
('Login', 'admin', '2023-11-13 13:30:52', 740);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_logs`
--
ALTER TABLE `users_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users_logs`
--
ALTER TABLE `users_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=741;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
