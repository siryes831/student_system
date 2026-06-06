-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2026 at 09:35 PM
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
-- Database: `studentrecordsv1`
--

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_name` varchar(100) DEFAULT NULL,
  `midterm` decimal(5,2) DEFAULT NULL,
  `final` decimal(5,2) DEFAULT NULL,
  `average` decimal(5,2) DEFAULT NULL,
  `remarks` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `student_id`, `subject_id`, `teacher_name`, `midterm`, `final`, `average`, `remarks`) VALUES
(27, 17, 13, 'Ms Abalos', 2.10, 1.30, 1.70, 'Passed'),
(28, 17, 11, 'Ms Abalos', 5.00, 5.00, 5.00, 'Passed'),
(29, 18, 11, 'Ms Abalos', 2.10, 1.30, 1.70, 'Passed');

-- --------------------------------------------------------

--
-- Table structure for table `grades_archive`
--

CREATE TABLE `grades_archive` (
  `grade_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_name` varchar(100) DEFAULT NULL,
  `midterm` decimal(5,2) DEFAULT NULL,
  `final` decimal(5,2) DEFAULT NULL,
  `average` decimal(5,2) DEFAULT NULL,
  `remarks` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `grades_archive`
--

INSERT INTO `grades_archive` (`grade_id`, `student_id`, `subject_id`, `teacher_name`, `midterm`, `final`, `average`, `remarks`) VALUES
(20, 17, 11, NULL, 0.00, 0.00, 0.00, ''),
(21, 17, 11, NULL, 2.20, 2.10, 2.20, 'Passed'),
(23, 17, 13, NULL, 5.00, 5.00, 5.00, 'Passed'),
(24, 17, 13, NULL, 5.00, 5.00, 5.00, 'Passed'),
(25, 17, 11, NULL, 2.10, 1.30, 1.70, 'Passed'),
(26, 17, 11, 'Ms Abalos', 2.10, 1.30, 1.70, 'Passed');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentID` int(11) NOT NULL,
  `FirstName` text DEFAULT NULL,
  `LastName` text DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Contact` varchar(15) NOT NULL,
  `Course` text DEFAULT NULL,
  `Gender` text DEFAULT NULL,
  `YearLevel` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`StudentID`, `FirstName`, `LastName`, `Age`, `Address`, `Contact`, `Course`, `Gender`, `YearLevel`) VALUES
(17, 'Siryes', 'Mekanishimu', 20, 'Bucal', '0999111222', 'INFOTECH', 'Female', '1'),
(18, 'Cyris', 'Mekanishimu', 1831200005, 'Gesokyo', '1222', 'Buisness', 'Male', '1');

-- --------------------------------------------------------

--
-- Table structure for table `students_archive`
--

CREATE TABLE `students_archive` (
  `StudentID` int(11) NOT NULL,
  `FirstName` text DEFAULT NULL,
  `LastName` text DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Contact` varchar(15) NOT NULL,
  `Course` text DEFAULT NULL,
  `Gender` text DEFAULT NULL,
  `YearLevel` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students_archive`
--

INSERT INTO `students_archive` (`StudentID`, `FirstName`, `LastName`, `Age`, `Address`, `Contact`, `Course`, `Gender`, `YearLevel`) VALUES
(4, 'Josh Rafael', 'Cabebe', 20, 'Bucal', '09999', 'INFOTECH', 'Male', '2'),
(5, 'Dhan', 'Bulda', 20, 'Earth', '09279992222', 'INFOTECH', 'Male', '2'),
(6, 'Ray', 'Caguiao', 20, 'Kawit', '09922212342', 'INFOTECH', 'Female', '2'),
(12, 'Siryes', 'Mekanishimu', 139, 'Home', '0999111222', 'CS', 'male', '1'),
(13, 'Anne', 'Dear', 27, 'Bucal', '1122', 'Buisness', 'Female', '1'),
(14, 'Siryes', 'Mekanishimu', 20, 'Bucal', '09999', 'INFOTECH', 'Male', '2'),
(15, 'Siryes', 'Mekanishimu', 20, 'Bucal', '09999', 'INFOTECH', 'Male', '2'),
(16, 'Siryes', 'Mekanishimu', 20, 'Bucal', '09999', 'INFOTECH', 'Male', '1');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `units` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `units`) VALUES
(11, 'SSS', NULL),
(13, 'Database Systems', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects_archive`
--

CREATE TABLE `subjects_archive` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `units` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subjects_archive`
--

INSERT INTO `subjects_archive` (`subject_id`, `subject_name`, `units`) VALUES
(1, 'Introduction to Programming', 3),
(2, 'Database Systems', 3),
(3, 'Web Development', 3),
(4, 'Gender And Society', NULL),
(5, 'SSS', NULL),
(6, 'STS', NULL),
(7, 'wqwqw', NULL),
(8, 'as', NULL),
(9, 'as', NULL),
(10, 'SSS', NULL),
(12, 'Gender And Society', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`);

--
-- Indexes for table `grades_archive`
--
ALTER TABLE `grades_archive`
  ADD PRIMARY KEY (`grade_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `students_archive`
--
ALTER TABLE `students_archive`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `subjects_archive`
--
ALTER TABLE `subjects_archive`
  ADD PRIMARY KEY (`subject_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `grades_archive`
--
ALTER TABLE `grades_archive`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `students_archive`
--
ALTER TABLE `students_archive`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `subjects_archive`
--
ALTER TABLE `subjects_archive`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
