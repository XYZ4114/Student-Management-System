-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 12:05 PM
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
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `enroll_no` varchar(20) NOT NULL,
  `pre_ct1_attendance` tinyint(4) DEFAULT NULL CHECK (`pre_ct1_attendance` between 0 and 100),
  `pre_mid_attendance` tinyint(4) DEFAULT NULL CHECK (`pre_mid_attendance` between 0 and 100),
  `pre_ct2_attendance` tinyint(4) DEFAULT NULL CHECK (`pre_ct2_attendance` between 0 and 100),
  `pre_final_attendance` tinyint(4) DEFAULT NULL CHECK (`pre_final_attendance` between 0 and 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`enroll_no`, `pre_ct1_attendance`, `pre_mid_attendance`, `pre_ct2_attendance`, `pre_final_attendance`) VALUES
('2023STD001', 88, 75, 82, 90),
('2023STD002', 61, 55, 68, 72),
('2023STD003', 92, 89, 95, 94),
('2023STD004', 78, 82, 80, 85),
('2023STD005', 55, 48, 59, 62),
('2023STD006', 67, 72, 74, 70),
('2023STD007', 92, 88, 95, 91),
('2023STD008', 89, 94, 97, 96),
('2023STD009', 60, 52, 65, 68),
('2023STD010', 85, 87, 84, 89),
('2023STD011', 70, 66, 69, 73),
('2023STD012', 58, 62, 60, 65),
('2023STD013', 95, 96, 98, 97),
('2023STD014', 63, 61, 66, 69),
('2023STD015', 86, 89, 88, 91),
('2023STD017', 74, 70, 72, 75),
('2023STD018', 97, 98, 99, 96);

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `enroll_no` varchar(20) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `class_test_1` int(11) DEFAULT NULL CHECK (`class_test_1` between 0 and 25),
  `midyear` int(11) DEFAULT NULL CHECK (`midyear` between 0 and 100),
  `class_test_2` int(11) DEFAULT NULL CHECK (`class_test_2` between 0 and 25),
  `final` int(11) DEFAULT NULL CHECK (`final` between 0 and 100),
  `total_marks` int(11) GENERATED ALWAYS AS (`class_test_1` + `midyear` + `class_test_2` + `final`) STORED,
  `out_of_marks` int(11) DEFAULT 250,
  `result` enum('Pass','Fail') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`enroll_no`, `subject_name`, `class_test_1`, `midyear`, `class_test_2`, `final`, `out_of_marks`, `result`) VALUES
('2023STD001', 'Computer', 23, 88, 24, 90, 250, 'Pass'),
('2023STD001', 'English', 22, 65, 23, 70, 250, 'Pass'),
('2023STD001', 'Hindi', 19, 74, 21, 78, 250, 'Pass'),
('2023STD001', 'Mathematics', 20, 75, 22, 80, 250, 'Pass'),
('2023STD001', 'Science', 18, 70, 20, 75, 250, 'Pass'),
('2023STD001', 'SST', 20, 70, 22, 76, 250, 'Pass'),
('2023STD002', 'Computer', 20, 60, 15, 70, 250, 'Pass'),
('2023STD002', 'English', 20, 40, 22, 60, 250, 'Pass'),
('2023STD002', 'Hindi', 21, 43, 12, 43, 250, 'Pass'),
('2023STD002', 'Mathematics', 18, 30, 23, 43, 250, 'Pass'),
('2023STD002', 'Science', 11, 77, 21, 60, 250, 'Pass'),
('2023STD002', 'SST', 15, 38, 11, 37, 250, 'Pass'),
('2023STD003', 'Computer', 21, 75, 20, 80, 250, 'Pass'),
('2023STD003', 'English', 20, 68, 21, 72, 250, 'Pass'),
('2023STD003', 'Hindi', 10, 29, 12, 40, 250, 'Fail'),
('2023STD003', 'Mathematics', 8, 30, 10, 40, 250, 'Fail'),
('2023STD003', 'Science', 14, 50, 12, 55, 250, 'Pass'),
('2023STD003', 'SST', 18, 65, 19, 70, 250, 'Pass'),
('2023STD004', 'Computer', 20, 65, 21, 75, 250, 'Pass'),
('2023STD004', 'English', 14, 60, 13, 68, 250, 'Pass'),
('2023STD004', 'Hindi', 11, 70, 16, 72, 250, 'Pass'),
('2023STD004', 'Mathematics', 19, 85, 18, 90, 250, 'Pass'),
('2023STD004', 'Science', 9, 55, 10, 60, 250, 'Fail'),
('2023STD004', 'SST', 13, 62, 11, 66, 250, 'Pass'),
('2023STD005', 'Computer', 8, 45, 12, 40, 250, 'Fail'),
('2023STD005', 'English', 10, 35, 15, 42, 250, 'Pass'),
('2023STD005', 'Hindi', 11, 28, 14, 30, 250, 'Fail'),
('2023STD005', 'Mathematics', 14, 50, 13, 55, 250, 'Pass'),
('2023STD005', 'Science', 13, 31, 10, 29, 250, 'Fail'),
('2023STD005', 'SST', 15, 40, 12, 35, 250, 'Pass'),
('2023STD006', 'Computer', 12, 38, 10, 34, 250, 'Pass'),
('2023STD006', 'English', 9, 60, 8, 50, 250, 'Fail'),
('2023STD006', 'Hindi', 10, 45, 9, 48, 250, 'Fail'),
('2023STD006', 'Mathematics', 17, 72, 18, 75, 250, 'Pass'),
('2023STD006', 'Science', 13, 68, 14, 70, 250, 'Pass'),
('2023STD006', 'SST', 14, 65, 15, 62, 250, 'Pass'),
('2023STD007', 'Computer', 15, 48, 13, 52, 250, 'Pass'),
('2023STD007', 'English', 16, 51, 14, 54, 250, 'Pass'),
('2023STD007', 'Hindi', 10, 40, 9, 38, 250, 'Fail'),
('2023STD007', 'Mathematics', 18, 78, 17, 76, 250, 'Pass'),
('2023STD007', 'Science', 19, 80, 18, 81, 250, 'Pass'),
('2023STD007', 'SST', 15, 64, 16, 67, 250, 'Pass'),
('2023STD008', 'Computer', 22, 85, 23, 89, 250, 'Pass'),
('2023STD008', 'English', 21, 75, 22, 78, 250, 'Pass'),
('2023STD008', 'Hindi', 20, 70, 21, 72, 250, 'Pass'),
('2023STD008', 'Mathematics', 23, 92, 22, 95, 250, 'Pass'),
('2023STD008', 'Science', 20, 82, 21, 84, 250, 'Pass'),
('2023STD008', 'SST', 18, 80, 19, 79, 250, 'Pass'),
('2023STD009', 'Computer', 13, 52, 11, 50, 250, 'Pass'),
('2023STD009', 'English', 12, 47, 10, 45, 250, 'Pass'),
('2023STD009', 'Hindi', 11, 30, 13, 33, 250, 'Pass'),
('2023STD009', 'Mathematics', 14, 28, 12, 29, 250, 'Fail'),
('2023STD009', 'Science', 9, 36, 10, 38, 250, 'Fail'),
('2023STD009', 'SST', 10, 33, 11, 35, 250, 'Pass'),
('2023STD010', 'Computer', 20, 60, 21, 62, 250, 'Pass'),
('2023STD010', 'English', 19, 55, 20, 58, 250, 'Pass'),
('2023STD010', 'Hindi', 18, 52, 19, 54, 250, 'Pass'),
('2023STD010', 'Mathematics', 22, 68, 23, 70, 250, 'Pass'),
('2023STD010', 'Science', 21, 72, 22, 74, 250, 'Pass'),
('2023STD010', 'SST', 19, 66, 20, 68, 250, 'Pass'),
('2023STD011', 'Computer', 11, 44, 13, 45, 250, 'Pass'),
('2023STD011', 'English', 10, 42, 12, 41, 250, 'Pass'),
('2023STD011', 'Hindi', 9, 39, 8, 37, 250, 'Fail'),
('2023STD011', 'Mathematics', 14, 60, 15, 62, 250, 'Pass'),
('2023STD011', 'Science', 13, 59, 14, 60, 250, 'Pass'),
('2023STD011', 'SST', 12, 55, 13, 56, 250, 'Pass'),
('2023STD012', 'Computer', 10, 31, 11, 32, 250, 'Pass'),
('2023STD012', 'English', 8, 29, 10, 28, 250, 'Fail'),
('2023STD012', 'Hindi', 12, 34, 13, 35, 250, 'Pass'),
('2023STD012', 'Mathematics', 14, 36, 15, 38, 250, 'Pass'),
('2023STD012', 'Science', 10, 33, 9, 30, 250, 'Fail'),
('2023STD012', 'SST', 13, 32, 14, 34, 250, 'Pass'),
('2023STD013', 'Computer', 23, 90, 24, 93, 250, 'Pass'),
('2023STD013', 'English', 21, 85, 22, 88, 250, 'Pass'),
('2023STD013', 'Hindi', 20, 82, 19, 84, 250, 'Pass'),
('2023STD013', 'Mathematics', 22, 89, 21, 90, 250, 'Pass'),
('2023STD013', 'Science', 24, 92, 23, 95, 250, 'Pass'),
('2023STD013', 'SST', 20, 87, 21, 88, 250, 'Pass'),
('2023STD014', 'Computer', 14, 66, 13, 68, 250, 'Pass'),
('2023STD014', 'English', 13, 64, 12, 62, 250, 'Pass'),
('2023STD014', 'Hindi', 11, 61, 10, 59, 250, 'Pass'),
('2023STD014', 'Mathematics', 9, 28, 10, 29, 250, 'Fail'),
('2023STD014', 'Science', 8, 30, 9, 27, 250, 'Fail'),
('2023STD014', 'SST', 10, 33, 11, 32, 250, 'Pass'),
('2023STD015', 'Computer', 20, 72, 21, 75, 250, 'Pass'),
('2023STD015', 'English', 19, 65, 20, 68, 250, 'Pass'),
('2023STD015', 'Hindi', 18, 62, 19, 64, 250, 'Pass'),
('2023STD015', 'Mathematics', 21, 80, 22, 83, 250, 'Pass'),
('2023STD015', 'Science', 23, 78, 24, 81, 250, 'Pass'),
('2023STD015', 'SST', 22, 76, 21, 77, 250, 'Pass'),
('2023STD017', 'Computer', 9, 32, 10, 33, 250, 'Fail'),
('2023STD017', 'English', 11, 35, 12, 36, 250, 'Pass'),
('2023STD017', 'Hindi', 10, 28, 9, 30, 250, 'Fail'),
('2023STD017', 'Mathematics', 13, 39, 14, 40, 250, 'Pass'),
('2023STD017', 'Science', 15, 42, 16, 44, 250, 'Pass'),
('2023STD017', 'SST', 12, 34, 13, 35, 250, 'Pass'),
('2023STD018', 'Computer', 22, 86, 23, 88, 250, 'Pass'),
('2023STD018', 'English', 20, 78, 21, 80, 250, 'Pass'),
('2023STD018', 'Hindi', 18, 70, 19, 72, 250, 'Pass'),
('2023STD018', 'Mathematics', 21, 88, 22, 90, 250, 'Pass'),
('2023STD018', 'Science', 24, 92, 25, 94, 250, 'Pass'),
('2023STD018', 'SST', 23, 89, 22, 91, 250, 'Pass');

-- --------------------------------------------------------

--
-- Table structure for table `profile_update_requests`
--

CREATE TABLE `profile_update_requests` (
  `id` int(11) NOT NULL,
  `enroll_no` varchar(20) DEFAULT NULL,
  `field_name` varchar(50) DEFAULT NULL,
  `current_value` text DEFAULT NULL,
  `requested_value` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_update_requests`
--

INSERT INTO `profile_update_requests` (`id`, `enroll_no`, `field_name`, `current_value`, `requested_value`, `status`, `request_date`) VALUES
(1, '2023STD001', 'name', 'Riya Mehta', 'Ria', 'Rejected', '2025-07-17 03:49:13'),
(2, '2023STD005', 'name', 'Meera Desai', 'Mira Desai', 'Approved', '2025-07-17 11:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `enroll_no` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `class` varchar(20) NOT NULL,
  `profile_image` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  `admission_date` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_phone` varchar(15) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`enroll_no`, `name`, `email`, `phone`, `class`, `profile_image`, `dob`, `admission_date`, `gender`, `address`, `blood_group`, `father_name`, `father_phone`, `mother_name`, `mother_phone`) VALUES
('2023STD001', 'Riya Mehta', 'riya@student.com', '9876543210', '10-A', 'riya.jpg', '2008-05-14', '2023-06-10', 'Female', '22, Green Park, Ahmedabad', 'B+', 'Rajesh Mehta', '9823123456', 'Kiran Mehta', '9823123460'),
('2023STD002', 'Aman Patel', 'aman@student.com', '9845612345', '10-A', 'aman.jpeg', '2007-11-22', '2023-06-12', 'Male', '16, Satellite Road, Ahmedabad', 'O+', 'Mahesh Patel', '9811123456', 'Nina Patel', '9811123460'),
('2023STD003', 'Neha Shah', 'neha@student.com', '9832123456', '10-A', 'neha.jpg', '2008-02-09', '2023-06-15', 'Female', '50, Maninagar, Ahmedabad', 'A-', 'Dinesh Shah', '9899988776', 'Pooja Shah', '9899988777'),
('2023STD004', 'Krish Iyer', 'krish@student.com', '9812345670', '10-A', '', '2008-06-02', '2023-06-18', 'Male', '12, Bodakdev, Ahmedabad', 'B+', 'Ramesh Iyer', '9811111111', 'Latha Iyer', '9811111112'),
('2023STD005', 'Mira Desai', 'meera@student.com', '9823456781', '10-A', '', '2007-12-11', '2023-06-20', 'Female', '34, Gota, Ahmedabad', 'O-', 'Prakash Desai', '9822222222', 'Rupal Desai', '9822222223'),
('2023STD006', 'Yash Thakkar', 'yash@student.com', '9845678912', '10-A', '', '2008-03-30', '2023-06-22', 'Male', '18, Vastrapur, Ahmedabad', 'AB+', 'Nilesh Thakkar', '9833333333', 'Pooja Thakkar', '9833333334'),
('2023STD007', 'Ananya Shah', 'ananya@student.com', '9867890123', '10-A', '', '2008-01-15', '2023-06-23', 'Female', '55, Navrangpura, Ahmedabad', 'A+', 'Amit Shah', '9844444444', 'Sneha Shah', '9844444445'),
('2023STD008', 'Dhruv Mehta', 'dhruv@student.com', '9856789012', '10-A', '', '2007-10-10', '2023-06-25', 'Male', '22, Ambawadi, Ahmedabad', 'B-', 'Harsh Mehta', '9855555555', 'Rina Mehta', '9855555556'),
('2023STD009', 'Isha Patel', 'isha@student.com', '9823891234', '10-A', '', '2008-02-19', '2023-06-26', 'Female', '75, Shahibaug, Ahmedabad', 'O+', 'Manoj Patel', '9866666666', 'Anita Patel', '9866666667'),
('2023STD010', 'Rudra Bhatt', 'rudra@student.com', '9812347890', '10-A', '', '2007-11-07', '2023-06-27', 'Male', '29, Usmanpura, Ahmedabad', 'AB-', 'Ravi Bhatt', '9877777777', 'Mina Bhatt', '9877777778'),
('2023STD011', 'Simran Gandhi', 'simran@student.com', '9876541200', '10-A', '', '2008-04-22', '2023-06-28', 'Female', '19, Thaltej, Ahmedabad', 'B+', 'Jayesh Gandhi', '9888888888', 'Nina Gandhi', '9888888889'),
('2023STD012', 'Arjun Solanki', 'arjun@student.com', '9845673210', '10-A', '', '2008-06-18', '2023-06-29', 'Male', '42, Paldi, Ahmedabad', 'A-', 'Raj Solanki', '9899999999', 'Kajal Solanki', '9899999900'),
('2023STD013', 'Priya Joshi', 'priya@student.com', '9823456789', '10-A', '', '2008-05-30', '2023-07-01', 'Female', '60, Bopal, Ahmedabad', 'O-', 'Suresh Joshi', '9800000001', 'Neeta Joshi', '9800000002'),
('2023STD014', 'Dev Kapadia', 'dev@student.com', '9832109876', '10-A', '', '2007-09-05', '2023-07-02', 'Male', '33, Sabarmati, Ahmedabad', 'B+', 'Jignesh Kapadia', '9800000003', 'Komal Kapadia', '9800000004'),
('2023STD015', 'Nidhi Rana', 'nidhi@student.com', '9876543333', '10-A', '', '2008-01-02', '2023-07-03', 'Female', '88, Naranpura, Ahmedabad', 'AB+', 'Tejas Rana', '9800000005', 'Varsha Rana', '9800000006'),
('2023STD017', 'Tanya Deshmukh', 'tanya@student.com', '9841234567', '10-A', '', '2008-03-14', '2023-07-05', 'Female', '7, Ghodasar, Ahmedabad', 'O+', 'Anil Deshmukh', '9800000009', 'Kavita Deshmukh', '9800000010'),
('2023STD018', 'Harshil Joshi', 'harshil@student.com', '9832456781', '10-A', '', '2007-08-21', '2023-07-06', 'Male', '58, Chandkheda, Ahmedabad', 'B-', 'Chirag Joshi', '9800000011', 'Rashmi Joshi', '9800000012');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `enroll_no` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`enroll_no`, `name`, `email`, `password`, `role`, `created_at`) VALUES
('2023ADM001', 'Admin User', 'admin@sms.com', '$2y$10$In8KgetC7jMbDdsIm1.ohO/DOj8hTG1uZ1bPFrDp9QL36Tt.4MlcC', 'admin', '2025-07-14 05:02:40'),
('2023STD001', 'Riya Mehta', 'riya@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-14 05:02:40'),
('2023STD002', 'Aman Patel', 'aman@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-14 05:02:40'),
('2023STD003', 'Neha Shah', 'neha@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-14 05:02:40'),
('2023STD004', 'Krish Iyer', 'krish@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD005', 'Meera Desai', 'meera@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD006', 'Yash Thakkar', 'yash@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD007', 'Ananya Shah', 'ananya@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD008', 'Dhruv Mehta', 'dhruv@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD009', 'Isha Patel', 'isha@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD010', 'Rudra Bhatt', 'rudra@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD011', 'Simran Gandhi', 'simran@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD012', 'Arjun Solanki', 'arjun@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD013', 'Priya Joshi', 'priya@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD014', 'Dev Kapadia', 'dev@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD015', 'Nidhi Rana', 'nidhi@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD017', 'Tanya Deshmukh', 'tanya@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10'),
('2023STD018', 'Harshil Joshi', 'harshil@student.com', '$2y$10$l5heTwZBoBiZBFtpgxtxXuR62y5uySbaWt0utbo5sfmbIHsE3GfZy', 'student', '2025-07-15 08:57:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`enroll_no`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`enroll_no`,`subject_name`);

--
-- Indexes for table `profile_update_requests`
--
ALTER TABLE `profile_update_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`enroll_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`enroll_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `profile_update_requests`
--
ALTER TABLE `profile_update_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`enroll_no`) REFERENCES `users` (`enroll_no`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attendance_enroll` FOREIGN KEY (`enroll_no`) REFERENCES `students` (`enroll_no`) ON DELETE CASCADE;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `fk_marks_enroll` FOREIGN KEY (`enroll_no`) REFERENCES `students` (`enroll_no`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`enroll_no`) REFERENCES `users` (`enroll_no`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
