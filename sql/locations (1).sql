-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 10, 2025 at 05:11 PM
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
(1, 'วัดเด่นสะหลีศรีเมืองแก่น', 'วัดที่สร้างขึ้นด้วยสถาปัตยกรรมล้านนาประยุกต์อันงดงามที่ใหญ่ที่สุดในจังหวัดเชียงใหม่ มีเจดีย์ประดิษฐานในทุกๆ มุม และมีความโดดเด่นไม่เหมือนวัดไหนๆ', 1, 'https://www.youtube.com/embed/5D34uW4z2v0', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.609420067645!2d98.9669!3d19.1417!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da37f8f8f8f8f9%3A0x6d8f8f8f8f8f8f8f!2z4Lir4Lit4Lij4Lix4LiB4Liy4LiZ4LmI4Liy4LiV4LiB4Liy4LiV4LmJ4Liy4Lir4LiB4Li', '2025-09-10 16:51:50'),
(2, 'อุทยานแห่งชาติน้ำตกบัวตอง-น้ำพุเจ็ดสี', 'อุทยานแห่งชาติที่ประกอบไปด้วย น้ำตกบัวตอง และน้ำพุเจ็ดสี น้ำตกบัวตองเป็นน้ำตกหินปูนที่มีความพิเศษคือ สามารถปีนป่ายขึ้นไปชมน้ำตกได้ เนื่องจากพื้นผิวของหินปูนไม่มีความลื่น', 2, NULL, 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3778.6179420067645!2d99.0430!3d19.1670!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da35f8f8f8f8f9%3A0x7d8f8f8f8f8f8f8f!2z4Lir4Lit4Lij4Lix4LiB4Liy4LiZ4LmI4Liy4LiV4LiB4Liy4LiV4LmJ4Liy4Lir4LiB4L', '2025-09-10 16:51:50'),
(3, 'แดนเทวดา', 'สถานที่ท่องเที่ยวที่ตกแต่งด้วยสวนและน้ำตกจำลองที่สวยงาม มีมุมถ่ายรูปเพียบ', 4, 'https://www.youtube.com/embed/S2pEa4fV38g', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3778.6179420067645!2d98.9224!3d19.0667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da35f8f8f8f8f9%3A0x2d8f8f8f8f8f8f8f!2z4Lir4Lit4Lij4Lix4LiB4Liy4LiZ4LmI4Liy4LiV4LiB4Liy4LiV4LmJ4Liy4Lir4LiB4L', '2025-09-10 16:51:50');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
