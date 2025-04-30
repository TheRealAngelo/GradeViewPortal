-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 03:11 AM
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
-- Database: `student_grade_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_by`, `created_at`) VALUES
(3, 'TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG  SAHUR', 'TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG TUNG', 2, '2025-04-13 04:58:32'),
(4, 'No Class', 'Please be informed that there will be no classes on Friday, April 25, 2025, in observance of a local holiday. Regular classes will resume on Monday, April 28, 2025.\r\n\r\nWe encourage everyone to use this time to rest, catch up on schoolwork, and spend time with family.\r\n\r\nThank you, and stay safe!', 2, '2025-04-20 16:03:25'),
(5, 'HAPPY BIRTHDAY MAAM AMY', 'Let’s all take a moment to wish a very Happy Birthday to Ma\'am Amy! \r\nYour dedication, kindness, and passion for teaching inspire us every day.\r\nWe hope your day is filled with joy, laughter, and love. \r\n\r\nFeel free to greet her and make her day extra special!\r\n\r\n– From the entire school community', 2, '2025-04-20 16:04:13');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `1stGrading` int(3) NOT NULL DEFAULT 0,
  `2ndGrading` int(3) NOT NULL DEFAULT 0,
  `3rdGrading` int(3) NOT NULL DEFAULT 0,
  `4thGrading` int(3) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `yearlevel_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `school_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `1stGrading`, `2ndGrading`, `3rdGrading`, `4thGrading`, `created_by`, `created_at`, `yearlevel_id`, `subject_id`, `school_year_id`) VALUES
(150, 8, 50, 50, 50, 100, 2, '2025-04-29 16:31:21', 1, 17, 1),
(151, 8, 100, 100, 100, 87, 2, '2025-04-29 16:31:21', 1, 18, 1),
(152, 8, 100, 100, 100, 89, 2, '2025-04-29 16:31:21', 1, 19, 1),
(153, 8, 100, 100, 100, 60, 2, '2025-04-29 16:31:21', 1, 20, 1),
(154, 8, 100, 100, 100, 90, 2, '2025-04-29 16:31:21', 1, 21, 1),
(155, 8, 100, 100, 100, 90, 2, '2025-04-29 16:31:21', 1, 22, 1),
(156, 8, 100, 100, 100, 75, 2, '2025-04-29 16:31:21', 1, 23, 1),
(157, 8, 100, 100, 100, 75, 2, '2025-04-29 16:31:21', 1, 24, 1),
(191, 9, 0, 0, 0, 0, 9, '2025-04-29 17:08:48', 3, 33, 1),
(192, 9, 0, 0, 0, 0, 9, '2025-04-29 17:08:48', 3, 34, 1),
(193, 9, 0, 0, 0, 0, 9, '2025-04-29 17:08:48', 3, 35, 1),
(194, 9, 0, 0, 0, 100, 9, '2025-04-29 17:08:48', 3, 36, 1),
(195, 9, 0, 0, 0, 0, 9, '2025-04-29 17:08:48', 3, 37, 1),
(196, 9, 0, 0, 0, 0, 9, '2025-04-29 17:08:48', 3, 38, 1),
(197, 9, 0, 0, 0, 0, 9, '2025-04-29 17:08:48', 3, 39, 1),
(198, 9, 0, 0, 0, 0, 9, '2025-04-29 17:08:48', 3, 40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `id` int(11) NOT NULL,
  `year_start` int(11) NOT NULL,
  `year_end` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`id`, `year_start`, `year_end`) VALUES
(1, 2025, 2026);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `yearlevel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `subject_name`, `yearlevel_id`) VALUES
(17, 'English 7', 1),
(18, 'Filipino 7', 1),
(19, 'MAPEH 7', 1),
(20, 'TLE 7', 1),
(21, 'Computer 7', 1),
(22, 'AP 7', 1),
(23, 'Science 7', 1),
(24, 'Math 7', 1),
(25, 'English 8', 2),
(26, 'Filipino 8', 2),
(27, 'MAPEH 8', 2),
(28, 'TLE 8', 2),
(29, 'Computer 8', 2),
(30, 'AP 8', 2),
(31, 'Science 8', 2),
(32, 'Math 8', 2),
(33, 'English 9', 3),
(34, 'Filipino 9', 3),
(35, 'MAPEH 9', 3),
(36, 'TLE 9', 3),
(37, 'Computer 9', 3),
(38, 'AP 9', 3),
(39, 'Science 9', 3),
(40, 'Math 9', 3),
(41, 'English 10', 4),
(42, 'Filipino 10', 4),
(43, 'MAPEH 10', 4),
(44, 'TLE 10', 4),
(45, 'Computer 10', 4),
(46, 'AP 10', 4),
(47, 'Science 10', 4),
(48, 'Math 10', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('faculty','student') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `FirstName`, `LastName`, `password`, `role`, `created_at`) VALUES
(2, 'Dan@gmail.com', 'Dan Michael', 'Oro', '$2y$10$hJwPpJUPGUeGQn4C8IVR0erpUzGUbJTZlXVrE4grWwMrR34tHUQAO', 'faculty', '2025-04-12 13:41:58'),
(8, 'angelo@gmail.com', 'Angelo', 'Morales', '$2y$10$6v04fV8NQT3Yhd2lnpuG1.xA.IjxkzcY4FrBT2KITM9GVXjfG3ZYO', 'student', '2025-04-13 03:05:10'),
(9, 'Juan@gmail.com', 'Juan', 'Dela Cruz', '$2y$10$/vBTC.XSPbH5xiXQRPhXmudyDNUHpoBTBe9sx7U1FEpF7PyBkTGDS', 'student', '2025-04-13 04:38:54');

-- --------------------------------------------------------

--
-- Table structure for table `yearlevel`
--

CREATE TABLE `yearlevel` (
  `id` int(11) NOT NULL,
  `level_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `yearlevel`
--

INSERT INTO `yearlevel` (`id`, `level_name`) VALUES
(1, 'Grade 7'),
(2, 'Grade 8'),
(3, 'Grade 9'),
(4, 'Grade 10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_grade_entry` (`student_id`,`subject_id`,`school_year_id`),
  ADD KEY `fk_grades_created_by` (`created_by`),
  ADD KEY `yearlevel_id` (`yearlevel_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `school_year_id` (`school_year_id`);

--
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year_start` (`year_start`,`year_end`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yearlevel_id` (`yearlevel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `yearlevel`
--
ALTER TABLE `yearlevel`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `yearlevel`
--
ALTER TABLE `yearlevel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grades_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_grades_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`yearlevel_id`) REFERENCES `yearlevel` (`id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`),
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`yearlevel_id`) REFERENCES `yearlevel` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
