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
-- Database: `maetaeng_tourism1`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'ธรรมชาติ', 'น้ำตก ภูเขา ป่าไม้', '2025-09-07 07:57:46'),
(2, 'วัฒนธรรม', 'วัด โบราณสถาน และงานประเพณี', '2025-09-07 07:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `place_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `place_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 2, 'สวยมาก ต้องมาอีก', '2025-09-07 07:57:46'),
(2, 3, 2, 'วิวอ่างเก็บน้ำดีมาก', '2025-09-07 07:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `type` enum('comment','rating') COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'comment', 'มีคอมเมนต์ใหม่ใน วัดบ้านเด่น', 0, '2025-09-07 07:57:46'),
(2, 1, 'rating', 'มีเรตติ้งใหม่ใน เขื่อนแม่งัด', 0, '2025-09-07 07:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audio_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` decimal(10,6) DEFAULT NULL,
  `lng` decimal(10,6) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `name`, `description`, `image_url`, `video_url`, `audio_url`, `lat`, `lng`, `category_id`, `created_at`) VALUES
(1, 'วัดเด่นสะหลีศรีเมืองแก่น (วัดบ้านเด่น)', 'วัดสวยโดดเด่นบนเนินเขา', 'https://example.com/wat_banden.jpg', NULL, NULL, 19.197500, 98.875300, 2, '2025-09-07 07:57:46'),
(2, 'อุทยานแห่งชาติน้ำตกบัวตอง-น้ำพุเจ็ดสี', 'แหล่งท่องเที่ยวธรรมชาติ น้ำตก+น้ำพุแร่', 'https://example.com/buatong.jpg', NULL, NULL, 19.054600, 98.793700, 1, '2025-09-07 07:57:46'),
(3, 'เขื่อนแม่งัดสมบูรณ์ชล', 'พักแพ กิจกรรมทางน้ำ', 'https://example.com/mae_ngat.jpg', NULL, NULL, 19.138100, 98.828400, 1, '2025-09-07 07:57:46'),
(4, 'ปางช้างแม่แตง', 'ชมช้างและการแสดง', 'https://example.com/maetang_elephant.jpg', NULL, NULL, 18.999900, 98.859900, 1, '2025-09-07 07:57:46'),
(5, 'แดนเทวดา', 'จุดกางเต็นท์/ชมวิวทะเลหมอก', 'https://example.com/daen_thewada.jpg', NULL, NULL, 19.089300, 98.811000, 1, '2025-09-07 07:57:46'),
(6, 'สวนสนแม่แตง', 'ป่าสน บรรยากาศดี', 'https://example.com/suan_son.jpg', NULL, NULL, 19.217200, 98.824800, 1, '2025-09-07 07:57:46'),
(7, 'น้ำตกหมอกฟ้า', 'น้ำใสเย็น กลางป่า', 'https://example.com/mok_fa.jpg', NULL, NULL, 19.135400, 98.748700, 1, '2025-09-07 07:57:46'),
(8, 'น้ำพุร้อนโป่งเดือด', 'แช่น้ำแร่ธรรมชาติ', 'https://example.com/pong_dueat.jpg', NULL, NULL, 19.335800, 98.613400, 1, '2025-09-07 07:57:46'),
(9, 'หาดปางเป้า', 'จุดพักผ่อนริมน้ำ', 'https://example.com/pang_pao_beach.jpg', NULL, NULL, 19.152400, 98.824200, 1, '2025-09-07 07:57:46'),
(10, 'ปางช้างแม่ตะมาน', 'กิจกรรมกับช้าง ใกล้ชิดธรรมชาติ', 'https://example.com/mae_taman_elephant.jpg', NULL, NULL, 19.292500, 98.821300, 1, '2025-09-07 07:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `place_views`
--

CREATE TABLE `place_views` (
  `id` int NOT NULL,
  `place_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `viewed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `place_views`
--

INSERT INTO `place_views` (`id`, `place_id`, `user_id`, `viewed_at`) VALUES
(1, 1, 2, '2025-09-07 07:57:46'),
(2, 1, 2, '2025-09-07 07:57:46'),
(3, 3, 2, '2025-09-07 07:57:46'),
(4, 7, 2, '2025-09-07 07:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int NOT NULL,
  `place_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `place_id`, `user_id`, `rating`, `created_at`) VALUES
(1, 1, 2, 5, '2025-09-07 07:57:46'),
(2, 3, 2, 4, '2025-09-07 07:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$VDwJoZEaPleoYiF3LiKaeuuMi3S.npN704dtncIurGXOhKH952qFq', 'admin', '2025-09-07 07:57:46'),
(2, 'test', '$2b$10$oyyAVdJp6k8QbT1QF2RtGe0rGUNShUMHbOWcZH/5Li.yYI8a9ka36', 'user', '2025-09-07 07:57:46');

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
  ADD KEY `place_id` (`place_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `place_views`
--
ALTER TABLE `place_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `place_views`
--
ALTER TABLE `place_views`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `places`
--
ALTER TABLE `places`
  ADD CONSTRAINT `places_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `place_views`
--
ALTER TABLE `place_views`
  ADD CONSTRAINT `place_views_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `place_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
