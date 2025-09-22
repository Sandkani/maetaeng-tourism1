-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2025 at 11:46 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maetaeng_tourism`
--

-- --------------------------------------------------------

--
-- Table structure for table `location1`
--

CREATE TABLE `location1` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location1`
--

INSERT INTO `location1` (`id`, `name`, `description`, `image_url`, `latitude`, `longitude`, `category_id`) VALUES
(1, 'วัดเด่นสะหลีศรีเมืองแก่น', 'วัดเด่นสะหรีศรีเมืองแกน หรือ วัดบ้านเด่น เป็นวัดราษฎร์สังกัดคณะสงฆ์ฝ่ายมหานิกาย ตั้งอยู่ในตำบลอินทขิล อำเภอแม่แตง จังหวัดเชียงใหม่ วัดมีเนื้อที่ 80 ไร่\r\nวัดเด่นสะหลีศรีเมืองแกน เดิมชื่อ วัดหรีบุญเรือง สร้างเมื่อ พ.ศ. 2437 ตั้งเด่นบนเนินเตี้ย ๆ เห็นได้แต่ไกล ภายใต้เนินนั้นเป็นถ้ำศักดิ์สิทธิ์ที่ชาวบ้านนับถือ จึงเรียกกันว่า \"วัดบ้านเด่น\" ต่อมาเมื่อ พ.ศ. 2437 ครูบาเทือง นาถสีโล เจ้าอาวาสได้ทำการบูรณะวัดขึ้นมาใหม่ ในลักษณะสถาปัตยกรรมไทยล้านนา จากอีกแหล่งข้อมูลระบุว่าชื่อ วัดเด่น แต่เมื่อครูบาเทืองมาอยู่ก็มีต้นโพธิ์ขึ้นมา ทางเหนือเรียกว่า \"เก๊าสะหลี\" ท่านเห็นว่าชื่อเป็นมงคลดี อีกทั้งที่ตั้งวัดอยู่ในเขตเมืองเก่าสมัยโบราณที่ชื่อว่า เมืองแกน จึงเป็นที่มาของชื่อวัด\r\nภายในวัดบ้านเด่นสะหรีศรีเมืองแกน มีเสนาสนะ ได้แก่ อุโบสถ หอไตร หอกลอง วิหารเสาอินทขิล กุฏิไม้สักทองทรงล้านนา พระวิหาร พระสถูปเจดีย์ โดยครูบาเจ้าเทือง นาถสีโล (พระครูไพศาลพัฒนโกวิท) วัดหัวคง ตำบลขัวมุง อำเภอสารภี จังหวัดเชียงใหม่ ได้รับนิมนต์จาก ครูบาไชยา วัดป่าป้อง อำเภอดอยสะเก็ด จังหวัดเชียงใหม่ มาเป็นประธานในการก่อสร้างศาสนสถานและศาสนสมบัติทั้งหมด', 'https://trueid-slsapp-storage-prod.s3.ap-southeast-1.amazonaws.com/partner_files/trueidintrend/329419/7_2572.jpg', 19.15795360, 98.97857013, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `location1`
--
ALTER TABLE `location1`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `location1`
--
ALTER TABLE `location1`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
