-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 10, 2025 at 02:17 PM
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
-- Database: `maetaeng_tourism 2`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'วัด'),
(2, 'ธรรมชาติ'),
(3, 'กิจกรรม'),
(4, 'แหล่งท่องเที่ยว');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `location_id`, `user_id`, `comment`, `created_at`) VALUES
(5, 2, 1, 'สวยมาก', '2025-09-09 19:07:13'),
(6, 1, 1, 'สวย', '2025-09-09 19:33:04'),
(7, 1, 2, 'ไปมาแล้ว', '2025-09-09 20:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category_id` int DEFAULT NULL,
  `video_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `description`, `category_id`, `video_url`, `map_url`, `created_at`) VALUES
(1, 'อุทยานแห่งชาติน้ำตกบัวตอง-น้ำพุเจ็ดสี ', 'ช่วงนี้อากาศกำลังดี จึงขอพาไปชมธรรมชาติที่สวยงาม น้ำตกบัวตอง เป็นน้ำตกที่สวยงาม มีความสูงประมาณ 100 เมตร มีด้วยกัน 2 ชั้น  ลักษณะน้ำตกก็จะมีแคลเซียมคาร์บอเนต ไหลผ่านมาเป็นเวลานานๆ จึงทำให้น้ำตกถูกเคลือบเป็นธารหินปูนแข็ง  และสามารถเดินหรือปืนป่ายไปบนตัวน้ำตกได้อย่างสนุกสนานโดยไม่ลื่น และหินปูนที่เคลือบอยู่นี้ยังทำให้น้ำตกดูสวยงามน่าชมยิ่งนัก\n\nน้ำพุเจ็ดสี เป็นบ่อน้ำพุเย็น มีน้ำไหลพุ่งออกจากใต้ดินตลอดปี น้ำใส เป็นประกายสีรุ้งเมื่อกระทบแสงอาทิตย์เป็นแหล่งกำเนิดต้นน้ำที่ทำให้เกิดน้ำตกบัวตอง\n\nบริเวณน้ำตกบัวตองและน้ำพุเจ็ดสี ลักษณะทั่วไป มีสภาพป่าสมบูรณ์และร่มรื่น มีต้นไม้ขนาดใหญ่ขึ้นอยู่หนาแน่นเป็นป่าดิบชื้นและป่าเบญจพรรณ มีจุดชมวิวที่สวยงาม เหมาะสำหรับครอบครัวที่ต้องการพักผ่อนแบบธรรมชาติสบายๆ มีพื้นที่สำหรับปิกนิกอีกด้วย  ไม่มีค่าเข้าชม เปิดทุกวันเวลา  8.00-17.00 น.\n\nการเดินทางไปวนอุทยานน้ำตกบัวตองและน้ำพุเจ็ด ตามถนนสายเชียงใหม่ - อำเภอพร้าว ตรงหลักกิโลเมตรที่ 48 - 49 ก็จะมีทางแยกขวามือเข้าไปวนอุทยาน อีกประมาณ 2.6 กิโลเมตร ก็จะมีทางเลี้ยวขวาเข้าวนอุทยานน้ำตกบัวตองและน้ำพุเจ็ดสี', 2, 'https://www.youtube.com/watch?v=_pPcKcOR9AU', '19.07015776511344, 99.08103475993116', '2025-09-10 14:04:01'),
(2, 'วัดเด่นสะหลีศรีเมืองแกน (วัดบ้านเด่น)​', 'วัดเด่นสะหลีศรีเมืองแกน หรือที่หลายคนเรียกว่า วัดบ้านเด่น เป็นวัดที่มีชื่อเสียงของอำเภอแม่แตง ตั้งอยู่ที่ตำบลอินทขิล อำเภอแม่แตง จังหวัดเชียงใหม่ ซึ่งอยู่ไม่ไกลจากเขื่อนแม่งัดมากนัก จึงทำให้เราได้มีโอกาสแวะไปทำบุญและชมความสวยงามของวัดแห่งนี้\r\n\r\nวัดบ้านเด่น จากที่ทราบมา เมื่อก่อนวัดแห่งนี้เป็นวัดเล็กๆ ที่ยังไม่มีอะไร แต่เมื่อครูบาเจ้าเทือง นาถลีโล ได้มาจำพรรษาอยู่ที่วัดบ้านเด่นแห่งนี้ ทำให้มีผู้คนศรัทธาเลื่อมใสในตัวท่านมากมาย จึงได้หลั่งไหลกันมาทำบุญ จนในที่สุดก็ได้กลายมาเป็น วัดเด่นสะหลีศรีเมืองแกน ที่มีความงดงามอลังการอย่างที่เห็นในทุกวันนี้ค่ะ', 1, 'https://www.youtube.com/watch?v=kJ3DULpm-54', '19.162103806802527, 98.97825793251572', '2025-09-09 17:47:42'),
(3, 'เขื่อนแม่งัดสมบรูณ์ชล', 'เขื่อนแม่งัด หรือ เขื่อนแม่งัดสมบูรณ์ชล เป็นอีกสถานที่ท่องเที่ยวของอำเภอแม่แตง จังหวัดเชียงใหม่ ตั้งอยู่ในเขตอุทยานศรีลานนา \r\n            ที่นี่เป็นเขื่อนผลิตไฟฟ้า ตัวเขื่อนสร้างปิดกั้นลำน้ำแม่งัด เริ่มก่อสร้างเมื่อปี พ.ศ. 2520 โดยกรมชลประทาน ได้สร้างเสร็จเมื่อปี พ.ศ. 2528 ซึ่งสามารถผลิตพลังงานไฟฟ้าได้ปีละ 24.5 ล้านกิโลวัตต์ชั่วโมงค่ะ\r\n            เขื่อนแม่งัด นอกจากจะผลิตกระแสไฟฟ้าแล้ว ยังมีประโยชน์อีกหลายๆ ด้านทั้งทางตรงและทางอ้อม ไม่ว่าจะเป็น ด้านการเกษตร ด้านชลประทาน ด้านการคมนาคมและการท่องเที่ยว อีกทั้งยังช่วยในเรื่องของการบรรเทาอุทกภัยอีกด้วย\r\n            ด้วยวิวทิวทัศน์ของเขื่อนที่ล้อมรอบไปด้วยภูเขาเขียวขจี จึงทำให้ที่นี่กลายเป็นแหล่งท่องเที่ยว ที่ใครมาเยือนอำเภอแม่แตงต้องห้ามพลาดการมาเที่ยวชม เขื่อนแม่งัด ซึ่งค่าเข้าชมภายในอุทยาน ผู้ใหญ่ คนละ 20 บาท เด็ก 10 บาท \r\n            ค่าบริการจอดรถยนต์ คันละ 30 บาท และจักรยานยนต์ คันละ 20 บาทค่ะ และสำหรับใครที่อยากมาพักผ่อน เราทราบมาว่าที่นี่มีรีสอร์ทเป็นเรือนแพคอยให้บริการกับนักท่องเที่ยวด้วย ซึ่งจะมีท่าเรือไว้ค่อยรับ-ส่ง ที่ตรงจุดท่าจอดเรือของอุทยานฯ\r\n            การมาเที่ยวจังหวัดเชียงใหม่ในครั้งนี้ ทำให้เราได้เห็นความสวยงามของธรรมชาติในหลายๆ มุมกับสถานที่ ที่เราไป ยิ่งวิวของเขื่อนแม่งัดที่ได้เห็นกับตาตัวเองด้วยแล้ว มันบรรยายความรู้สึกออกมายากมากๆ เลยล่ะค่ะ รู้แต่ว่า ความรู้สึกของตัวเองมันยกให้กับธรรมชาติตรงหน้าไปหมดแล้ว \r\n            ฝากติดตามบทความต่อๆ ไปของเราด้วยนะคะ', 2, 'https://www.youtube.com/watch?v=qSKK5UUUDqY', 'https://maps.app.goo.gl/mfWQLZBVLmzfxtmQ6', '2025-09-09 20:14:33');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int NOT NULL,
  `location_id` int NOT NULL,
  `media_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `location_id` int NOT NULL,
  `total_views` int DEFAULT '0',
  `average_rating` decimal(2,1) DEFAULT '0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`location_id`, `total_views`, `average_rating`) VALUES
(1, 29, 0.0),
(2, 38, 0.0),
(3, 7, 0.0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_admin`, `created_at`) VALUES
(1, 'admin', '$2y$10$Ff5kOHERGPWc5DweXJTFpeAp.BkdRIczZOfXBte.5R5JcH6k41wW6', 'admin@example.com', 1, '2025-09-09 12:51:07'),
(2, 'Som007', '$2y$10$3w.0vVTFYv2AEnCoxQnZded4lsUBht0c71FBDvx3ZH7yZ5dywWJTO', 'ftgu2965@gmail.com', 0, '2025-09-09 14:28:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `maetaeng_tourism3`.`locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `maetaeng_tourism3`.`locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `maetaeng_tourism3`.`locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `statistics`
--
ALTER TABLE `statistics`
  ADD CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `maetaeng_tourism3`.`locations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
