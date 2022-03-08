-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 30, 2019 at 08:29 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logins`
--

DROP TABLE IF EXISTS `admin_logins`;
CREATE TABLE IF NOT EXISTS `admin_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(90) NOT NULL,
  `password` varchar(90) NOT NULL,
  `token` varchar(90) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_logins`
--

INSERT INTO `admin_logins` (`id`, `username`, `password`, `token`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 'loMh4ODY0ODpOejk');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `std_id` int(11) NOT NULL,
  `inv_id` varchar(20) NOT NULL,
  `bill_date` datetime NOT NULL,
  `bill_title` varchar(900) NOT NULL,
  `bill_amount` int(11) NOT NULL,
  `scholarship` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `std_id`, `inv_id`, `bill_date`, `bill_title`, `bill_amount`, `scholarship`) VALUES
(2, 1, 'INV_28yQe10RHI19', '2019-10-28 11:59:38', 'Admission', 2000, 20),
(3, 1, 'INV_28yQe10RHI19', '2019-10-28 11:59:38', 'Development', 100, 20),
(4, 4, 'INV_30ttl10fiz19', '2019-10-30 07:18:09', 'Admission Fee', 2000, 20),
(5, 4, 'INV_30ttl10fiz19', '2019-10-30 07:18:09', 'Sports Fee', 10, 20),
(6, 4, 'INV_30Ugr102l019', '2019-10-30 07:42:58', 'Development Fee', 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(900) NOT NULL,
  `fathers_name` varchar(900) NOT NULL,
  `mothers_name` varchar(900) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `address` text NOT NULL,
  `class_name` varchar(10) NOT NULL,
  `section_name` varchar(10) NOT NULL,
  `roll_no` varchar(10) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `blood_group` varchar(10) NOT NULL,
  `scholarship` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `full_name`, `fathers_name`, `mothers_name`, `gender`, `birth_date`, `address`, `class_name`, `section_name`, `roll_no`, `mobile_number`, `blood_group`, `scholarship`, `image`) VALUES
(1, 'Md Jahidul Hasan Limon', 'Lockman Hossain', 'Momtaz Begum', 'Male', '1996-11-17', 'Uttara, Dhaka', '12', 'N', '2267', '01956758055', 'B+', 0, ''),
(4, 'Rakibul Islam Munsis', 'Tajul Islam Munsi', 'Rakiba Begum', 'Male', '1991-11-17', 'Sector 6, Uttara, dhaka', '1', 'B', '5', '01675234678', 'A+', 20, 'img/student-images/uimg-30102019_074227.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
