-- สคริปต์สำหรับสร้างฐานข้อมูลและตารางตามโครงสร้างที่กำหนด
-- ลบฐานข้อมูลเก่าถ้ามีอยู่
DROP DATABASE IF EXISTS `maetaeng_tourism`;

-- สร้างฐานข้อมูลใหม่
CREATE DATABASE `maetaeng_tourism` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- เลือกฐานข้อมูลที่จะใช้งาน
USE `maetaeng_tourism`;

-- D1 & D2: ตารางผู้ใช้งานและผู้ดูแลระบบ
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `is_admin` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- D4: ตารางหมวดหมู่สถานที่ (Reference File)
CREATE TABLE `categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- D3: ตารางสถานที่ท่องเที่ยว (Master File)
CREATE TABLE `locations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `category_id` INT(11),
  `video_url` VARCHAR(255),
  `map_url` VARCHAR(255),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- D5: ตารางสื่อประกอบสถานที่ (Transaction File)
CREATE TABLE `media` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `location_id` INT(11) NOT NULL,
  `media_type` VARCHAR(50) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- D6: ตารางความคิดเห็น (Transaction File)
CREATE TABLE `comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `location_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- D7: ตารางคะแนนสถานที่ (Transaction File)
CREATE TABLE `ratings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `location_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `rating` INT(1) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- D8: ตารางสถิติความนิยม (Summary File)
CREATE TABLE `statistics` (
  `location_id` INT(11) NOT NULL,
  `total_views` INT(11) DEFAULT 0,
  `average_rating` DECIMAL(2,1) DEFAULT 0.0,
  PRIMARY KEY (`location_id`),
  FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- เพิ่มข้อมูลตัวอย่าง
-- รหัสผ่านคือ 'admin123'
INSERT INTO `users` (`username`, `password`, `email`, `is_admin`) VALUES
('admin', '$2y$10$wT0l.Xh4Q/oZ5A5I3S/3eO5j2t/t.D9b/B.Gz.Q6o/y6aG.t5.H.G', 'admin@example.com', 1);

INSERT INTO `categories` (`name`) VALUES
('วัด'),
('ธรรมชาติ'),
('กิจกรรม'),
('แหล่งท่องเที่ยว');

-- เพิ่มข้อมูลสถานที่ท่องเที่ยวตัวอย่าง (อ้างอิง category_id)
INSERT INTO `locations` (`name`, `description`, `category_id`, `video_url`, `map_url`) VALUES
('วัดเด่นสะหลีศรีเมืองแก่น', 'วัดที่สร้างขึ้นด้วยสถาปัตยกรรมล้านนาประยุกต์อันงดงามที่ใหญ่ที่สุดในจังหวัดเชียงใหม่ มีเจดีย์ประดิษฐานในทุกๆ มุม และมีความโดดเด่นไม่เหมือนวัดไหนๆ', 1, 'https://www.youtube.com/embed/5D34uW4z2v0', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.609420067645!2d98.9669!3d19.1417!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da37f8f8f8f8f9%3A0x6d8f8f8f8f8f8f8f!2z4Lir4Lit4Lij4Lix4LiB4Liy4LiZ4LmI4Liy4LiV4LiB4Liy4LiV4LmJ4Liy4Lir4LiB4Liy!5e0!3m2!1sen!2sth!4v1628198758838!5m2!1sen!2sth'),
('อุทยานแห่งชาติน้ำตกบัวตอง-น้ำพุเจ็ดสี', 'อุทยานแห่งชาติที่ประกอบไปด้วย น้ำตกบัวตอง และน้ำพุเจ็ดสี น้ำตกบัวตองเป็นน้ำตกหินปูนที่มีความพิเศษคือ สามารถปีนป่ายขึ้นไปชมน้ำตกได้ เนื่องจากพื้นผิวของหินปูนไม่มีความลื่น', 2, NULL, 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3778.6179420067645!2d99.0430!3d19.1670!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da35f8f8f8f8f9%3A0x7d8f8f8f8f8f8f8f!2z4Lir4Lit4Lij4Lix4LiB4Liy4LiZ4LmI4Liy4LiV4LiB4Liy4LiV4LmJ4Liy4Lir4LiB4Liy!5e0!3m2!1sen!2sth!4v1628198758838!5m2!1sen!2sth'),
('แดนเทวดา', 'สถานที่ท่องเที่ยวที่ตกแต่งด้วยสวนและน้ำตกจำลองที่สวยงาม มีมุมถ่ายรูปเพียบ', 4, 'https://www.youtube.com/embed/S2pEa4fV38g', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3778.6179420067645!2d98.9224!3d19.0667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da35f8f8f8f8f9%3A0x2d8f8f8f8f8f8f8f!2z4Lir4Lit4Lij4Lix4LiB4Liy4LiZ4LmI4Liy4LiV4LiB4Liy4LiV4LmJ4Liy4Lir4LiB4Liy!5e0!3m2!1sen!2sth!4v1628198758838!5m2!1sen!2sth');
