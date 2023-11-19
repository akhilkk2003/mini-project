-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2023 at 01:03 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `driving-school(2)`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointments`
--

CREATE TABLE `tbl_appointments` (
  `appointment_id` int(11) NOT NULL,
  `learner_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `instructor_id` varchar(255) NOT NULL,
  `section` varchar(30) NOT NULL,
  `appointment_time` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `classneed_date` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_appointments`
--

INSERT INTO `tbl_appointments` (`appointment_id`, `learner_id`, `user_email`, `service_id`, `instructor_id`, `section`, `appointment_time`, `status`, `classneed_date`, `created_at`) VALUES
(9, 49, 'akhilkk200313@gmail.com', 1, '22', 'evening', '5:30PM-6:00PM', 'approved', '2023-11-23', '2023-11-18 22:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedbacks`
--

CREATE TABLE `tbl_feedbacks` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_feedbacks`
--

INSERT INTO `tbl_feedbacks` (`feedback_id`, `user_id`, `feedback`, `status`) VALUES
(1, 1, 'sadadsad', 'rejected'),
(7, 1, 'waeqa   fbgfnnbdc', 'approved'),
(8, 1, 'aeedsdxddsdsd', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_instructors`
--

CREATE TABLE `tbl_instructors` (
  `instructor_id` int(11) NOT NULL,
  `instructor_name` varchar(255) NOT NULL,
  `instructor_image` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `instructor_created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_instructors`
--

INSERT INTO `tbl_instructors` (`instructor_id`, `instructor_name`, `instructor_image`, `status`, `instructor_created_at`) VALUES
(22, 'basil', 'img/trainer/6557bf73cd636.jpeg', 'Active', '2023-08-17 19:37:50'),
(26, 'Thomas', 'img/trainer/6557bf7cdd055.jpeg', 'Active', '2023-08-26 21:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_instructor_time`
--

CREATE TABLE `tbl_instructor_time` (
  `instructortime_id` int(11) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `slot_id` int(11) DEFAULT NULL,
  `morning` varchar(20) DEFAULT NULL,
  `evening` varchar(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_instructor_time`
--

INSERT INTO `tbl_instructor_time` (`instructortime_id`, `instructor_id`, `course_id`, `slot_id`, `morning`, `evening`, `status`, `created_at`) VALUES
(18, 22, 1, 1, 'Active', 'Active', 'Active', '2023-11-17 19:29:15'),
(19, 22, 1, 2, 'Active', 'Active', 'Active', '2023-11-17 19:29:15'),
(20, 22, 1, 3, 'Active', 'Active', 'Active', '2023-11-17 19:29:15'),
(21, 22, 1, 4, 'Active', 'Active', 'Active', '2023-11-17 19:29:15'),
(22, 22, 1, 5, 'Active', 'Active', 'Active', '2023-11-17 19:29:15'),
(23, 22, 1, 6, 'Active', 'Active', 'Active', '2023-11-17 19:29:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_learners_details`
--

CREATE TABLE `tbl_learners_details` (
  `user_id` int(11) NOT NULL,
  `learner_id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `birth_proof` varchar(255) DEFAULT NULL,
  `aadhaar_card` varchar(255) DEFAULT NULL,
  `eye_cert` varchar(500) DEFAULT NULL,
  `signature` varchar(255) NOT NULL,
  `package_id` int(11) NOT NULL,
  `application_status` varchar(50) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `date1` text NOT NULL,
  `date2` varchar(255) NOT NULL,
  `date3` varchar(255) NOT NULL,
  `choosed_date` varchar(255) NOT NULL,
  `test_status` varchar(255) NOT NULL,
  `dl_test` varchar(255) NOT NULL,
  `driving_test_status` varchar(255) NOT NULL,
  `learners_test_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_learners_details`
--

INSERT INTO `tbl_learners_details` (`user_id`, `learner_id`, `full_name`, `dob`, `phone_number`, `blood_group`, `photo`, `birth_proof`, `aadhaar_card`, `eye_cert`, `signature`, `package_id`, `application_status`, `payment_status`, `date1`, `date2`, `date3`, `choosed_date`, `test_status`, `dl_test`, `driving_test_status`, `learners_test_status`) VALUES
(11, 49, 'akhil kk', '2023-11-09', '1223333333', '1', 'img/photo/about-1.jpg', 'img/birth/about-1.jpg', 'img/aadhaar/about-1.jpg', 'img/eye_cert/about-1.jpg', 'img/sig/about-1.jpg', 1, 'rejected', 'Paid', '2023-11-23', '2023-11-27', '2023-11-30', 'is processing', 'pending', 'pending', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_l_testdate`
--

CREATE TABLE `tbl_l_testdate` (
  `learner_id` int(11) NOT NULL,
  `date1` varchar(255) NOT NULL,
  `date2` varchar(255) NOT NULL,
  `date3` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_l_testdate`
--

INSERT INTO `tbl_l_testdate` (`learner_id`, `date1`, `date2`, `date3`) VALUES
(0, '2', '1', '4'),
(0, '2', '1', '4'),
(0, '2023-10-17', '2023-10-16', '2023-10-19'),
(0, '2023-10-17', '2023-10-16', '2023-10-19'),
(0, '2023-10-31', '2023-10-29', '2023-10-27'),
(0, '2023-10-27', '2023-10-30', '2023-10-29'),
(0, '2023-10-27', '2023-10-30', '2023-10-29'),
(0, '2023-10-27', '2023-10-30', '2023-10-29'),
(0, '2023-10-27', '2023-10-29', '2023-10-28'),
(0, '2023-10-27', '2023-10-29', '2023-10-28'),
(0, '2023-10-30', '2023-10-31', '2023-12-01'),
(0, '2023-10-30', '2023-10-31', '2023-12-01'),
(0, '2023-10-30', '2023-10-31', '2023-12-01'),
(0, '2023-10-30', '2023-10-31', '2023-12-01'),
(0, '2023-10-30', '2023-10-31', '2023-12-01'),
(0, '2023-10-30', '2023-10-31', '2023-12-01'),
(0, '2023-10-31', '2023-10-31', '2023-11-06'),
(0, '2023-10-31', '2023-10-31', '2023-11-06'),
(0, '2023-10-30', '2023-10-31', '2023-11-02'),
(0, '2023-10-30', '2023-10-31', '2023-11-02'),
(0, '2023-10-30', '2023-10-31', '2023-11-02'),
(0, '2023-10-31', '2023-11-02', '2023-11-03'),
(0, '2023-10-31', '2023-11-03', '2023-11-03'),
(0, '2023-10-31', '2023-11-03', '2023-11-03'),
(0, '2023-11-02', '2023-11-03', '2023-11-06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_package`
--

CREATE TABLE `tbl_package` (
  `package_id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `package_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_package`
--

INSERT INTO `tbl_package` (`package_id`, `package_name`, `package_price`) VALUES
(1, 'MCWG+LMV', 10000),
(2, 'LMV', 7000),
(3, 'MCWG', 3500);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_services`
--

CREATE TABLE `tbl_services` (
  `tbl_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tbl_license_no` varchar(255) DEFAULT NULL,
  `tbl_dob` date DEFAULT NULL,
  `tbl_category` varchar(255) DEFAULT NULL,
  `tbl_new_address` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_services`
--

INSERT INTO `tbl_services` (`tbl_id`, `user_id`, `tbl_license_no`, `tbl_dob`, `tbl_category`, `tbl_new_address`, `payment_status`, `created_at`, `status`) VALUES
(16, 0, '3435', '2023-10-05', 'Change Of Address', 'n/a', '', '2023-11-18', 'completed'),
(18, 0, '2332883', '2023-10-12', 'Change Of Address', 'yma road', '', '2023-11-18', 'completed'),
(19, 0, '64632632546', '2023-10-30', 'Change Of Address', 'abcdefghijk', '', '2023-11-18', 'completed'),
(20, 0, '64632632546', '2023-10-30', 'Change Of Address', 'abcdefghijk', '', '2023-11-18', ''),
(21, 0, '64632632546', '2023-10-30', 'Change Of Address', 'abcdefghijk', '', '2023-11-18', ''),
(22, 0, '64632632546', '2023-10-30', 'Change Of Address', 'abcdefghijk', '', '2023-11-18', ''),
(24, 7, '64632632546', '2023-10-30', 'Change Of Address', 'abcdefghijk', 'Paid', '2023-11-18', ''),
(25, 9, '235643563546546674657846576', '2023-11-24', 'licence renewal', 'N/A', 'paid', '2023-11-18', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_timeslot`
--

CREATE TABLE `tbl_timeslot` (
  `slot_id` int(11) NOT NULL,
  `days` varchar(255) NOT NULL,
  `starting_time` time NOT NULL,
  `ending_time` time NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_timeslot`
--

INSERT INTO `tbl_timeslot` (`slot_id`, `days`, `starting_time`, `ending_time`, `status`, `created_at`) VALUES
(1, 'Monday', '09:00:00', '21:00:00', 'Active', '2023-09-01 14:54:11'),
(2, 'Tuesday', '09:00:00', '21:00:00', 'Active', '2023-09-01 14:55:18'),
(3, 'Wednesday', '09:00:00', '21:00:00', 'Active', '2023-09-01 14:55:48'),
(4, 'Thursday', '09:00:00', '21:00:00', 'Active', '2023-09-01 14:56:16'),
(5, 'Friday', '09:00:00', '21:00:00', 'Active', '2023-09-01 14:56:38'),
(6, 'Saturday', '09:00:00', '21:00:00', 'Active', '2023-09-01 14:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_name`, `user_email`, `password`) VALUES
(2, 'admin', 'admin@gmail.com', 'admin'),
(11, 'akhil', 'akhilkk200313@gmail.com', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_appointments`
--
ALTER TABLE `tbl_appointments`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indexes for table `tbl_feedbacks`
--
ALTER TABLE `tbl_feedbacks`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `tbl_instructors`
--
ALTER TABLE `tbl_instructors`
  ADD PRIMARY KEY (`instructor_id`);

--
-- Indexes for table `tbl_instructor_time`
--
ALTER TABLE `tbl_instructor_time`
  ADD PRIMARY KEY (`instructortime_id`),
  ADD KEY `doctor_id` (`instructor_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Indexes for table `tbl_learners_details`
--
ALTER TABLE `tbl_learners_details`
  ADD PRIMARY KEY (`learner_id`);

--
-- Indexes for table `tbl_package`
--
ALTER TABLE `tbl_package`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `tbl_services`
--
ALTER TABLE `tbl_services`
  ADD PRIMARY KEY (`tbl_id`);

--
-- Indexes for table `tbl_timeslot`
--
ALTER TABLE `tbl_timeslot`
  ADD PRIMARY KEY (`slot_id`),
  ADD UNIQUE KEY `days` (`days`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_appointments`
--
ALTER TABLE `tbl_appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_feedbacks`
--
ALTER TABLE `tbl_feedbacks`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_instructors`
--
ALTER TABLE `tbl_instructors`
  MODIFY `instructor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_instructor_time`
--
ALTER TABLE `tbl_instructor_time`
  MODIFY `instructortime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_learners_details`
--
ALTER TABLE `tbl_learners_details`
  MODIFY `learner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tbl_package`
--
ALTER TABLE `tbl_package`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `tbl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_timeslot`
--
ALTER TABLE `tbl_timeslot`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
