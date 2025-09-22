-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 12, 2025 at 07:04 AM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminID` int NOT NULL,
  `adminUsername` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `adminUsername`, `email`, `password`) VALUES
(1, 'kanokporn', 'kxicxd@gmail.com', '$2y$10$X2jhMXPb3faxUlKa9mazBuw6ioTHtnrWbkiDTlsW4pCXa3cYwFLB6');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'วัด'),
(2, 'ธรรมชาติ'),
(3, 'กิจกรรม'),
(4, 'แหล่งท่องเที่ยว');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `commentID` int NOT NULL,
  `location_id` int NOT NULL,
  `userID` int NOT NULL,
  `commentText` text NOT NULL,
  `commentDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentID`, `location_id`, `userID`, `commentText`, `commentDate`) VALUES
(1, 1, 1, 'ดีมาก\r\n', '2025-09-10'),
(5, 1, 1, 'สวย', '2025-09-11'),
(13, 1, 1, 'ดีมาก', '2025-09-11'),
(25, 3, 1, 'ดี', '2025-09-12'),
(26, 2, 1, 'สวยมาก\r\n', '2025-09-12');

-- --------------------------------------------------------

--
-- Table structure for table `location1`
--

CREATE TABLE `location1` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `category_id` int NOT NULL,
  `video_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location1`
--

INSERT INTO `location1` (`id`, `name`, `description`, `category_id`, `video_url`, `latitude`, `longitude`, `image_url`) VALUES
(1, 'อุทยานแห่งชาติน้ำตกบัวตอง-น้ำพุเจ็ดสี', 'ช่วงนี้อากาศกำลังดี จึงขอพาไปชมธรรมชาติที่สวยงาม น้ำตกบัวตอง เป็นน้ำตกที่สวยงาม มีความสูงประมาณ 100 เมตร มีด้วยกัน 2 ชั้น \r\nลักษณะน้ำตกก็จะมีแคลเซียมคาร์บอเนต ไหลผ่านมาเป็นเวลานานๆ จึงทำให้น้ำตกถูกเคลือบเป็นธารหินปูนแข็ง \r\nและสามารถเดินหรือปืนป่ายไปบนตัวน้ำตกได้อย่างสนุกสนานโดยไม่ลื่น และหินปูนที่เคลือบอยู่นี้ยังทำให้น้ำตกดูสวยงามน่าชมยิ่งนัก\r\n\r\nน้ำพุเจ็ดสี เป็นบ่อน้ำพุเย็น มีน้ำไหลพุ่งออกจากใต้ดินตลอดปี น้ำใส เป็นประกายสีรุ้งเมื่อกระทบแสงอาทิตย์เป็นแหล่งกำเนิดต้นน้ำที่ทำให้เกิดน้ำตกบัวตอง\r\n\r\nบริเวณน้ำตกบัวตองและน้ำพุเจ็ดสี ลักษณะทั่วไป มีสภาพป่าสมบูรณ์และร่มรื่น มีต้นไม้ขนาดใหญ่ขึ้นอยู่หนาแน่นเป็นป่าดิบชื้นและป่าเบญจพรรณ มีจุดชมวิวที่สวยงาม เหมาะสำหรับครอบครัวที่ต้องการพักผ่อนแบบธรรมชาติสบายๆ มีพื้นที่สำหรับปิกนิกอีกด้วย \r\nไม่มีค่าเข้าชม เปิดทุกวันเวลา  8.00-17.00 น.\r\n\r\nการเดินทางไปวนอุทยานน้ำตกบัวตองและน้ำพุเจ็ด ตามถนนสายเชียงใหม่ - อำเภอพร้าว ตรงหลักกิโลเมตรที่ 48 - 49 ก็จะมีทางแยกขวามือเข้าไปวนอุทยาน อีกประมาณ 2.6 กิโลเมตร ก็จะมีทางเลี้ยวขวาเข้าวนอุทยานน้ำตกบัวตองและน้ำพุเจ็ดสี', 2, 'https://www.youtube.com/watch?v=_pPcKcOR9AU', 19.07019208, 99.07957792, 'https://trueid-slsapp-storage-prod.s3-ap-southeast-1.amazonaws.com/partner_files/trueidintrend/143794/%E0%B8%9A%E0%B8%B1%E0%B8%A7%E0%B8%95%E0%B8%AD%E0%B8%87%2010-6-62_%E0%B9%91%E0%B9%99%E0%B9%90%E0%B9%96%E0%B9%91%E0%B9%96_0012.jpg'),
(2, 'วัดเด่นสะหลีศรีเมืองแก่น', '“วัดเด่นสะหลีศรีเมืองแกน” หรือ “วัดบ้านเด่น” ต.อินทขิล อ.แม่แตง จ.เชียงใหม่ สร้างเมื่อ พ.ศ.2437 ตั้งเด่นบนเนิน ภายใต้เนินเป็นถ้ำศักดิ์สิทธิ์ที่ชาวบ้านนับถือ ในอดีตเป็นเพียงวัดเล็กๆ ในหมู่บ้านเท่านั้น ต่อมาเมื่อเมื่อ “ครูบาเจ้าเทือง นาถสีโล” มาจำพรรษาที่วัดบ้านเด่น ด้วยความที่ครูบาเจ้าเทือง เป็นพระที่มีปฏิปทาจึงได้รับความเลื่อมใสศรัทธาจากศิษยานุศิษย์ที่มีมากมาย ส่งผลให้ยอดถวายปัจจัยมากมาย และด้วยความศรัทธาในพระพุทธศาสนา ครูบาเจ้าเทืองก็ไม่ต้องการจะเก็บปัจจัยไว้ ประกอบกับมีความตั้งใจที่จะสร้างอนุสรณ์สถานแห่งบุญขึ้นมาเป็นรูปธรรม จึงเป็นที่มาของการบูรณะวัดขึ้นมาใหม่ ในปี พ.ศ.2534 ซึ่งครูบาเจ้าเทืองท่านตั้งใจทำให้วัดเป็นศาสนสถานที่ยึดเหนี่ยวจิตใจที่งดงาม แฝงด้วยคติธรรม เป็นอุบายในการดึงคนเข้าวัดเพื่อการขัดเกลาจิตใจ ให้เป็นสถานที่พักผ่อนทางจิตใจมากกว่าการประกอบพิธีกรรมทางศาสนา ค่อยๆ ซึมซับคำสอนของพระพุทธศาสดาพร้อมไปกับการเที่ยวชมพุทธสถานแห่งนี้\r\n\r\nลักษณะสถาปัตยกรรมภายในวัดเป็นสถาปัตยกรรมแบบไทยล้านนาประยุกต์ ที่ผสมผสานโดยแนวคิดของครูบาเจ้าเทือง คือคิดจะใส่อะไรจะทำอะไรก็ทำ แต่ต้องมีความมั่นคง และเป็นการผสมผสานระหว่างวัดบ้านกับวัดป่า เพราะมีความเชื่อว่าศาสนาอยู่ได้ด้วยการปฏิบัติ การแบ่งแยกจึงไม่ใช่เรื่องสำคัญ ยึดหลักการสร้างตามบุญเป็นตั้งมั่น และด้วยการสร้างตามบุญนั้น จึงทำให้วัดมีความวิจิตรงดงาม ตระการตา ยิ่งใหญ่สมกับแรงศรัทธาของพุทธศาสนิกชน ถูกถ่ายทอดออกมาผ่านสิ่งปลูกสร้างต่าง ๆ ได้เป็นอย่างดี เป็นวัดที่ได้รับการยกย่องจากนักท่องเที่ยวว่าเป็นวัดอันดับ 1 ของจังหวัดเชียงใหม่\r\n\r\nการเดินทาง : ใช้เส้นทางเดียวกับเขื่อนแม่งัด ซึ่งอยู่ในเส้นทางท่องเที่ยวเชียงใหม่-สะเมิง หรือเชียงใหม่-ฝาง ขับรถไปตามทางหลวงหมายเลข 107 ผ่านแยกแม่มาลัย ไปถึงอำเภอแม่แตง เลี้ยวขวาทางไปเขื่อนแม่งัดสมบูรณ์ชล ขับตรงไปเรื่อย ๆ จะผ่านซุ้มเทศบาลเมืองแกน เมื่อถึงสนามกีฬา เลยไปไม่ไกลจะเห็นป้ายวัดบ้านเด่นอยู่ทางซ้าย เลี้ยวไปตามทางอีกประมาณ 1 กิโลเมตร ก็จะถึงวัดบ้านเด่นสะหลีศรีเมืองแกน ที่นี่อยู่ห่างจากตัวเมืองเชียงใหม่ 30 กิโลเมตร\r\n\r\nhttps://www.facebook.com/watbanden/\r\n\r\n', 1, 'https://www.youtube.com/watch?v=_pPcKcOR9AU', 19.15796373, 98.97846284, 'https://img-prod.api-onscene.com/cdn-cgi/image/format=auto,width=3200/https://sls-prod.api-onscene.com/partner_files/trueidintrend/329419/cover_image/%E0%B8%9B%E0%B8%81%201_2.jpg'),
(3, 'เขื่อนแม่งัดสมบรูณ์ชล', ' จากเหตุอุทกภัยครั้งใหญ่เมื่อปี พ.ศ. 2516 ที่ตำบลอินทขิล และตำบลช่อแล จ.เชียงใหม่ จึงได้มีพระราชดำริของพระบาทสมเด็จพระบรมชนกาธิเบศร มหาภูมิพลอดุลยเดชมหาราช บรมนาถบพิตร ให้มีการก่อสร้าง “เขื่อนแม่งัดสมบูรณ์ชล” เพื่ออำนวยประโยชน์ด้านการชลประทานแก่ราษฎรในพื้นที่ อีกทั้งยังสามารถช่วยบรรเทาอุทกภัยแก่พื้นที่เพาะปลูกท้ายอ่างเก็บน้ำ ตลอดจนนำมาใช้ผลิตไฟฟ้าได้\r\n\r\n           เขื่อนแม่งัดสมบูรณ์ชล เป็นโครงการพัฒนาแหล่งน้ำอเนกประสงค์ที่สำคัญอีกแห่งหนึ่งในภาคเหนือ หนึ่งในโครงการพระราชดำริ ที่เกิดจากความร่วมมือระหว่างการไฟฟ้าฝ่ายผลิตแห่งประเทศไทย (กฟผ.) กับกรมชลประทาน โดยกรมชลประทานรับผิดชอบในการสร้างเขื่อนและอาคารประกอบต่างๆ ส่วน กฟผ. รับผิดชอบโรงไฟฟ้าและระบบส่งไฟฟ้า เริ่มก่อสร้างเมื่อปี พ.ศ. 2520 โดยกรมชลประทานก่อสร้างตัวเขื่อนแล้วเสร็จเมื่อปี พ.ศ. 2527\r\n         ต่อมา กฟผ. ได้เข้ามาดำเนินการก่อสร้างโรงไฟฟ้าพลังน้ำ ในปี พ.ศ. 2526 แล้วเสร็จในปี พ.ศ. 2528 พระบาทสมเด็จพระบรมชนกาธิเบศร มหาภูมิพลอดุลยเดชมหาราช บรมนาถบพิตร ได้ทรงพระกรุณาโปรดเกล้าฯ พระราชทานนามเขื่อนว่า “เขื่อนแม่งัดสมบูรณ์ชล” เมื่อวันที่ 16 มกราคม พ.ศ. 2529 และเสด็จพระราชดำเนินทรงประกอบพิธีเปิดเขื่อน เมื่อวันที่ 22 กุมภาพันธ์ พ.ศ. 2529 ปัจจุบันมีขนาดกำลังผลิตตามสัญญา 9 เมกะวัตต์', 2, 'https://youtu.be/qSKK5UUUDqY?si=0inXsrs3SNFt-Z-k', 19.17441584, 99.03068561, 'https://www.egat.co.th/home/wp-content/uploads/2021/04/10682341_800130466676625_2195851740691855984_o.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int NOT NULL,
  `location_id` int NOT NULL,
  `media_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `location_id`, `media_type`, `url`, `created_at`) VALUES
(2, 1, 'วิดีโอ', 'https://www.youtube.com/watch?v=_pPcKcOR9AU', '2025-09-12 11:18:11');

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
-- Table structure for table `statistic`
--

CREATE TABLE `statistic` (
  `locationID` int NOT NULL,
  `viewCount` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `statistic`
--

INSERT INTO `statistic` (`locationID`, `viewCount`) VALUES
(1, 103),
(2, 7),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `email`, `password`) VALUES
(1, 'Anonymous User', 'anonymous@example.com', '$2y$10$SjoaebNPDnmCuayfQ1X/uuqyAWOZULIxqVLDJefjFdkrBY.CMhfZ2'),
(2, 'jon', 'HYUGUY2965@gmail.com', '$2y$10$qJgCbeTVakp2lStmUOI7Y.SlNLd5Lm/ZkLRxEZ4vJaFD02oZhXAWu'),
(3, 'Som007', 'ftgu2965@gmail.com', '$2y$10$Wj9ZMA0RbP.5Pe.2TXR3qOVEYeyoaqzOW60QycM6RUA2OZOhd.PRi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `location1`
--
ALTER TABLE `location1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_location_category` (`category_id`);

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
-- Indexes for table `statistic`
--
ALTER TABLE `statistic`
  ADD PRIMARY KEY (`locationID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `location1`
--
ALTER TABLE `location1`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location1` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `location1`
--
ALTER TABLE `location1`
  ADD CONSTRAINT `fk_location_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location1` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
