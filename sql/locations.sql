-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 09, 2025 at 06:51 PM
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
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` int DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `description`, `category_id`, `video_url`, `map_url`, `created_at`) VALUES
(1, 'อุทยานแห่งชาติน้ำตกบัวตอง-น้ำพุเจ็ดสี ', 'ช่วงนี้อากาศกำลังดี จึงขอพาไปชมธรรมชาติที่สวยงาม น้ำตกบัวตอง เป็นน้ำตกที่สวยงาม มีความสูงประมาณ 100 เมตร มีด้วยกัน 2 ชั้น  ลักษณะน้ำตกก็จะมีแคลเซียมคาร์บอเนต ไหลผ่านมาเป็นเวลานานๆ จึงทำให้น้ำตกถูกเคลือบเป็นธารหินปูนแข็ง  และสามารถเดินหรือปืนป่ายไปบนตัวน้ำตกได้อย่างสนุกสนานโดยไม่ลื่น และหินปูนที่เคลือบอยู่นี้ยังทำให้น้ำตกดูสวยงามน่าชมยิ่งนัก\n\nน้ำพุเจ็ดสี เป็นบ่อน้ำพุเย็น มีน้ำไหลพุ่งออกจากใต้ดินตลอดปี น้ำใส เป็นประกายสีรุ้งเมื่อกระทบแสงอาทิตย์เป็นแหล่งกำเนิดต้นน้ำที่ทำให้เกิดน้ำตกบัวตอง\n\nบริเวณน้ำตกบัวตองและน้ำพุเจ็ดสี ลักษณะทั่วไป มีสภาพป่าสมบูรณ์และร่มรื่น มีต้นไม้ขนาดใหญ่ขึ้นอยู่หนาแน่นเป็นป่าดิบชื้นและป่าเบญจพรรณ มีจุดชมวิวที่สวยงาม เหมาะสำหรับครอบครัวที่ต้องการพักผ่อนแบบธรรมชาติสบายๆ มีพื้นที่สำหรับปิกนิกอีกด้วย  ไม่มีค่าเข้าชม เปิดทุกวันเวลา  8.00-17.00 น.\n\nการเดินทางไปวนอุทยานน้ำตกบัวตองและน้ำพุเจ็ด ตามถนนสายเชียงใหม่ - อำเภอพร้าว ตรงหลักกิโลเมตรที่ 48 - 49 ก็จะมีทางแยกขวามือเข้าไปวนอุทยาน อีกประมาณ 2.6 กิโลเมตร ก็จะมีทางเลี้ยวขวาเข้าวนอุทยานน้ำตกบัวตองและน้ำพุเจ็ดสี', 2, 'https://www.youtube.com/watch?v=_pPcKcOR9AU', '19.075409583744808, 99.0787527573382', '2025-09-10 14:04:01'),
(2, 'วัดเด่นสะหลีศรีเมืองแกน (วัดบ้านเด่น)​', 'วัดเด่นสะหลีศรีเมืองแกน หรือที่หลายคนเรียกว่า วัดบ้านเด่น เป็นวัดที่มีชื่อเสียงของอำเภอแม่แตง ตั้งอยู่ที่ตำบลอินทขิล อำเภอแม่แตง จังหวัดเชียงใหม่ ซึ่งอยู่ไม่ไกลจากเขื่อนแม่งัดมากนัก จึงทำให้เราได้มีโอกาสแวะไปทำบุญและชมความสวยงามของวัดแห่งนี้\r\n\r\nวัดบ้านเด่น จากที่ทราบมา เมื่อก่อนวัดแห่งนี้เป็นวัดเล็กๆ ที่ยังไม่มีอะไร แต่เมื่อครูบาเจ้าเทือง นาถลีโล ได้มาจำพรรษาอยู่ที่วัดบ้านเด่นแห่งนี้ ทำให้มีผู้คนศรัทธาเลื่อมใสในตัวท่านมากมาย จึงได้หลั่งไหลกันมาทำบุญ จนในที่สุดก็ได้กลายมาเป็น วัดเด่นสะหลีศรีเมืองแกน ที่มีความงดงามอลังการอย่างที่เห็นในทุกวันนี้ค่ะ', 1, 'https://www.youtube.com/watch?v=kJ3DULpm-54', '19.15786238318881, 98.9785486730154', '2025-09-09 17:47:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
