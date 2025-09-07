-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2025 at 08:07 AM
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
-- Database: `education_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `campus` varchar(50) NOT NULL,
  `role` enum('super_admin','campus_admin','content_moderator') DEFAULT 'campus_admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password_hash`, `campus`, `role`, `created_at`) VALUES
(1, 'icbtadmin@gmail.com', '$2y$10$cXKLFmM8oNpBHSymNGjXUO.mU7hUaM5z79F.hbcsQxgyT96fOIpTS', 'ICBT Campus', 'campus_admin', '2025-08-27 13:54:16'),
(2, 'nibmadmin@gmail.com', '$2y$10$FYGHzeqR/ZqU8ITmpwjRPe3ot02pbJiMC/dLzGvkMtCO4Z1IhwsXS', 'NIBM', 'campus_admin', '2025-08-27 13:54:16'),
(3, 'peradeniyaadmin@gmail.com', '$2y$10$caw10DUw.q6TadpwPXho7O/Ap0sxr5CVnPPU4wM49jsEbeb8PYmDO', 'University of Peradeniya', 'campus_admin', '2025-08-27 13:54:16'),
(4, 'moratuwaadmin@gmail.com', '$2y$10$/x/C0DJbHPTWPrB.Hwy1VuGAr6./ChTpQjQrPVNvUiRMvwLH5zbAW', 'University of Moratuwa', 'campus_admin', '2025-08-27 13:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions_log`
--

CREATE TABLE `admin_actions_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action_type` enum('create','update','delete') NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `action_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `description`, `created_at`) VALUES
(1, 1, 'CREATE_ANNOUNCEMENT', 'Created announcement for ICBT Campus: Discounts', '2025-08-30 05:39:15'),
(2, 1, 'CREATE_ANNOUNCEMENT', 'Created announcement for ICBT Campus: 📢 New Update on ICBT Campus', '2025-09-01 11:22:42'),
(3, 4, 'CREATE_ANNOUNCEMENT', 'Created announcement for University of Moratuwa: hiiiiiiiiiii', '2025-09-01 13:47:04'),
(4, 3, 'CREATE_ANNOUNCEMENT', 'Created announcement for University of Peradeniya: peradeniya campus', '2025-09-01 14:32:03'),
(5, 2, 'CREATE_ANNOUNCEMENT', 'Created announcement for NIBM: New Semester Registration', '2025-09-02 06:04:10'),
(6, 3, 'CREATE_ANNOUNCEMENT', 'Created announcement for University of Peradeniya: Library Timings Update', '2025-09-02 06:04:50'),
(7, 4, 'CREATE_ANNOUNCEMENT', 'Created announcement for University of Moratuwa: Tech Workshop', '2025-09-02 06:05:34'),
(8, 1, 'NEW_REVIEW', 'New review #5 submitted by student 1 for ICBT Campus', '2025-09-03 03:41:08'),
(9, 1, 'CREATE_ANNOUNCEMENT', 'Created announcement for ICBT Campus: 📢 New Update on ICBT Campus', '2025-09-03 16:53:38'),
(10, 2, 'CREATE_ANNOUNCEMENT', 'Created announcement for NIBM: New Intake – BSc in Data Science', '2025-09-05 09:42:55'),
(11, 3, 'CREATE_ANNOUNCEMENT', 'Created announcement for University of Peradeniya: Postgraduate Intake 2026 – MSc in Computer Science', '2025-09-05 09:53:59'),
(12, 4, 'CREATE_ANNOUNCEMENT', 'Created announcement for University of Moratuwa: New Intake – BSc in Information Technology', '2025-09-05 10:06:48'),
(13, 1, 'NEW_REVIEW', 'New review #2 submitted by student 8 for ICBT Campus', '2025-09-06 10:00:07'),
(14, 2, 'CREATE_ANNOUNCEMENT', 'Created announcement for NIBM: New Intake – BSc in Data Science', '2025-09-06 14:52:35'),
(15, 3, 'CREATE_ANNOUNCEMENT', 'Created announcement for University of Peradeniya: Postgraduate Intake 2026 – MSc in Computer Science', '2025-09-06 14:53:27'),
(16, 4, 'CREATE_ANNOUNCEMENT', 'Created announcement for University of Moratuwa: New Intake – BSc in Information Technology', '2025-09-06 14:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `campus` varchar(50) NOT NULL,
  `email_notifications` tinyint(1) DEFAULT 1,
  `dashboard_alerts` tinyint(1) DEFAULT 1,
  `theme` enum('light','dark') DEFAULT 'light',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `admin_id`, `campus`, `email_notifications`, `dashboard_alerts`, `theme`, `created_at`, `updated_at`) VALUES
(1, 1, 'ICBT Campus', 1, 1, 'light', '2025-09-01 04:19:29', '2025-09-01 04:19:29'),
(2, 2, 'NIBM', 1, 1, 'light', '2025-09-01 04:19:29', '2025-09-01 14:01:02'),
(3, 4, 'University of Moratuwa', 1, 1, 'light', '2025-09-01 04:19:29', '2025-09-01 04:19:29'),
(4, 3, 'University of Peradeniya', 1, 1, 'light', '2025-09-01 04:19:29', '2025-09-01 04:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `audience` enum('all','students','admins') NOT NULL,
  `campus` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `body`, `audience`, `campus`, `admin_id`, `expires_at`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'New Semester Registration', 'Registration for the upcoming semester opens on 10th September. Please complete your enrollment before the deadline.', 'all', 'NIBM', 2, '2025-09-04 06:04:00', 0, '2025-09-02 06:04:10', '2025-09-05 09:42:55'),
(7, 'Library Timings Update', 'The library will now be open from 8:00 AM to 8:00 PM on weekdays.', 'all', 'University of Peradeniya', 3, '2025-09-03 06:04:00', 0, '2025-09-02 06:04:50', '2025-09-05 09:53:59'),
(8, 'Tech Workshop', 'A free coding workshop will be held on 15th September in the Computer Lab. All students are welcome.', 'all', 'University of Moratuwa', 4, '2025-09-05 06:05:00', 0, '2025-09-02 06:05:34', '2025-09-05 10:06:48'),
(9, '📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for more updates and information about programs and opportunities at ICBT!', 'all', 'ICBT Campus', 1, '2025-09-06 03:00:00', 1, '2025-09-03 16:53:38', '2025-09-03 16:53:38'),
(13, 'New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. Limited seats available. Apply before 30th December 2025', 'all', 'NIBM', 2, '2025-11-28 14:52:00', 1, '2025-09-06 14:52:35', '2025-09-06 14:52:35'),
(14, 'Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. The new intake will commence in January 2026. Eligible candidates are encouraged to apply online through the university website.', 'all', 'University of Peradeniya', 3, '2025-11-28 14:53:00', 1, '2025-09-06 14:53:27', '2025-09-06 14:53:27'),
(15, 'New Intake – BSc in Information Technology', 'Applications are now open for the BSc in Information Technology program at Moratuwa Campus for the 2025 intake. Interested candidates are encouraged to apply online before 15th October 2025', 'all', 'University of Moratuwa', 4, '2025-11-28 14:54:00', 1, '2025-09-06 14:54:15', '2025-09-06 14:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `application_status_log`
--

CREATE TABLE `application_status_log` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `old_status` varchar(20) NOT NULL,
  `new_status` varchar(20) NOT NULL,
  `reviewer_id` int(11) DEFAULT NULL,
  `change_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_status_log`
--

INSERT INTO `application_status_log` (`id`, `application_id`, `old_status`, `new_status`, `reviewer_id`, `change_date`) VALUES
(27, 77, 'approved', 'approved', 1, '2025-09-03 13:49:38'),
(28, 79, 'waitlisted', 'waitlisted', 1, '2025-09-03 15:42:50'),
(29, 78, 'pending', 'rejected', 1, '2025-09-03 15:39:30');

-- --------------------------------------------------------

--
-- Stand-in structure for view `application_summary`
-- (See below for the actual view)
--
CREATE TABLE `application_summary` (
);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `seats_available` int(11) DEFAULT 50,
  `status` enum('active','inactive','full') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_applications`
--

CREATE TABLE `course_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `university` varchar(100) NOT NULL,
  `study_level` varchar(100) NOT NULL,
  `program` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `highest_qualification` varchar(100) NOT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `graduation_year` int(4) DEFAULT NULL,
  `declaration_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `terms_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','approved','rejected','waitlisted') NOT NULL DEFAULT 'pending',
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `review_date` timestamp NULL DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `application_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_applications`
--

INSERT INTO `course_applications` (`id`, `user_id`, `course_name`, `university`, `study_level`, `program`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `highest_qualification`, `institution`, `graduation_year`, `declaration_accepted`, `terms_accepted`, `status`, `application_date`, `review_date`, `reviewed_by`, `application_number`) VALUES
(77, 8, 'Diploma in Strategic Management and Leadership (OTHM Level 7)', 'ICBT Campus', 'Postgraduate', 'Business', 'Nethmi', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '0789037326', '2007-09-02', 'Diploma', 'ICBT', 2024, 1, 1, 'approved', '2025-09-03 13:46:59', '2025-09-03 13:49:38', 1, 'APP-2025-4928'),
(78, 8, 'Higher Diploma in Computing and Software Engineering', 'ICBT Campus', 'Undergraduate', 'Information Technology', 'Nethmi', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '0789037326', '2007-09-02', 'Diploma', 'ESOFT Campus', 2022, 1, 1, 'rejected', '2025-09-03 15:35:59', '2025-09-03 15:39:30', 1, 'APP-2025-4461'),
(79, 8, 'MSc in Data Science', 'ICBT Campus', 'Postgraduate', 'Information Technology', 'Nethmi', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '0789037326', '2007-09-02', 'Diploma', 'ICBT campus', 2024, 1, 1, 'waitlisted', '2025-09-03 15:41:50', '2025-09-03 15:42:50', 1, 'APP-2025-5638'),
(80, 8, 'Higher Diploma in Computing and Software Engineering', 'ICBT Campus', 'Undergraduate', 'Information Technology', 'Nethmi', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '0789037326', '2007-09-03', 'Diploma', 'ICBT', 2023, 1, 1, 'pending', '2025-09-04 04:03:36', NULL, NULL, 'APP-2025-1097'),
(81, 8, 'Higher Diploma in Biomedical Science', 'ICBT Campus', 'Undergraduate', 'Science', 'Nethmi', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '0789037326', '2007-09-03', 'Diploma', 'ICBT', 2023, 1, 1, 'pending', '2025-09-04 05:02:30', NULL, NULL, 'APP-2025-3089'),
(82, 8, 'BA (Hons) in Management & Leadership', 'NIBM', 'Degree (Undergraduate)', 'Business & Management', 'Nethmi', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '0789037326', '2009-09-04', 'Diploma', 'ESOFT', 2023, 1, 1, 'pending', '2025-09-05 09:35:53', NULL, NULL, 'APP-2025-7424'),
(83, 8, 'Certificate in Basic Tamil', 'University of Peradeniya', 'Certificate & Advanced Certificate', 'CDCE', 'Nethmi', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '0789037326', '2009-09-04', 'Advanced Certificate', 'ICBT', 2024, 1, 1, 'pending', '2025-09-05 09:48:38', NULL, NULL, 'APP-2025-9045'),
(84, 8, 'Computer Hardware & Networking', 'University of Moratuwa', 'Faculty of Information Technology', 'Certificate Programs', 'Nethmi', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '0789037326', '2009-09-04', 'Diploma', 'NIBM', 2022, 1, 1, 'pending', '2025-09-05 09:55:51', NULL, NULL, 'APP-2025-0130');

-- --------------------------------------------------------

--
-- Table structure for table `course_registrations`
--

CREATE TABLE `course_registrations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` varchar(100) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `university_name` varchar(100) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `program_name` varchar(100) NOT NULL,
  `student_full_name` varchar(100) NOT NULL,
  `student_nic_passport` varchar(20) NOT NULL,
  `student_email` varchar(100) NOT NULL,
  `student_phone` varchar(20) NOT NULL,
  `student_address` text DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `admin_response_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `university_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `icbt_courses`
--

CREATE TABLE `icbt_courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `course_description` text DEFAULT NULL,
  `requirement` text DEFAULT NULL,
  `program_id` int(11) NOT NULL,
  `campus` varchar(50) NOT NULL DEFAULT 'ICBT',
  `status` enum('Active','Inactive','Draft') DEFAULT 'Active',
  `is_active` tinyint(1) DEFAULT 1,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `icbt_faculties`
--

CREATE TABLE `icbt_faculties` (
  `id` int(11) NOT NULL,
  `faculty_name` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `campus` varchar(50) NOT NULL DEFAULT 'ICBT',
  `status` enum('Active','Inactive','Suspended') DEFAULT 'Active',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `icbt_faculties`
--

INSERT INTO `icbt_faculties` (`id`, `faculty_name`, `category`, `description`, `display_order`, `is_active`, `campus`, `status`, `created_date`, `updated_date`) VALUES
(1, 'Business', 'Postgraduate', '-- =====================================================\r\n-- ICBT FACULTIES TABLE CREATION\r\n-- =====================================================\r\n\r\n-- Create the ICBT faculties table\r\nCREATE TABLE IF NOT EXISTS `icbt_faculties` (\r\n    `id` int(11) NOT NULL AUTO_INCREMENT,\r\n    `faculty_name` varchar(200) NOT NULL,\r\n    `category` varchar(100) NOT NULL,\r\n    `description` text DEFAULT NULL,\r\n    `campus` varchar(50) NOT NULL DEFAULT \'ICBT\',\r\n    `status` enum(\'Active\', \'Inactive\', \'Suspended\') DEFAULT \'Active\',\r\n    `created_date` timestamp DEFAULT CURRENT_TIMESTAMP,\r\n    `updated_date` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\r\n    PRIMARY KEY (`id`),\r\n    UNIQUE KEY `unique_faculty_category` (`faculty_name`, `category`, `campus`),\r\n    KEY `idx_faculties_category` (`category`),\r\n    KEY `idx_faculties_campus` (`campus`),\r\n    KEY `idx_faculties_status` (`status`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\r\n\r\n-- Insert sample ICBT faculties (using INSERT IGNORE to avoid duplicates)\r\nINSERT IGNORE INTO `icbt_faculties` (\r\n    `faculty_name`, \r\n    `category`, \r\n    `description`, \r\n    `campus`\r\n) VALUES \r\n-- Postgraduate Faculties\r\n(\'Business\', \'Postgraduate\', \'Business and management studies at postgraduate level\', \'ICBT\'),\r\n(\'Engineering & Construction\', \'Postgraduate\', \'Advanced engineering and construction management\', \'ICBT\'),\r\n(\'Information Technology\', \'Postgraduate\', \'Advanced IT and computing studies\', \'ICBT\'),\r\n(\'Science\', \'Postgraduate\', \'Scientific research and applied sciences\', \'ICBT\'),\r\n(\'Law\', \'Postgraduate\', \'Legal studies and international business law\', \'ICBT\'),\r\n\r\n-- Undergraduate Faculties\r\n(\'Business\', \'Undergraduate\', \'Business and management studies at undergraduate level\', \'ICBT\'),\r\n(\'Engineering & Construction\', \'Undergraduate\', \'Engineering and construction studies\', \'ICBT\'),\r\n(\'Information Technology\', \'Undergraduate\', \'IT and computing studies\', \'ICBT\'),\r\n(\'English\', \'Undergraduate\', \'English language and literature studies\', \'ICBT\'),\r\n(\'Law\', \'Undergraduate\', \'Legal studies and law practice\', \'ICBT\'),\r\n\r\n-- After A/L Faculties\r\n(\'Business\', \'After A/L\', \'Business studies for A/L graduates\', \'ICBT\'),\r\n(\'Engineering & Construction\', \'After A/L\', \'Engineering foundation studies\', \'ICBT\'),\r\n(\'Information Technology\', \'After A/L\', \'IT foundation studies\', \'ICBT\'),\r\n(\'Science\', \'After A/L\', \'Science foundation studies\', \'ICBT\'),\r\n\r\n-- After O/L Faculties\r\n(\'Business\', \'After O/L\', \'Business foundation studies\', \'ICBT\'),\r\n(\'Engineering & Construction\', \'After O/L\', \'Engineering foundation studies\', \'ICBT\'),\r\n(\'Information Technology\', \'After O/L\', \'IT foundation studies\', \'ICBT\'),\r\n(\'Science\', \'After O/L\', \'Science foundation studies\', \'ICBT\');\r\n\r\n-- =====================================================\r\n-- VERIFICATION QUERIES\r\n-- =====================================================\r\n\r\n-- Show the created table structure\r\nDESCRIBE icbt_faculties;\r\n\r\n-- Show sample faculty data\r\nSELECT * FROM icbt_faculties ORDER BY category, faculty_name;\r\n\r\n-- Count total faculties\r\nSELECT \r\n    category,\r\n    COUNT(*) as faculty_count \r\nFROM icbt_faculties \r\nGROUP BY category \r\nORDER BY category;\r\n', 1, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(2, 'Engineering & Construction', 'Postgraduate', 'Advanced engineering and construction management', 1, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(3, 'Information Technology 1', 'Postgraduate', '', 1, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 17:54:40'),
(4, 'Science', 'Postgraduate', 'Scientific research and applied sciences', 1, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(5, 'Law', 'Postgraduate', 'Legal studies and international business law', 1, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(6, 'Business', 'Undergraduate', 'Business and management studies at undergraduate level', 2, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(7, 'Engineering & Construction', 'Undergraduate', 'Engineering and construction studies', 2, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(8, 'Information Technology', 'Undergraduate', 'IT and computing studies', 2, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(9, 'English', 'Undergraduate', 'English language and literature studies', 2, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(10, 'Law', 'Undergraduate', 'Legal studies and law practice', 2, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(11, 'Business', 'After A/L', 'Business studies for A/L graduates', 3, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(12, 'Engineering & Construction', 'After A/L', 'Engineering foundation studies', 3, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(13, 'Information Technology', 'After A/L', 'IT foundation studies', 3, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(14, 'Science', 'After A/L', 'Science foundation studies', 3, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(15, 'Business', 'After O/L', 'Business foundation studies', 4, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(16, 'Engineering & Construction', 'After O/L', 'Engineering foundation studies', 4, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(17, 'Information Technology', 'After O/L', 'IT foundation studies', 4, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(18, 'Science', 'After O/L', 'Science foundation studies', 4, 1, 'ICBT', 'Active', '2025-08-28 12:58:40', '2025-08-30 08:38:44'),
(19, 'Business', 'After O/L & A/L', 'Business studies for O/L and A/L graduates', 0, 1, 'ICBT', 'Active', '2025-08-30 10:10:51', '2025-08-30 10:10:51'),
(20, 'Science', 'After O/L & A/L', 'Science foundation studies', 0, 1, 'ICBT', 'Active', '2025-08-30 10:10:51', '2025-08-30 10:10:51'),
(21, 'Information Technology', 'After O/L & A/L', 'IT foundation studies', 0, 1, 'ICBT', 'Active', '2025-08-30 10:10:51', '2025-08-30 10:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `icbt_programs`
--

CREATE TABLE `icbt_programs` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `program_code` varchar(20) NOT NULL,
  `program_name` varchar(200) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `level` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `max_capacity` int(11) DEFAULT NULL,
  `campus` varchar(50) NOT NULL DEFAULT 'ICBT',
  `status` enum('Active','Inactive','Suspended') DEFAULT 'Active',
  `is_active` tinyint(1) DEFAULT 1,
  `enrolled_count` int(11) DEFAULT 0,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `icbt_programs`
--

INSERT INTO `icbt_programs` (`id`, `faculty_id`, `program_code`, `program_name`, `duration`, `level`, `description`, `requirements`, `max_capacity`, `campus`, `status`, `is_active`, `enrolled_count`, `created_date`, `updated_date`) VALUES
(17, 1, 'PGDSML(L284', 'Diploma in Strategic Management and Leadership (OTHM Level 7)', '1-2 Years', 'Postgraduate', 'Program added from dashboard', NULL, 100, 'ICBT', 'Active', 1, 0, '2025-08-28 12:10:58', '2025-08-30 08:39:06'),
(18, 1, 'PGDHRM(L274', 'Diploma in Human Resource Management (OTHM Level 7)', '1-2 Years', 'Postgraduate', 'Program added from dashboard', NULL, 100, 'ICBT', 'Active', 1, 0, '2025-08-28 13:26:33', '2025-08-30 08:39:06'),
(21, 1, 'PG002', 'Diploma in Human Resource Management (OTHM Level 7)', '1 Year', 'Postgraduate', 'Professional HR management qualification', NULL, 40, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(22, 1, 'PG003', 'MSc in International Relations', '2 Years', 'Postgraduate', 'International relations and global politics', NULL, 30, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(23, 1, 'PG004', 'MSc in Civil Engineering', '2 Years', 'Postgraduate', 'Advanced civil engineering studies', NULL, 35, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(24, 1, 'PG005', 'MSc Quantity Surveying and Commercial Management', '2 Years', 'Postgraduate', 'Quantity surveying and commercial management', NULL, 25, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(25, 1, 'PG006', 'MSc Construction Project Management', '2 Years', 'Postgraduate', 'Construction project management and leadership', NULL, 30, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(26, 1, 'PG007', 'Master of Science in Information Technology (MSc IT)', '2 Years', 'Postgraduate', 'Advanced IT and computing studies', NULL, 40, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(27, 1, 'PG008', 'MSc in Data Science', '2 Years', 'Postgraduate', 'Data science and analytics', NULL, 35, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(28, 1, 'PG009', 'MSc Applied Psychology & Behavior Change', '2 Years', 'Postgraduate', 'Applied psychology and behavior modification', NULL, 30, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(29, 1, 'PG010', 'Master of Laws in International Business (LLM)', '2 Years', 'Postgraduate', 'International business law', NULL, 25, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:12'),
(30, 6, 'UG001', 'Higher Diploma in Digital Marketing', '2 Years', 'Undergraduate', 'Digital marketing and online business', NULL, 60, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(31, 6, 'UG002', 'Higher Diploma in Business Management', '2 Years', 'Undergraduate', 'Business management and administration', NULL, 80, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(32, 6, 'UG003', 'Professional Diploma in Quantity Surveying', '2 Years', 'Undergraduate', 'Quantity surveying and construction economics', NULL, 40, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(33, 6, 'UG004', 'Higher Diploma in Automotive Engineering', '2 Years', 'Undergraduate', 'Automotive engineering and technology', NULL, 35, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(34, 6, 'UG005', 'Higher Diploma in Quantity Surveying', '2 Years', 'Undergraduate', 'Quantity surveying and construction management', NULL, 45, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(35, 6, 'UG006', 'Higher Diploma in Computing and Software Engineering', '2 Years', 'Undergraduate', 'Computing and software development', NULL, 70, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(36, 6, 'UG007', 'Higher Diploma in Network Technology & Cyber Security', '2 Years', 'Undergraduate', 'Network technology and cybersecurity', NULL, 55, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(37, 6, 'UG008', 'BSc (Hons) Data Science', '3 Years', 'Undergraduate', 'Data science and analytics degree', NULL, 50, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(38, 6, 'UG009', 'Higher Diploma in English', '2 Years', 'Undergraduate', 'English language and literature', NULL, 40, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(39, 6, 'UG010', 'BA (Hons) in English', '3 Years', 'Undergraduate', 'English language and literature degree', NULL, 35, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25'),
(40, 6, 'UG011', 'LLB (Hons) Law', '3 Years', 'Undergraduate', 'Law degree and legal studies', NULL, 30, 'ICBT', 'Active', 1, 0, '2025-08-30 10:10:51', '2025-08-30 16:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `university_id` varchar(50) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `response` text DEFAULT NULL,
  `response_status` enum('pending','answered','closed') DEFAULT 'pending',
  `response_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `student_id`, `university_id`, `subject`, `message`, `response`, `response_status`, `response_date`, `created_at`, `updated_at`) VALUES
(21, 8, 'ICBT Campus', 'course-inquiry', 'Dear Admin, I would like more information about the courses offered on the portal. Could you please guide me?', 'Dear Student, please check the ‘Courses’ section in your account to view all course details. – ICBT Admin', 'answered', '2025-09-03 16:26:27', '2025-09-03 16:24:52', '2025-09-03 16:26:27'),
(22, 8, 'NIBM Campus', 'course-inquiry', 'Hello, I would like to know the duration and fee structure of the BSc (Hons) Software Engineering program at NIBM Campus?', 'Dear Student, the BSc (Hons) Software Engineering program has a duration of 3 years. The total course fee is LKR 1,200,000, payable in installments. Please contact our admissions office for detailed payment plans', 'answered', '2025-09-05 09:40:45', '2025-09-05 09:39:09', '2025-09-05 09:40:45'),
(23, 8, 'Peradeniya Campus', 'course-inquiry', 'Can you give me details about the MSc in Computer Science at Peradeniya?', 'The MSc in Computer Science is a 2-year program. Next intake opens in January 2026. Please check our website for application details.', 'answered', '2025-09-05 09:52:19', '2025-09-05 09:50:34', '2025-09-05 09:52:19'),
(24, 8, 'Moratuwa Campus', 'course-inquiry', 'Is the BSc IT program open for 2025 at Moratuwa?', NULL, 'pending', NULL, '2025-09-05 09:56:51', '2025-09-05 09:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `moratuwa_courses`
--

CREATE TABLE `moratuwa_courses` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `course_description` text DEFAULT NULL,
  `requirement` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `campus` varchar(50) DEFAULT 'Moratuwa',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moratuwa_courses`
--

INSERT INTO `moratuwa_courses` (`id`, `program_id`, `course_name`, `course_description`, `requirement`, `duration`, `campus`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Global Business Strategy', 'Strategic management in international business environment', 'Bachelor degree or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(2, 1, 'International Marketing', 'Marketing strategies for global markets', 'Bachelor degree or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(3, 1, 'Cross-Cultural Management', 'Managing diverse teams across cultures', 'Bachelor degree or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(4, 1, 'Global Finance', 'International financial management and investment', 'Bachelor degree or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(5, 2, 'Clinical Psychology', 'Clinical assessment and intervention techniques', 'BSc in Psychology or related field', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(6, 2, 'Organizational Psychology', 'Psychology in workplace and organizational settings', 'BSc in Psychology or related field', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(7, 2, 'Behavioral Therapy', 'Behavioral modification and therapeutic techniques', 'BSc in Psychology or related field', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(8, 2, 'Research Methods', 'Advanced research methodologies in psychology', 'BSc in Psychology or related field', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(9, 3, 'Data Analytics', 'Statistical analysis and data interpretation', 'BSc in Mathematics, Statistics, or Computer Science', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(10, 3, 'Machine Learning', 'Machine learning algorithms and applications', 'BSc in Mathematics, Statistics, or Computer Science', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(11, 3, 'Big Data Processing', 'Processing and analyzing large datasets', 'BSc in Mathematics, Statistics, or Computer Science', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(12, 3, 'Data Visualization', 'Creating effective data visualizations', 'BSc in Mathematics, Statistics, or Computer Science', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(13, 4, 'Business Management', 'Core business management principles', 'A/L qualification or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(14, 4, 'Leadership Development', 'Leadership skills and team management', 'A/L qualification or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(15, 4, 'Strategic Planning', 'Strategic thinking and planning processes', 'A/L qualification or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(16, 4, 'Business Communication', 'Effective business communication skills', 'A/L qualification or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(17, 5, 'Introduction to Psychology', 'Basic psychological concepts and theories', 'A/L qualification or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(18, 5, 'Research Methods', 'Psychological research methodologies', 'A/L qualification or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(19, 5, 'Social Psychology', 'Social behavior and group dynamics', 'A/L qualification or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(20, 5, 'Cognitive Psychology', 'Mental processes and information processing', 'A/L qualification or equivalent', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(21, 6, 'Business Fundamentals', 'Core business concepts and practices', 'A/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(22, 6, 'Management Principles', 'Management theories and applications', 'A/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(23, 6, 'Business Communication', 'Professional communication skills', 'A/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(24, 6, 'Business Ethics', 'Ethical considerations in business', 'A/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(25, 7, 'Project Planning', 'Project planning and scheduling techniques', 'A/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(26, 7, 'Risk Management', 'Identifying and managing project risks', 'A/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(27, 7, 'Project Execution', 'Project implementation and control', 'A/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(28, 7, 'Stakeholder Management', 'Managing project stakeholders', 'A/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(29, 8, 'Business Basics', 'Introduction to business concepts', 'O/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(30, 8, 'Management Skills', 'Basic management and leadership skills', 'O/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(31, 8, 'Customer Service', 'Customer service principles and practices', 'O/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(32, 9, 'Financial Accounting', 'Principles of financial accounting', 'A/L qualification with Mathematics', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(33, 9, 'Management Accounting', 'Cost and management accounting', 'A/L qualification with Mathematics', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(34, 9, 'Financial Analysis', 'Financial statement analysis', 'A/L qualification with Mathematics', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(35, 9, 'Budgeting', 'Budget preparation and control', 'A/L qualification with Mathematics', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(36, 10, 'Academic Skills', 'Study skills and academic writing', 'O/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(37, 10, 'Mathematics', 'Foundation mathematics for business', 'O/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(38, 10, 'English Language', 'English language skills for academic study', 'O/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(39, 10, 'Business Introduction', 'Introduction to business concepts', 'O/L qualification', '6 months', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(40, 11, 'science ', 'ffffffffffffffffffffffffffffv gggggggggggggggg', 'ggggggggggggggggggg gggggggggggggggg', '', 'Moratuwa', 'Active', '2025-09-02 16:40:27', '2025-09-02 16:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `moratuwa_programs`
--

CREATE TABLE `moratuwa_programs` (
  `id` int(11) NOT NULL,
  `program_code` varchar(20) NOT NULL,
  `program_name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `level` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `campus` varchar(50) DEFAULT 'Moratuwa',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moratuwa_programs`
--

INSERT INTO `moratuwa_programs` (`id`, `program_code`, `program_name`, `description`, `level`, `category`, `duration`, `campus`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MOR-MBA-GB', 'MBA in Global Business', 'Advanced business administration with global perspective and international business strategies.', 'Masters Programme', 'Business', '2 years full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(2, 'MOR-MSC-AP', 'MSc in Applied Psychology', 'Applied psychology principles for behavior modification and therapy in various settings.', 'Masters Programme', 'Psychology', '2 years full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(3, 'MOR-MSC-DS', 'MSc in Data Science', 'Data analysis, machine learning, and statistical modeling for business intelligence.', 'Masters Programme', 'Computing / Data', '2 years full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(4, 'MOR-BA-ML', 'BA (Hons) in Management & Leadership', 'Comprehensive management and leadership training for future business leaders.', 'Degree (Undergraduate)', 'Business & Management', '3 years full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(5, 'MOR-BSC-PSY', 'BSc (Hons) in Psychology', 'Foundation in psychological principles and research methodologies.', 'Degree (Undergraduate)', 'Psychology', '3 years full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(6, 'MOR-AD-BM', 'Advanced Diploma in Business Management (After A/L)', 'Advanced business management skills for post-A/L students.', 'Advanced Diploma / Diploma', 'Business', '2 years full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(7, 'MOR-AD-PM', 'Advanced Diploma in Project Management', 'Professional project management skills and methodologies.', 'Advanced Diploma / Diploma', 'Management', '2 years full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(8, 'MOR-CERT-BM', 'Certificate in Business Management', 'Basic business management principles and practices.', 'Certificate & Advanced Certificate', 'Business', '1 year full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(9, 'MOR-ACERT-FMA', 'Advanced Certificate in Financial & Management Accounting', 'Advanced accounting and financial management skills.', 'Certificate & Advanced Certificate', 'Accounting', '1 year full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(10, 'MOR-FOUND', 'Foundation Programme for Bachelor\'s Degree', 'Preparatory program for students entering bachelor\'s degree programs.', 'Foundation Programme', 'General', '1 year full-time', 'Moratuwa', 'Active', '2025-09-02 16:37:03', '2025-09-02 16:37:03'),
(11, 'MOR1756831209716', 'physical science', '', 'Masters Programme', 'Business', '3 Years', 'Moratuwa', 'Active', '2025-09-02 16:40:09', '2025-09-02 16:40:09');

-- --------------------------------------------------------

--
-- Table structure for table `nibm_courses`
--

CREATE TABLE `nibm_courses` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `course_description` text DEFAULT NULL,
  `requirement` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `campus` varchar(50) DEFAULT 'NIBM',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nibm_courses`
--

INSERT INTO `nibm_courses` (`id`, `program_id`, `course_name`, `course_description`, `requirement`, `duration`, `campus`, `status`, `created_at`, `updated_at`) VALUES
(5, 2, 'Clinical Psychology', 'Clinical assessment and intervention techniques', 'BSc in Psychology or related field', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(6, 2, 'Organizational Psychology', 'Psychology in workplace and organizational settings', 'BSc in Psychology or related field', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(7, 2, 'Behavioral Therapy', 'Behavioral modification and therapeutic techniques', 'BSc in Psychology or related field', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(8, 2, 'Research Methods', 'Advanced research methodologies in psychology', 'BSc in Psychology or related field', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(9, 3, 'Data Analytics', 'Statistical analysis and data interpretation', 'BSc in Mathematics, Statistics, or Computer Science', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(10, 3, 'Machine Learning', 'Machine learning algorithms and applications', 'BSc in Mathematics, Statistics, or Computer Science', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(11, 3, 'Big Data Processing', 'Processing and analyzing large datasets', 'BSc in Mathematics, Statistics, or Computer Science', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(12, 3, 'Data Visualization', 'Creating effective data visualizations', 'BSc in Mathematics, Statistics, or Computer Science', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(13, 4, 'Business Management', 'Core business management principles', 'A/L qualification or equivalent', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(14, 4, 'Leadership Development', 'Leadership skills and team management', 'A/L qualification or equivalent', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(15, 4, 'Strategic Planning', 'Strategic thinking and planning processes', 'A/L qualification or equivalent', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(16, 4, 'Business Communication', 'Effective business communication skills', 'A/L qualification or equivalent', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(17, 5, 'Introduction to Psychology', 'Basic psychological concepts and theories', 'A/L qualification or equivalent', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(18, 5, 'Research Methods', 'Psychological research methodologies', 'A/L qualification or equivalent', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(19, 5, 'Social Psychology', 'Social behavior and group dynamics', 'A/L qualification or equivalent', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(20, 5, 'Cognitive Psychology', 'Mental processes and information processing', 'A/L qualification or equivalent', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(21, 6, 'Business Fundamentals', 'Core business concepts and practices', 'A/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(22, 6, 'Management Principles', 'Management theories and applications', 'A/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(23, 6, 'Business Communication', 'Professional communication skills', 'A/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(24, 6, 'Business Ethics', 'Ethical considerations in business', 'A/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(25, 7, 'Project Planning', 'Project planning and scheduling techniques', 'A/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(26, 7, 'Risk Management', 'Identifying and managing project risks', 'A/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(27, 7, 'Project Execution', 'Project implementation and control', 'A/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(28, 7, 'Stakeholder Management', 'Managing project stakeholders', 'A/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(29, 8, 'Business Basics', 'Introduction to business concepts', 'O/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(30, 8, 'Management Skills', 'Basic management and leadership skills', 'O/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(31, 8, 'Customer Service', 'Customer service principles and practices', 'O/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(32, 9, 'Financial Accounting', 'Principles of financial accounting', 'A/L qualification with Mathematics', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(33, 9, 'Management Accounting', 'Cost and management accounting', 'A/L qualification with Mathematics', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(34, 9, 'Financial Analysis', 'Financial statement analysis', 'A/L qualification with Mathematics', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(35, 9, 'Budgeting', 'Budget preparation and control', 'A/L qualification with Mathematics', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(36, 10, 'Academic Skills', 'Study skills and academic writing', 'O/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(38, 10, 'English Language', 'English language skills for academic study', 'O/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(39, 10, 'Business Introduction', 'Introduction to business concepts', 'O/L qualification', '6 months', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(45, 16, 'japanese', 'jjjjjjjjjjj hhhhhhhhhhhhhhhhhhhhhhhhhhh', 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj uuuuuuuuuuuuuuuuuuuuuu', '', 'NIBM', 'Active', '2025-09-02 12:14:37', '2025-09-02 12:14:37'),
(46, 17, 'english language basic', 'english language is a ', 'english language is a english language is a english language is a ', '', 'NIBM', 'Active', '2025-09-02 12:39:51', '2025-09-02 12:39:51');

-- --------------------------------------------------------

--
-- Table structure for table `nibm_programs`
--

CREATE TABLE `nibm_programs` (
  `id` int(11) NOT NULL,
  `program_code` varchar(20) NOT NULL,
  `program_name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `level` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `campus` varchar(50) DEFAULT 'NIBM',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nibm_programs`
--

INSERT INTO `nibm_programs` (`id`, `program_code`, `program_name`, `description`, `level`, `category`, `duration`, `campus`, `status`, `created_at`, `updated_at`) VALUES
(2, 'NIBM-MSC-AP', 'MSc in Applied Psychology', 'Applied psychology principles for behavior modification and therapy in various settings.', 'Masters Programme', 'Psychology', '2 years full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-09-01 03:46:14'),
(3, 'NIBM-MSC-DS', 'MSc in Data Science', 'Data analysis, machine learning, and statistical modeling for business intelligence.', 'Masters Programme', 'Computing / Data', '2 years full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-09-01 03:46:14'),
(4, 'NIBM-BA-ML', 'BA (Hons) in Management & Leadership', 'Comprehensive management and leadership training for future business leaders.', 'Degree (Undergraduate)', 'Business & Management', '3 years full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-09-01 03:46:14'),
(5, 'NIBM-BSC-PSY', 'BSc (Hons) in Psychology', 'Foundation in psychological principles and research methodologies.', 'Degree (Undergraduate)', 'Psychology', '3 years full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-09-01 03:46:14'),
(6, 'NIBM-AD-BM', 'Advanced Diploma in Business Management (After A/L)', 'Advanced business management skills for post-A/L students.', 'Advanced Diploma / Diploma', 'Business', '2 years full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(7, 'NIBM-AD-PM', 'Advanced Diploma in Project Management', 'Professional project management skills and methodologies.', 'Advanced Diploma / Diploma', 'Management', '2 years full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-08-31 16:52:24'),
(8, 'NIBM-CERT-BM', 'Certificate in Business Management', 'Basic business management principles and practices.', 'Certificate & Advanced Certificate', 'Business', '1 year full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-09-01 03:46:14'),
(9, 'NIBM-ACERT-FMA', 'Advanced Certificate in Financial & Management Accounting', 'Advanced accounting and financial management skills.', 'Certificate & Advanced Certificate', 'Accounting', '1 year full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-09-01 03:46:14'),
(10, 'NIBM-FOUND', 'Foundation Programme for Bachelor\'s Degree', 'Preparatory program for students entering bachelor\'s degree programs.', 'Foundation Programme', 'General', '1 year full-time', 'NIBM', 'Active', '2025-08-31 16:52:24', '2025-09-01 03:46:14'),
(16, 'NIBM1756815257815', 'Languages', '', 'Masters Programme', 'Business', '3 Years', 'NIBM', 'Active', '2025-09-02 12:14:17', '2025-09-02 12:14:17'),
(17, 'NIBM1756816744188', 'english and tkt', '', 'Degree (Undergraduate)', 'Business', '3 Years', 'NIBM', 'Active', '2025-09-02 12:39:04', '2025-09-02 12:39:04');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('student','admin') NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `related_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `user_type`, `title`, `message`, `type`, `related_url`, `created_at`) VALUES
(6, 4, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 04:22:46'),
(7, 1, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 04:45:18'),
(8, 2, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 04:45:18'),
(9, 3, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 04:45:18'),
(10, 4, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 04:45:18'),
(11, 3, 'student', 'Response to your inquiry: University info', 'An admin has responded to your inquiry regarding: university-info', 'info', 'profile.php', '2025-08-30 04:46:13'),
(12, 1, 'student', 'New Announcement: Discounts', 'This is a test announcement to verify the system is working. for discounts', 'announcement', 'courses.php', '2025-08-30 05:39:15'),
(13, 3, 'student', 'New Announcement: Discounts', 'This is a test announcement to verify the system is working. for discounts', 'announcement', 'courses.php', '2025-08-30 05:39:15'),
(15, 1, 'admin', 'New Announcement: Discounts', 'This is a test announcement to verify the system is working. for discounts', 'announcement', 'admin-dashboard.php', '2025-08-30 05:39:15'),
(16, 2, 'admin', 'New Announcement: Discounts', 'This is a test announcement to verify the system is working. for discounts', 'announcement', 'admin-dashboard.php', '2025-08-30 05:39:15'),
(17, 3, 'admin', 'New Announcement: Discounts', 'This is a test announcement to verify the system is working. for discounts', 'announcement', 'admin-dashboard.php', '2025-08-30 05:39:15'),
(18, 4, 'admin', 'New Announcement: Discounts', 'This is a test announcement to verify the system is working. for discounts', 'announcement', 'admin-dashboard.php', '2025-08-30 05:39:15'),
(22, 1, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 08:52:27'),
(23, 2, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 08:52:27'),
(24, 3, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 08:52:27'),
(25, 4, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-30 08:52:27'),
(26, 3, 'student', 'Response to your inquiry: University info', 'An admin has responded to your inquiry regarding: university-info', 'info', 'profile.php', '2025-08-30 08:53:15'),
(27, 1, 'admin', 'New Inquiry: Partnership', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: partnership for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-31 13:15:30'),
(28, 2, 'admin', 'New Inquiry: Partnership', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: partnership for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-31 13:15:30'),
(29, 3, 'admin', 'New Inquiry: Partnership', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: partnership for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-31 13:15:30'),
(30, 4, 'admin', 'New Inquiry: Partnership', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: partnership for ICBT Campus', 'info', 'admin-dashboard.php', '2025-08-31 13:15:30'),
(31, 3, 'student', 'Response to your inquiry: Partnership', 'An admin has responded to your inquiry regarding: partnership', 'info', 'profile.php', '2025-08-31 13:16:26'),
(32, 1, 'admin', 'New Inquiry: Feedback', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: feedback for NIBM Campus', 'info', 'admin-dashboard.php', '2025-08-31 17:38:37'),
(33, 2, 'admin', 'New Inquiry: Feedback', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: feedback for NIBM Campus', 'info', 'admin-dashboard.php', '2025-08-31 17:38:37'),
(34, 3, 'admin', 'New Inquiry: Feedback', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: feedback for NIBM Campus', 'info', 'admin-dashboard.php', '2025-08-31 17:38:37'),
(35, 4, 'admin', 'New Inquiry: Feedback', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: feedback for NIBM Campus', 'info', 'admin-dashboard.php', '2025-08-31 17:38:37'),
(36, 1, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-08-31 17:41:15'),
(37, 2, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-08-31 17:41:15'),
(38, 3, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-08-31 17:41:15'),
(39, 4, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-08-31 17:41:15'),
(40, 1, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-01 04:11:56'),
(41, 2, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-01 04:11:56'),
(42, 3, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-01 04:11:56'),
(43, 4, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-01 04:11:56'),
(44, 1, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-01 04:14:06'),
(45, 5, 'student', 'Response to your inquiry: University info', 'An admin has responded to your inquiry regarding: university-info', 'info', 'profile.php', '2025-09-01 04:14:23'),
(46, 5, 'student', 'Response to your inquiry: University info', 'An admin has responded to your inquiry regarding: university-info', 'info', 'profile.php', '2025-09-01 04:14:47'),
(47, 3, 'student', 'Inquiry closed: University info', 'Your inquiry regarding: university-info has been closed.', 'info', 'profile.php', '2025-09-01 04:31:22'),
(48, 3, 'student', 'Inquiry deleted: University info', 'Your inquiry regarding: university-info has been deleted by an administrator.', 'warning', 'profile.php', '2025-09-01 04:34:43'),
(49, 5, 'student', 'Inquiry closed: University info', 'Your inquiry regarding: university-info has been closed.', 'info', 'profile.php', '2025-09-01 04:34:48'),
(50, 5, 'student', 'Inquiry deleted: University info', 'Your inquiry regarding: university-info has been deleted by an administrator.', 'warning', 'profile.php', '2025-09-01 04:41:58'),
(51, 3, 'student', 'Inquiry deleted: University info', 'Your inquiry regarding: university-info has been deleted by an administrator.', 'warning', 'profile.php', '2025-09-01 04:42:02'),
(52, 1, 'student', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'courses.php', '2025-09-01 11:22:42'),
(53, 3, 'student', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'courses.php', '2025-09-01 11:22:42'),
(54, 5, 'student', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'courses.php', '2025-09-01 11:22:42'),
(55, 1, 'admin', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'admin-dashboard.php', '2025-09-01 11:22:42'),
(56, 2, 'admin', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'admin-dashboard.php', '2025-09-01 11:22:42'),
(57, 3, 'admin', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'admin-dashboard.php', '2025-09-01 11:22:42'),
(58, 4, 'admin', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'admin-dashboard.php', '2025-09-01 11:22:42'),
(62, 1, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-01 12:00:31'),
(63, 2, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-01 12:00:31'),
(64, 3, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-01 12:00:31'),
(65, 4, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-01 12:00:31'),
(66, 1, 'student', 'New Announcement: hiiiiiiiiiii', 'hhhhhhhhhhhhhhhhhhhhhhh', 'announcement', 'courses.php', '2025-09-01 13:47:04'),
(67, 3, 'student', 'New Announcement: hiiiiiiiiiii', 'hhhhhhhhhhhhhhhhhhhhhhh', 'announcement', 'courses.php', '2025-09-01 13:47:04'),
(68, 5, 'student', 'New Announcement: hiiiiiiiiiii', 'hhhhhhhhhhhhhhhhhhhhhhh', 'announcement', 'courses.php', '2025-09-01 13:47:04'),
(69, 1, 'admin', 'New Announcement: hiiiiiiiiiii', 'hhhhhhhhhhhhhhhhhhhhhhh', 'announcement', 'admin-dashboard.php', '2025-09-01 13:47:04'),
(70, 2, 'admin', 'New Announcement: hiiiiiiiiiii', 'hhhhhhhhhhhhhhhhhhhhhhh', 'announcement', 'admin-dashboard.php', '2025-09-01 13:47:04'),
(71, 3, 'admin', 'New Announcement: hiiiiiiiiiii', 'hhhhhhhhhhhhhhhhhhhhhhh', 'announcement', 'admin-dashboard.php', '2025-09-01 13:47:04'),
(72, 4, 'admin', 'New Announcement: hiiiiiiiiiii', 'hhhhhhhhhhhhhhhhhhhhhhh', 'announcement', 'admin-dashboard.php', '2025-09-01 13:47:04'),
(76, 3, 'student', 'Response to your inquiry: Course inquiry', 'An admin has responded to your inquiry regarding: course-inquiry', 'info', 'profile.php', '2025-09-01 14:31:17'),
(77, 1, 'student', 'New Announcement: peradeniya campus', 'university of peradeniya campus events', 'announcement', 'courses.php', '2025-09-01 14:32:03'),
(78, 3, 'student', 'New Announcement: peradeniya campus', 'university of peradeniya campus events', 'announcement', 'courses.php', '2025-09-01 14:32:03'),
(79, 5, 'student', 'New Announcement: peradeniya campus', 'university of peradeniya campus events', 'announcement', 'courses.php', '2025-09-01 14:32:03'),
(80, 1, 'admin', 'New Announcement: peradeniya campus', 'university of peradeniya campus events', 'announcement', 'admin-dashboard.php', '2025-09-01 14:32:03'),
(81, 2, 'admin', 'New Announcement: peradeniya campus', 'university of peradeniya campus events', 'announcement', 'admin-dashboard.php', '2025-09-01 14:32:03'),
(82, 3, 'admin', 'New Announcement: peradeniya campus', 'university of peradeniya campus events', 'announcement', 'admin-dashboard.php', '2025-09-01 14:32:03'),
(83, 4, 'admin', 'New Announcement: peradeniya campus', 'university of peradeniya campus events', 'announcement', 'admin-dashboard.php', '2025-09-01 14:32:03'),
(87, 1, 'admin', 'New Inquiry: Feedback', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: feedback for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:40:58'),
(88, 3, 'student', 'Response to your inquiry: Feedback', 'An admin has responded to your inquiry regarding: feedback', 'info', 'profile.php', '2025-09-01 16:41:31'),
(89, 1, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:45:18'),
(90, 2, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:45:18'),
(91, 3, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:45:18'),
(92, 4, 'admin', 'New Inquiry: University info', 'New inquiry from Samunda De Silva De Silva regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:45:18'),
(93, 5, 'student', 'Response to your inquiry: University info', 'An admin has responded to your inquiry regarding: university-info', 'info', 'profile.php', '2025-09-01 16:45:45'),
(94, 1, 'admin', 'New Inquiry: General', 'New inquiry from Samunda De Silva De Silva regarding: general for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:47:35'),
(95, 2, 'admin', 'New Inquiry: General', 'New inquiry from Samunda De Silva De Silva regarding: general for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:47:35'),
(96, 3, 'admin', 'New Inquiry: General', 'New inquiry from Samunda De Silva De Silva regarding: general for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:47:35'),
(97, 4, 'admin', 'New Inquiry: General', 'New inquiry from Samunda De Silva De Silva regarding: general for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-01 16:47:35'),
(98, 5, 'student', 'Response to your inquiry: General', 'An admin has responded to your inquiry regarding: general', 'info', 'profile.php', '2025-09-01 16:49:52'),
(99, 1, 'student', 'New Announcement: New Semester Registration', 'Registration for the upcoming semester opens on 10th September. Please complete your enrollment befo...', 'announcement', 'courses.php', '2025-09-02 06:04:10'),
(100, 3, 'student', 'New Announcement: New Semester Registration', 'Registration for the upcoming semester opens on 10th September. Please complete your enrollment befo...', 'announcement', 'courses.php', '2025-09-02 06:04:10'),
(101, 5, 'student', 'New Announcement: New Semester Registration', 'Registration for the upcoming semester opens on 10th September. Please complete your enrollment befo...', 'announcement', 'courses.php', '2025-09-02 06:04:10'),
(102, 1, 'admin', 'New Announcement: New Semester Registration', 'Registration for the upcoming semester opens on 10th September. Please complete your enrollment befo...', 'announcement', 'admin-dashboard.php', '2025-09-02 06:04:10'),
(103, 2, 'admin', 'New Announcement: New Semester Registration', 'Registration for the upcoming semester opens on 10th September. Please complete your enrollment befo...', 'announcement', 'admin-dashboard.php', '2025-09-02 06:04:10'),
(104, 3, 'admin', 'New Announcement: New Semester Registration', 'Registration for the upcoming semester opens on 10th September. Please complete your enrollment befo...', 'announcement', 'admin-dashboard.php', '2025-09-02 06:04:10'),
(105, 4, 'admin', 'New Announcement: New Semester Registration', 'Registration for the upcoming semester opens on 10th September. Please complete your enrollment befo...', 'announcement', 'admin-dashboard.php', '2025-09-02 06:04:10'),
(109, 1, 'student', 'New Announcement: Library Timings Update', 'The library will now be open from 8:00 AM to 8:00 PM on weekdays.', 'announcement', 'courses.php', '2025-09-02 06:04:50'),
(110, 3, 'student', 'New Announcement: Library Timings Update', 'The library will now be open from 8:00 AM to 8:00 PM on weekdays.', 'announcement', 'courses.php', '2025-09-02 06:04:50'),
(111, 5, 'student', 'New Announcement: Library Timings Update', 'The library will now be open from 8:00 AM to 8:00 PM on weekdays.', 'announcement', 'courses.php', '2025-09-02 06:04:50'),
(112, 1, 'admin', 'New Announcement: Library Timings Update', 'The library will now be open from 8:00 AM to 8:00 PM on weekdays.', 'announcement', 'admin-dashboard.php', '2025-09-02 06:04:50'),
(113, 2, 'admin', 'New Announcement: Library Timings Update', 'The library will now be open from 8:00 AM to 8:00 PM on weekdays.', 'announcement', 'admin-dashboard.php', '2025-09-02 06:04:50'),
(114, 3, 'admin', 'New Announcement: Library Timings Update', 'The library will now be open from 8:00 AM to 8:00 PM on weekdays.', 'announcement', 'admin-dashboard.php', '2025-09-02 06:04:50'),
(115, 4, 'admin', 'New Announcement: Library Timings Update', 'The library will now be open from 8:00 AM to 8:00 PM on weekdays.', 'announcement', 'admin-dashboard.php', '2025-09-02 06:04:50'),
(119, 1, 'student', 'New Announcement: Tech Workshop', 'A free coding workshop will be held on 15th September in the Computer Lab. All students are welcome.', 'announcement', 'courses.php', '2025-09-02 06:05:34'),
(120, 3, 'student', 'New Announcement: Tech Workshop', 'A free coding workshop will be held on 15th September in the Computer Lab. All students are welcome.', 'announcement', 'courses.php', '2025-09-02 06:05:34'),
(121, 5, 'student', 'New Announcement: Tech Workshop', 'A free coding workshop will be held on 15th September in the Computer Lab. All students are welcome.', 'announcement', 'courses.php', '2025-09-02 06:05:34'),
(122, 1, 'admin', 'New Announcement: Tech Workshop', 'A free coding workshop will be held on 15th September in the Computer Lab. All students are welcome.', 'announcement', 'admin-dashboard.php', '2025-09-02 06:05:34'),
(123, 2, 'admin', 'New Announcement: Tech Workshop', 'A free coding workshop will be held on 15th September in the Computer Lab. All students are welcome.', 'announcement', 'admin-dashboard.php', '2025-09-02 06:05:34'),
(124, 3, 'admin', 'New Announcement: Tech Workshop', 'A free coding workshop will be held on 15th September in the Computer Lab. All students are welcome.', 'announcement', 'admin-dashboard.php', '2025-09-02 06:05:34'),
(125, 4, 'admin', 'New Announcement: Tech Workshop', 'A free coding workshop will be held on 15th September in the Computer Lab. All students are welcome.', 'announcement', 'admin-dashboard.php', '2025-09-02 06:05:34'),
(126, 1, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:53:34'),
(127, 1, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:54:30'),
(128, 2, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:54:30'),
(129, 3, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:54:30'),
(130, 4, 'admin', 'New Inquiry: University info', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: university-info for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:54:30'),
(131, 1, 'admin', 'New Inquiry: General', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: general for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:55:21'),
(132, 2, 'admin', 'New Inquiry: General', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: general for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:55:21'),
(133, 3, 'admin', 'New Inquiry: General', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: general for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:55:21'),
(134, 4, 'admin', 'New Inquiry: General', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: general for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:55:21'),
(135, 1, 'admin', 'New Inquiry: Partnership', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: partnership for Moratuwa Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:56:05'),
(136, 2, 'admin', 'New Inquiry: Partnership', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: partnership for Moratuwa Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:56:05'),
(137, 3, 'admin', 'New Inquiry: Partnership', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: partnership for Moratuwa Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:56:05'),
(138, 4, 'admin', 'New Inquiry: Partnership', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: partnership for Moratuwa Campus', 'info', 'admin-dashboard.php', '2025-09-02 10:56:05'),
(139, 3, 'student', 'Response to your inquiry: Course inquiry', 'An admin has responded to your inquiry regarding: course-inquiry', 'info', 'profile.php', '2025-09-02 10:56:52'),
(140, 3, 'student', 'Response to your inquiry: University info', 'An admin has responded to your inquiry regarding: university-info', 'info', 'profile.php', '2025-09-02 10:57:18'),
(141, 3, 'student', 'Response to your inquiry: General', 'An admin has responded to your inquiry regarding: general', 'info', 'profile.php', '2025-09-02 10:57:44'),
(142, 3, 'student', 'Response to your inquiry: Partnership', 'An admin has responded to your inquiry regarding: partnership', 'info', 'profile.php', '2025-09-02 10:58:16'),
(143, 1, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-02 11:32:05'),
(144, 3, 'student', 'Inquiry closed: General', 'Your inquiry regarding: general has been closed.', 'info', 'profile.php', '2025-09-02 14:50:39'),
(145, 3, 'student', 'Inquiry deleted: Partnership', 'Your inquiry regarding: partnership has been deleted by an administrator.', 'warning', 'profile.php', '2025-09-02 16:55:10'),
(146, 3, 'student', 'Inquiry deleted: Feedback', 'Your inquiry regarding: feedback has been deleted by an administrator.', 'warning', 'profile.php', '2025-09-02 16:55:13'),
(147, 3, 'student', 'Inquiry deleted: Course inquiry', 'Your inquiry regarding: course-inquiry has been deleted by an administrator.', 'warning', 'profile.php', '2025-09-02 16:55:17'),
(148, 3, 'student', 'Response to your inquiry: Course inquiry', 'An admin has responded to your inquiry regarding: course-inquiry', 'info', 'profile.php', '2025-09-02 16:55:33'),
(149, 1, 'admin', 'New Review Submitted', 'New review submitted by Test Student for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-03 03:41:08'),
(150, 2, 'admin', 'New Review Submitted', 'New review submitted by Test Student for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-03 03:41:08'),
(151, 3, 'admin', 'New Review Submitted', 'New review submitted by Test Student for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-03 03:41:08'),
(152, 4, 'admin', 'New Review Submitted', 'New review submitted by Test Student for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-03 03:41:08'),
(153, 1, 'admin', 'New Inquiry: Application help', 'New inquiry from Nethmi Suraweeraarachchi Suraweeraarachchi regarding: application-help for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-03 10:59:34'),
(154, 8, 'student', 'Response to your inquiry: Application help', 'An admin has responded to your inquiry regarding: application-help', 'info', 'profile.php', '2025-09-03 11:38:29'),
(155, 1, 'admin', 'New Inquiry: University info', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: university-info for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-03 14:18:45'),
(156, 8, 'student', 'Response to your inquiry: University info', 'An admin has responded to your inquiry regarding: university-info', 'info', 'profile.php', '2025-09-03 14:20:39'),
(157, 1, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-03 16:24:52'),
(158, 8, 'student', 'Response to your inquiry: Course inquiry', 'An admin has responded to your inquiry regarding: course-inquiry', 'info', 'profile.php', '2025-09-03 16:26:27'),
(159, 8, 'student', 'Inquiry deleted: Application help', 'Your inquiry regarding: application-help has been deleted by an administrator.', 'warning', 'profile.php', '2025-09-03 16:34:31'),
(160, 8, 'student', 'Inquiry deleted: University info', 'Your inquiry regarding: university-info has been deleted by an administrator.', 'warning', 'profile.php', '2025-09-03 16:36:07'),
(161, 8, 'student', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'courses.php', '2025-09-03 16:53:38'),
(162, 1, 'admin', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'admin-dashboard.php', '2025-09-03 16:53:38'),
(163, 2, 'admin', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'admin-dashboard.php', '2025-09-03 16:53:38'),
(164, 3, 'admin', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'admin-dashboard.php', '2025-09-03 16:53:38'),
(165, 4, 'admin', 'New Announcement: 📢 New Update on ICBT Campus', 'We are excited to announce that the ICBT Campus page has been successfully added. Stay tuned for mor...', 'announcement', 'admin-dashboard.php', '2025-09-03 16:53:38'),
(166, 1, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:39:09'),
(167, 2, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:39:09'),
(168, 3, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:39:09'),
(169, 4, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for NIBM Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:39:09'),
(170, 8, 'student', 'Response to your inquiry: Course inquiry', 'An admin has responded to your inquiry regarding: course-inquiry', 'info', 'profile.php', '2025-09-05 09:40:45'),
(171, 8, 'student', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'courses.php', '2025-09-05 09:42:55'),
(172, 1, 'admin', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'admin-dashboard.php', '2025-09-05 09:42:55'),
(173, 2, 'admin', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'admin-dashboard.php', '2025-09-05 09:42:55'),
(174, 3, 'admin', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'admin-dashboard.php', '2025-09-05 09:42:55'),
(175, 4, 'admin', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'admin-dashboard.php', '2025-09-05 09:42:55'),
(179, 1, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:50:34'),
(180, 2, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:50:34'),
(181, 3, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:50:34'),
(182, 4, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Peradeniya Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:50:34'),
(183, 8, 'student', 'Response to your inquiry: Course inquiry', 'An admin has responded to your inquiry regarding: course-inquiry', 'info', 'profile.php', '2025-09-05 09:52:19'),
(184, 8, 'student', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'courses.php', '2025-09-05 09:53:59'),
(185, 1, 'admin', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'admin-dashboard.php', '2025-09-05 09:53:59'),
(186, 2, 'admin', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'admin-dashboard.php', '2025-09-05 09:53:59'),
(187, 3, 'admin', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'admin-dashboard.php', '2025-09-05 09:53:59'),
(188, 4, 'admin', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'admin-dashboard.php', '2025-09-05 09:53:59'),
(192, 1, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Moratuwa Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:56:51'),
(193, 2, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Moratuwa Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:56:51'),
(194, 3, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Moratuwa Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:56:51'),
(195, 4, 'admin', 'New Inquiry: Course inquiry', 'New inquiry from Anushka Suraweeraarachchi Suraweeraarachchi regarding: course-inquiry for Moratuwa Campus', 'info', 'admin-dashboard.php', '2025-09-05 09:56:51'),
(196, 8, 'student', 'New Announcement: New Intake – BSc in Information Technology', '“Applications are now open for the BSc in Information Technology program at Moratuwa Campus for th...', 'announcement', 'courses.php', '2025-09-05 10:06:48'),
(197, 1, 'admin', 'New Announcement: New Intake – BSc in Information Technology', '“Applications are now open for the BSc in Information Technology program at Moratuwa Campus for th...', 'announcement', 'admin-dashboard.php', '2025-09-05 10:06:48'),
(198, 2, 'admin', 'New Announcement: New Intake – BSc in Information Technology', '“Applications are now open for the BSc in Information Technology program at Moratuwa Campus for th...', 'announcement', 'admin-dashboard.php', '2025-09-05 10:06:48'),
(199, 3, 'admin', 'New Announcement: New Intake – BSc in Information Technology', '“Applications are now open for the BSc in Information Technology program at Moratuwa Campus for th...', 'announcement', 'admin-dashboard.php', '2025-09-05 10:06:48'),
(200, 4, 'admin', 'New Announcement: New Intake – BSc in Information Technology', '“Applications are now open for the BSc in Information Technology program at Moratuwa Campus for th...', 'announcement', 'admin-dashboard.php', '2025-09-05 10:06:48'),
(201, 1, 'admin', 'New Review Submitted', 'New review submitted by Anushka Suraweeraarachchi for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-06 10:00:07'),
(202, 2, 'admin', 'New Review Submitted', 'New review submitted by Anushka Suraweeraarachchi for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-06 10:00:07'),
(203, 3, 'admin', 'New Review Submitted', 'New review submitted by Anushka Suraweeraarachchi for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-06 10:00:07'),
(204, 4, 'admin', 'New Review Submitted', 'New review submitted by Anushka Suraweeraarachchi for ICBT Campus', 'info', 'admin-dashboard.php', '2025-09-06 10:00:07'),
(205, 8, 'student', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'courses.php', '2025-09-06 14:52:35'),
(206, 1, 'admin', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:52:35'),
(207, 2, 'admin', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:52:35'),
(208, 3, 'admin', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:52:35'),
(209, 4, 'admin', 'New Announcement: New Intake – BSc in Data Science', 'Admissions are now open for the upcoming intake of the BSc in Data Science program at NIBM Campus. L...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:52:35'),
(213, 8, 'student', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'courses.php', '2025-09-06 14:53:27'),
(214, 1, 'admin', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:53:27'),
(215, 2, 'admin', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:53:27'),
(216, 3, 'admin', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:53:27'),
(217, 4, 'admin', 'New Announcement: Postgraduate Intake 2026 – MSc in Computer Science', 'Applications are now open for the MSc in Computer Science program at the University of Peradeniya. T...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:53:27'),
(221, 8, 'student', 'New Announcement: New Intake – BSc in Information Technology', 'Applications are now open for the BSc in Information Technology program at Moratuwa Campus for the 2...', 'announcement', 'courses.php', '2025-09-06 14:54:15'),
(222, 1, 'admin', 'New Announcement: New Intake – BSc in Information Technology', 'Applications are now open for the BSc in Information Technology program at Moratuwa Campus for the 2...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:54:15'),
(223, 2, 'admin', 'New Announcement: New Intake – BSc in Information Technology', 'Applications are now open for the BSc in Information Technology program at Moratuwa Campus for the 2...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:54:15'),
(224, 3, 'admin', 'New Announcement: New Intake – BSc in Information Technology', 'Applications are now open for the BSc in Information Technology program at Moratuwa Campus for the 2...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:54:15'),
(225, 4, 'admin', 'New Announcement: New Intake – BSc in Information Technology', 'Applications are now open for the BSc in Information Technology program at Moratuwa Campus for the 2...', 'announcement', 'admin-dashboard.php', '2025-09-06 14:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `peradeniya_courses`
--

CREATE TABLE `peradeniya_courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_description` text DEFAULT NULL,
  `program_id` int(11) NOT NULL,
  `study_level` varchar(100) NOT NULL,
  `requirement` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `campus` varchar(50) DEFAULT 'Peradeniya',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peradeniya_courses`
--

INSERT INTO `peradeniya_courses` (`id`, `course_name`, `course_description`, `program_id`, `study_level`, `requirement`, `duration`, `campus`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Calculus I', 'Introduction to differential and integral calculus', 1, 'Degree (Undergraduate)', 'A/L Mathematics', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(2, 'Linear Algebra', 'Vector spaces, linear transformations, and matrices', 1, 'Degree (Undergraduate)', 'Calculus I', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(3, 'Data Structures and Algorithms', 'Advanced programming concepts and algorithm design', 2, 'Masters Programme', 'Bachelor in Computer Science', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(4, 'Machine Learning', 'Introduction to machine learning algorithms and applications', 2, 'Masters Programme', 'Statistics, Programming', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(5, 'Real Analysis', 'Rigorous treatment of real number system and limits', 3, 'Degree (Undergraduate)', 'Calculus II', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(6, 'Abstract Algebra', 'Groups, rings, and fields', 3, 'Degree (Undergraduate)', 'Linear Algebra', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(7, 'Advanced Engineering Mathematics', 'Complex analysis and partial differential equations', 4, 'Masters Programme', 'Engineering Mathematics', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(8, 'Research Methods', 'Scientific research methodology and thesis writing', 4, 'Masters Programme', 'None', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(9, 'Classical Mechanics', 'Newtonian mechanics and Lagrangian formulation', 5, 'Degree (Undergraduate)', 'Calculus I, Physics', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(10, 'Quantum Mechanics', 'Introduction to quantum theory and applications', 5, 'Degree (Undergraduate)', 'Classical Mechanics', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(11, 'Web Development', 'HTML, CSS, JavaScript, and web frameworks', 6, 'Advanced Diploma / Diploma', 'Basic Computer Skills', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(12, 'Database Management', 'SQL, database design, and administration', 6, 'Advanced Diploma / Diploma', 'None', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(13, 'Python Programming', 'Introduction to Python programming language', 7, 'Certificate & Advanced Certificate', 'Basic Computer Skills', '6 Months', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(14, 'Java Programming', 'Object-oriented programming with Java', 7, 'Certificate & Advanced Certificate', 'Basic Programming', '6 Months', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(15, 'Basic Mathematics', 'Foundation mathematics for science programs', 8, 'Foundation Programme', 'O/L Mathematics', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(16, 'Introduction to Science', 'Overview of physics, chemistry, and biology', 8, 'Foundation Programme', 'None', '1 Semester', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(19, 'IT', 'yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy nnnnnnnnnnnnnnnnnnnnnnn', 13, '', 'uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu hhhhhhhhhhhhhhhhh', '', 'University of Peradeniya', '2025-09-02 14:01:35', '2025-09-02 14:09:41', 'Active'),
(20, 'italian', 'italian language is ', 18, '', 'italian language is italian language is italian language is italian language is ', '', 'University of Peradeniya', '2025-09-02 14:20:27', '2025-09-02 14:20:27', 'Active'),
(21, 'english', 'gggggggggggggggggggggggggggggg', 19, '', 'ggggggggggggggggggggggg', '', 'University of Peradeniya', '2025-09-02 14:36:06', '2025-09-02 14:36:06', 'Active'),
(22, 'english', 'hhhhhhhhhhhhh', 21, '', 'hhhhhhhhhhhhhhhh', '', 'University of Peradeniya', '2025-09-02 15:45:41', '2025-09-02 15:45:41', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `peradeniya_programs`
--

CREATE TABLE `peradeniya_programs` (
  `id` int(11) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `program_code` varchar(50) NOT NULL,
  `level` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT 'Engineering',
  `duration` varchar(50) DEFAULT '4 Years',
  `description` text DEFAULT NULL,
  `campus` varchar(50) DEFAULT 'Peradeniya',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peradeniya_programs`
--

INSERT INTO `peradeniya_programs` (`id`, `program_name`, `program_code`, `level`, `category`, `duration`, `description`, `campus`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Bachelor of Science in Engineering', 'BSE001', 'Degree (Undergraduate)', 'Engineering', '4 Years', 'Comprehensive engineering program covering multiple disciplines', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(2, 'Master of Science in Computer Science', 'MSCS001', 'Masters Programme', 'Computer Science', '2 Years', 'Advanced computer science program with research focus', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(3, 'Bachelor of Science in Mathematics', 'BSM001', 'Degree (Undergraduate)', 'Mathematics', '3 Years', 'Pure and applied mathematics program', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(4, 'Master of Engineering', 'ME001', 'Masters Programme', 'Engineering', '2 Years', 'Advanced engineering program with specialization options', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(5, 'Bachelor of Science in Physics', 'BSP001', 'Degree (Undergraduate)', 'Physics', '3 Years', 'Physics program with laboratory and theoretical components', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(6, 'Diploma in Information Technology', 'DIT001', 'Advanced Diploma / Diploma', 'Information Technology', '2 Years', 'Practical IT skills and knowledge', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(7, 'Certificate in Programming', 'CIP001', 'Certificate & Advanced Certificate', 'Programming', '1 Year', 'Basic programming skills certificate', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(8, 'Foundation Course in Science', 'FCS001', 'Foundation Programme', 'Science', '1 Year', 'Preparatory course for science programs', 'Peradeniya', '2025-09-01 14:10:57', '2025-09-01 14:10:57', 'Active'),
(13, 'Information Technology', 'PERA1756821664093', 'Certificate & Advanced Certificate', 'Business', '3 Years', '', 'University of Peradeniya', '2025-09-02 14:01:04', '2025-09-02 14:09:41', 'Active'),
(17, 'CCNA Routing & Switching', 'PERA004', 'Professional Short Courses', 'CEIT', '3 months intensive', 'Cisco Certified Network Associate training in routing and switching technologies.', 'University of Peradeniya', '2025-09-02 14:01:08', '2025-09-02 14:01:08', 'Active'),
(18, 'Languages', 'PERA1756822787825', 'Diploma', 'Business', '3 Years', '', 'University of Peradeniya', '2025-09-02 14:19:47', '2025-09-02 14:19:47', 'Active'),
(19, 'business info', 'PERA1756823756940', 'Professional Short Courses', 'Other', '6 months', '55555555555555', 'University of Peradeniya', '2025-09-02 14:35:56', '2025-09-02 14:35:56', 'Active'),
(20, 'business info', 'PERA1756827649687', 'Certificate & Advanced Certificate', 'Business', '3 Years', '', 'University of Peradeniya', '2025-09-02 15:40:49', '2025-09-02 15:40:49', 'Active'),
(21, 'business info', 'PERA1756827935321', 'Masters Programme', 'Business', '3 Years', '', 'University of Peradeniya', '2025-09-02 15:45:35', '2025-09-02 15:45:35', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `study_level` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `seats_available` int(11) DEFAULT 50,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `university_name` varchar(255) NOT NULL,
  `courses` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `recommend_or_not_recommended` enum('yes','no') NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `student_id`, `university_name`, `courses`, `rating`, `recommend_or_not_recommended`, `status`, `created_at`, `updated_at`) VALUES
(2, 8, 'ICBT Campus', 'Bsc Computing network', 5, 'yes', 'pending', '2025-09-06 10:00:07', '2025-09-06 10:00:07');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nic_passport` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `account_status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `phone`, `password_hash`, `nic_passport`, `address`, `account_status`, `created_at`, `updated_at`) VALUES
(8, 'Anushka', 'Suraweeraarachchi', 'nethmianushka250@gmail.com', '775299354', '$2y$10$G4BhSUNdP2kYaLcI0nS2qOwhlyrpQnbItNY7XyLX14tZQyRI9GeFa', NULL, NULL, 'active', '2025-09-03 07:00:08', '2025-09-03 11:14:23');

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_applications`
-- (See below for the actual view)
--
CREATE TABLE `student_applications` (
);

-- --------------------------------------------------------

--
-- Table structure for table `study_levels`
--

CREATE TABLE `study_levels` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `study_levels`
--

INSERT INTO `study_levels` (`id`, `name`, `description`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Postgraduate', 'Advanced degree programs for graduates', 1, 1, '2025-08-30 08:05:26', '2025-08-30 08:05:26'),
(2, 'Undergraduate', 'Bachelor and diploma level programs', 2, 1, '2025-08-30 08:05:26', '2025-08-30 08:05:26'),
(3, 'After O/L & A/L', 'Foundation and certificate programs', 3, 1, '2025-08-30 08:05:26', '2025-08-30 08:05:26');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `accreditation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `name`, `logo`, `description`, `location`, `accreditation`, `created_at`) VALUES
(1, 'ICBT Campus', 'LogoICBT.png', 'International College of Business and Technology', 'Colombo, Sri Lanka', 'UGC Approved', '2025-08-27 13:54:16'),
(2, 'NIBM', 'LogoNibm.png', 'National Institute of Business Management', 'Colombo, Sri Lanka', 'Government Institute', '2025-08-27 13:54:16'),
(3, 'University of Peradeniya', 'LogoPeradeniya.png', 'Premier University of Sri Lanka', 'Peradeniya, Sri Lanka', 'UGC Approved', '2025-08-27 13:54:16'),
(4, 'University of Moratuwa', 'LogoMoratuwa.png', 'Leading Engineering and Technology University', 'Moratuwa, Sri Lanka', 'UGC Approved', '2025-08-27 13:54:16'),
(5, 'NIBM Campus', NULL, 'National Institute of Business Management', NULL, NULL, '2025-08-29 11:36:12'),
(6, 'Peradeniya Campus', NULL, 'University of Peradeniya', NULL, NULL, '2025-08-29 11:36:12'),
(7, 'Moratuwa Campus', NULL, 'University of Moratuwa', NULL, NULL, '2025-08-29 11:36:12');

-- --------------------------------------------------------

--
-- Stand-in structure for view `university_inquiries_summary`
-- (See below for the actual view)
--
CREATE TABLE `university_inquiries_summary` (
`university_id` int(11)
,`university_name` varchar(100)
,`total_inquiries` bigint(21)
,`pending_inquiries` bigint(21)
,`answered_inquiries` bigint(21)
);

-- --------------------------------------------------------

--
-- Structure for view `application_summary`
--
DROP TABLE IF EXISTS `application_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `application_summary`  AS SELECT `ca`.`id` AS `id`, `ca`.`application_number` AS `application_number`, `ca`.`course_name` AS `course_name`, `ca`.`university` AS `university`, `ca`.`study_level` AS `study_level`, `ca`.`program` AS `program`, concat(`ca`.`first_name`,' ',`ca`.`last_name`) AS `full_name`, `ca`.`email` AS `email`, `ca`.`phone` AS `phone`, `ca`.`status` AS `status`, `ca`.`application_date` AS `application_date`, `ca`.`highest_qualification` AS `highest_qualification`, `ca`.`review_date` AS `review_date`, `ca`.`review_notes` AS `review_notes`, `ca`.`institution` AS `institution`, `ca`.`graduation_year` AS `graduation_year` FROM `course_applications` AS `ca` ORDER BY `ca`.`application_date` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `student_applications`
--
DROP TABLE IF EXISTS `student_applications`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_applications`  AS SELECT `ca`.`id` AS `id`, `ca`.`user_id` AS `user_id`, `ca`.`course_name` AS `course_name`, `ca`.`university` AS `university`, `ca`.`study_level` AS `study_level`, `ca`.`program` AS `program`, `ca`.`first_name` AS `first_name`, `ca`.`last_name` AS `last_name`, `ca`.`email` AS `email`, `ca`.`phone` AS `phone`, `ca`.`date_of_birth` AS `date_of_birth`, `ca`.`highest_qualification` AS `highest_qualification`, `ca`.`institution` AS `institution`, `ca`.`graduation_year` AS `graduation_year`, `ca`.`declaration_accepted` AS `declaration_accepted`, `ca`.`terms_accepted` AS `terms_accepted`, `ca`.`status` AS `status`, `ca`.`application_date` AS `application_date`, `ca`.`review_date` AS `review_date`, `ca`.`reviewed_by` AS `reviewed_by`, `ca`.`review_notes` AS `review_notes`, `ca`.`application_number` AS `application_number`, concat(`ca`.`first_name`,' ',`ca`.`last_name`) AS `full_name`, `s`.`email` AS `student_email`, `s`.`phone` AS `student_phone` FROM (`course_applications` `ca` join `students` `s` on(`ca`.`user_id` = `s`.`id`)) ORDER BY `ca`.`application_date` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `university_inquiries_summary`
--
DROP TABLE IF EXISTS `university_inquiries_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `university_inquiries_summary`  AS SELECT `u`.`id` AS `university_id`, `u`.`name` AS `university_name`, count(`i`.`id`) AS `total_inquiries`, count(case when `i`.`response_status` = 'pending' then 1 end) AS `pending_inquiries`, count(case when `i`.`response_status` = 'answered' then 1 end) AS `answered_inquiries` FROM (`universities` `u` left join `inquiries` `i` on(`u`.`id` = `i`.`university_id`)) GROUP BY `u`.`id`, `u`.`name` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_admins_email` (`email`),
  ADD KEY `idx_admins_campus` (`campus`),
  ADD KEY `idx_admins_role` (`role`);

--
-- Indexes for table `admin_actions_log`
--
ALTER TABLE `admin_actions_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_action_type` (`action_type`),
  ADD KEY `idx_table` (`table_name`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_admin_settings` (`admin_id`),
  ADD KEY `idx_campus` (`campus`),
  ADD KEY `idx_theme` (`theme`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audience` (`audience`),
  ADD KEY `idx_campus` (`campus`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `application_status_log`
--
ALTER TABLE `application_status_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_log_application` (`application_id`),
  ADD KEY `idx_status_log_date` (`change_date`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_courses_program` (`program_id`),
  ADD KEY `idx_courses_title` (`title`),
  ADD KEY `idx_courses_status` (`status`);

--
-- Indexes for table `course_applications`
--
ALTER TABLE `course_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_course_applications_user` (`user_id`),
  ADD KEY `idx_course_applications_course` (`course_name`),
  ADD KEY `idx_course_applications_university` (`university`),
  ADD KEY `idx_course_applications_status` (`status`),
  ADD KEY `idx_course_applications_date` (`application_date`),
  ADD KEY `idx_course_applications_email` (`email`),
  ADD KEY `idx_course_applications_number` (`application_number`),
  ADD KEY `idx_course_applications_user_course` (`user_id`,`course_name`),
  ADD KEY `idx_course_applications_status_date` (`status`,`application_date`),
  ADD KEY `idx_course_applications_university_program` (`university`,`program`),
  ADD KEY `reviewed_by` (`reviewed_by`);

--
-- Indexes for table `course_registrations`
--
ALTER TABLE `course_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_departments_university` (`university_id`);

--
-- Indexes for table `icbt_courses`
--
ALTER TABLE `icbt_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_courses_program` (`program_id`),
  ADD KEY `idx_courses_campus` (`campus`),
  ADD KEY `idx_courses_status` (`status`),
  ADD KEY `idx_courses_name` (`course_name`);

--
-- Indexes for table `icbt_faculties`
--
ALTER TABLE `icbt_faculties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_faculty_category` (`faculty_name`,`category`,`campus`),
  ADD KEY `idx_faculties_category` (`category`),
  ADD KEY `idx_faculties_campus` (`campus`),
  ADD KEY `idx_faculties_status` (`status`);

--
-- Indexes for table `icbt_programs`
--
ALTER TABLE `icbt_programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `program_code` (`program_code`),
  ADD KEY `idx_programs_code` (`program_code`),
  ADD KEY `idx_programs_campus` (`campus`),
  ADD KEY `idx_programs_status` (`status`),
  ADD KEY `fk_programs_faculty` (`faculty_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_university` (`university_id`),
  ADD KEY `idx_status` (`response_status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `moratuwa_courses`
--
ALTER TABLE `moratuwa_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_program` (`program_id`),
  ADD KEY `idx_campus` (`campus`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `moratuwa_programs`
--
ALTER TABLE `moratuwa_programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `program_code` (`program_code`),
  ADD KEY `idx_level` (`level`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_campus` (`campus`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `nibm_courses`
--
ALTER TABLE `nibm_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_program` (`program_id`),
  ADD KEY `idx_campus` (`campus`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `nibm_programs`
--
ALTER TABLE `nibm_programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `program_code` (`program_code`),
  ADD KEY `idx_level` (`level`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_campus` (`campus`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`,`user_type`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `peradeniya_courses`
--
ALTER TABLE `peradeniya_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_peradeniya_courses_program_id` (`program_id`),
  ADD KEY `idx_peradeniya_courses_study_level` (`study_level`);

--
-- Indexes for table `peradeniya_programs`
--
ALTER TABLE `peradeniya_programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `program_code` (`program_code`),
  ADD KEY `idx_peradeniya_programs_level` (`level`),
  ADD KEY `idx_peradeniya_programs_category` (`category`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_programs_department` (`department_id`),
  ADD KEY `idx_programs_study_level` (`study_level`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_students_email` (`email`),
  ADD KEY `idx_students_phone` (`phone`),
  ADD KEY `idx_students_status` (`account_status`);

--
-- Indexes for table `study_levels`
--
ALTER TABLE `study_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_universities_name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_actions_log`
--
ALTER TABLE `admin_actions_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `application_status_log`
--
ALTER TABLE `application_status_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_applications`
--
ALTER TABLE `course_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `course_registrations`
--
ALTER TABLE `course_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `icbt_courses`
--
ALTER TABLE `icbt_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `icbt_faculties`
--
ALTER TABLE `icbt_faculties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `icbt_programs`
--
ALTER TABLE `icbt_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `moratuwa_courses`
--
ALTER TABLE `moratuwa_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `moratuwa_programs`
--
ALTER TABLE `moratuwa_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `nibm_courses`
--
ALTER TABLE `nibm_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `nibm_programs`
--
ALTER TABLE `nibm_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `peradeniya_courses`
--
ALTER TABLE `peradeniya_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `peradeniya_programs`
--
ALTER TABLE `peradeniya_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `study_levels`
--
ALTER TABLE `study_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_actions_log`
--
ALTER TABLE `admin_actions_log`
  ADD CONSTRAINT `admin_actions_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD CONSTRAINT `admin_settings_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_status_log`
--
ALTER TABLE `application_status_log`
  ADD CONSTRAINT `application_status_log_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `course_applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `application_status_log_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_applications`
--
ALTER TABLE `course_applications`
  ADD CONSTRAINT `course_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_applications_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_registrations`
--
ALTER TABLE `course_registrations`
  ADD CONSTRAINT `course_registrations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_registrations_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `icbt_courses`
--
ALTER TABLE `icbt_courses`
  ADD CONSTRAINT `icbt_courses_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `icbt_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `icbt_programs`
--
ALTER TABLE `icbt_programs`
  ADD CONSTRAINT `fk_programs_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `icbt_faculties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `moratuwa_courses`
--
ALTER TABLE `moratuwa_courses`
  ADD CONSTRAINT `moratuwa_courses_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `moratuwa_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nibm_courses`
--
ALTER TABLE `nibm_courses`
  ADD CONSTRAINT `nibm_courses_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `nibm_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peradeniya_courses`
--
ALTER TABLE `peradeniya_courses`
  ADD CONSTRAINT `peradeniya_courses_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `peradeniya_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
