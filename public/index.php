<?php
// เริ่ม session เพื่อจัดการสถานะการล็อกอิน
session_start();

// จำลองการเชื่อมต่อฐานข้อมูลและข้อมูล
// โค้ดส่วนนี้จะทำงานได้โดยไม่ต้องมีฐานข้อมูลจริง
//----------------------------------------------
class MockDatabase {
    public function query($sql) {
        if (strpos($sql, 'FROM categories') !== false) {
            return new class {
                public function fetch_all($mode) {
                    // ปรับปรุงหมวดหมู่ตามที่ผู้ใช้ต้องการ
                    return [
                        ['id' => 1, 'name' => 'วัด'],
                        ['id' => 2, 'name' => 'ธรรมชาติ'],
                        ['id' => 3, 'name' => 'กิจกรรม'],
                        ['id' => 4, 'name' => 'แหล่งท่องเที่ยว']
                    ];
                }
            };
        }
        return false;
    }
    public function close() {}
}

$conn = new MockDatabase();
//----------------------------------------------

// ดึงข้อมูลหมวดหมู่ทั้งหมด
$categories = [];
$sql_categories = "SELECT id, name FROM categories ORDER BY name ASC";
$result_categories = $conn->query($sql_categories);
if ($result_categories) {
    $categories = $result_categories->fetch_all(MYSQLI_ASSOC);
}

// ตรวจสอบตัวกรองและโหมดการแสดงผล
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$is_map_view = isset($_GET['view']) && $_GET['view'] === 'map';
$locations = [];

// ข้อมูลสถานที่ท่องเที่ยวจำลอง (ปรับปรุง category_id และ category_name)
$locations_list = [
    [
        'id' => 1,
       'name' => 'อุทยานแห่งชาติน้ำตกบัวตอง-น้ำพุเจ็ดสี',
        'description' => 'น้ำตกที่สวยงามและน้ำพุที่น่าสนใจ',
        'category_name' => 'ธรรมชาติ',
        'total_views' => '850',
        'icon' => '⛰️',
        'coords' => ['top' => '45%', 'left' => '75%'],
        'category_id' => 2,
        'image_url' => 'https://trueid-slsapp-storage-prod.s3-ap-southeast-1.amazonaws.com/partner_files/trueidintrend/143794/%E0%B8%9A%E0%B8%B1%E0%B8%A7%E0%B8%95%E0%B8%AD%E0%B8%87%2010-6-62_%E0%B9%91%E0%B9%99%E0%B9%90%E0%B9%96%E0%B9%91%E0%B9%96_0012.jpg'
    ],
    [
        'id' => 2,
        'name' => 'วัดเด่นสะหลีศรีเมืองแก่น',
        'description' => 'วัดที่สวยงามและมีสถาปัตยกรรมที่โดดเด่น',
        'category_name' => 'วัด',
        'total_views' => '999',
        'icon' => '🙏',
        'coords' => ['top' => '15%', 'left' => '30%'],
        'category_id' => 1,
        'image_url' => 'https://img-prod.api-onscene.com/cdn-cgi/image/format=auto,width=3200/https://sls-prod.api-onscene.com/partner_files/trueidintrend/329419/cover_image/%E0%B8%9B%E0%B8%81%201_2.jpg'
    ],
    [
        'id' => 3,
        'name' => 'เขื่อนแม่งัดสมบรูณ์ชล',
        'description' => 'เขื่อนขนาดใหญ่ที่ล้อมรอบด้วยภูเขาและธรรมชาติ',
        'category_name' => 'ธรรมชาติ',
        'total_views' => '780',
        'icon' => '🏞️',
        'coords' => ['top' => '60%', 'left' => '40%'],
        'category_id' => 2,
        'image_url' => 'https://image.bangkokbiznews.com/uploads/images/contents/w1024/2024/11/BtO6vzwXZbbpdGgMjfbU.webp?x-image-process=style/md-webp'
    ],
    [
        'id' => 4,
        'name' => 'ปางช้างแม่แตง',
        'description' => 'สถานที่สำหรับชมและทำกิจกรรมกับช้าง',
        'category_name' => 'กิจกรรม',
        'total_views' => '620',
        'icon' => '🐘',
        'coords' => ['top' => '25%', 'left' => '60%'],
        'category_id' => 3,
        'image_url' => 'https://placehold.co/400x250/5B21B6/FFFFFF?text=Mae+Taeng+Elephant+Camp'
    ],
    [
        'id' => 5,
        'name' => 'แดนเทวดา',
        'description' => 'คาเฟ่และร้านอาหารที่มีการตกแต่งสวยงาม',
        'category_name' => 'แหล่งท่องเที่ยว',
        'total_views' => '540',
        'icon' => '☕',
        'coords' => ['top' => '70%', 'left' => '20%'],
        'category_id' => 4,
        'image_url' => 'https://placehold.co/400x250/5B21B6/FFFFFF?text=Daen+Tewada'
    ],
    [
        'id' => 6,
        'name' => 'สวนสนแม่แตง',
        'description' => 'สวนป่าสนที่ร่มรื่นและเย็นสบาย',
        'category_name' => 'ธรรมชาติ',
        'total_views' => '450',
        'icon' => '🌲',
        'coords' => ['top' => '85%', 'left' => '55%'],
        'category_id' => 2,
        'image_url' => 'https://placehold.co/400x250/5B21B6/FFFFFF?text=Mae+Taeng+Pine+Garden'
    ],
    [
        'id' => 7,
        'name' => 'น้ำตกหมอกฟ้า',
        'description' => 'น้ำตกที่สวยงามอีกแห่งหนึ่งของแม่แตง',
        'category_name' => 'ธรรมชาติ',
        'total_views' => '390',
        'icon' => '🌊',
        'coords' => ['top' => '50%', 'left' => '55%'],
        'category_id' => 2,
        'image_url' => 'https://placehold.co/400x250/5B21B6/FFFFFF?text=Mok+Fa+Waterfall'
    ],
    [
        'id' => 8,
        'name' => 'น้ำพุร้อนโป่งเดือด',
        'description' => 'น้ำพุร้อนธรรมชาติที่สามารถแช่เท้าได้',
        'category_name' => 'ธรรมชาติ',
        'total_views' => '280',
        'icon' => '♨️',
        'coords' => ['top' => '75%', 'left' => '85%'],
        'category_id' => 2,
        'image_url' => 'https://placehold.co/400x250/5B21B6/FFFFFF?text=Pong+Dueat+Hot+Spring'
    ],
    [
        'id' => 9,
        'name' => 'หาดปางเป้า',
        'description' => 'ชายหาดจำลองริมเขื่อนแม่งัด',
        'category_name' => 'ธรรมชาติ',
        'total_views' => '150',
        'icon' => '🏖️',
        'coords' => ['top' => '80%', 'left' => '10%'],
        'category_id' => 2,
        'image_url' => 'https://placehold.co/400x250/5B21B6/FFFFFF?text=Pang+Pao+Beach'
    ]
];

// กรองข้อมูลตามหมวดหมู่ที่เลือก
if ($category_id > 0) {
    $locations = array_filter($locations_list, function($location) use ($category_id) {
        return $location['category_id'] === $category_id;
    });
} else {
    $locations = $locations_list;
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_map_view ? 'แผนที่ท่องเที่ยวแม่แตง' : 'สถานที่ท่องเที่ยวแม่แตง'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
        .container {
            max-width: 1024px;
        }
        .map-container {
            position: relative;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="1" fill="%23d1c4e9"/><circle cx="50" cy="30" r="1" fill="%23d1c4e9"/><circle cx="90" cy="50" r="1" fill="%23d1c4e9"/><circle cx="20" cy="70" r="1" fill="%23d1c4e9"/><circle cx="60" cy="90" r="1" fill="%23d1c4e9"/></svg>');
            background-size: 20px 20px;
            background-color: #f3f4f6;
            background-repeat: repeat;
        }
        .landmark {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s;
            z-index: 5;
        }
        .landmark:hover {
            transform: scale(1.1);
            z-index: 10;
        }
        .landmark-icon {
            font-size: 3rem;
            line-height: 1;
        }
        .landmark-name {
            background-color: #ffffff;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-top: 0.5rem;
            font-weight: bold;
            white-space: nowrap;
        }
        .info-box {
            position: absolute;
            background-color: #ffffff;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            max-width: 250px;
            transform: translate(-50%, 0);
            left: 50%;
            bottom: 100%;
            margin-bottom: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s, transform 0.3s;
        }
        .landmark:hover .info-box {
            opacity: 1;
            visibility: visible;
        }
        .info-box::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 16px;
            height: 16px;
            background-color: #ffffff;
        }
    </style>
</head>
<body class="font-kanit bg-gray-50 p-4">
    <header class="bg-indigo-600 text-white p-6 shadow-md rounded-xl relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 opacity-80"></div>
        <div class="container mx-auto flex justify-between items-center relative z-10">
            <a href="index.php" class="text-3xl font-bold">🏞️ เที่ยวแม่แตง</a>
            <nav class="flex items-center space-x-4">
                <a href="statistics.php" class="bg-white text-indigo-600 py-2 px-4 rounded-full font-bold hover:bg-gray-200 transition-colors">สถิติความนิยม</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- แสดงชื่อผู้ใช้และปุ่มออกจากระบบ -->
                    <span class="font-bold hidden md:inline">ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="bg-red-500 text-white py-2 px-4 rounded-full font-bold hover:bg-red-600 transition-colors">ออกจากระบบ</a>
                <?php else: ?>
                    <!-- แสดงปุ่มเข้าสู่ระบบ -->
                    <a href="login.php" class="bg-green-500 text-white py-2 px-4 rounded-full font-bold hover:bg-green-600 transition-colors">เข้าสู่ระบบ</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container mx-auto mt-8 p-4 bg-white rounded-xl shadow-lg">
        <?php if ($is_map_view): ?>
            <!-- โหมดแผนที่จำลอง -->
            <h1 class="text-4xl font-bold text-center text-gray-800 mb-2">🗺️ แผนที่ท่องเที่ยวแม่แตง</h1>
            <p class="text-center text-gray-500 mb-8">สำรวจสถานที่ยอดนิยมและแหล่งท่องเที่ยวที่น่าสนใจ</p>
            <div class="w-full text-center mb-6">
                <a href="index.php" class="inline-block bg-indigo-500 text-white font-bold py-2 px-6 rounded-full shadow-lg hover:bg-indigo-600 transition-colors">
                    กลับหน้าหลัก
                </a>
            </div>
            <div class="map-container w-full h-[600px] rounded-2xl overflow-hidden p-8 relative">
                <?php foreach ($locations as $location): ?>
                    <div class="landmark group" style="top: <?php echo htmlspecialchars($location['coords']['top']); ?>; left: <?php echo htmlspecialchars($location['coords']['left']); ?>;">
                        <a href="location_detail.php?id=<?php echo htmlspecialchars($location['id']); ?>" class="block">
                            <div class="landmark-icon"><?php echo htmlspecialchars($location['icon']); ?></div>
                            <div class="landmark-name"><?php echo htmlspecialchars($location['name']); ?></div>
                            <div class="info-box group-hover:block">
                                <img src="<?php echo htmlspecialchars($location['image_url']); ?>" alt="<?php echo htmlspecialchars($location['name']); ?>" class="rounded-lg mb-2">
                                <h3 class="font-bold text-lg mb-1"><?php echo htmlspecialchars($location['name']); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($location['description']); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- โหมดรายการปกติ -->
            <h1 class="text-4xl font-bold mb-2 text-center text-gray-800">สำรวจสถานที่ท่องเที่ยวในแม่แตง!</h1>
            <p class="text-center text-gray-500 mb-6">ค้นหาสถานที่ที่คุณสนใจได้ตามใจชอบ</p>
            <a href="index.php?view=map" class="block">
                <div class="w-full h-48 bg-gray-200 rounded-lg overflow-hidden mb-6 relative">
                    <img src="https://placehold.co/1024x192/E5E7EB/5B21B6?text=Maetaeng+Tourism+Map" alt="แผนที่แม่แตง" class="w-full h-full object-cover">
                    <div class="absolute inset-0 flex items-center justify-center p-4">
                        <div class="bg-white/80 backdrop-blur-sm rounded-full px-6 py-3 font-bold text-gray-800 text-lg shadow-xl">
                            แผนที่จำลอง
                        </div>
                    </div>
                </div>
            </a>
            <!-- ตัวกรองหมวดหมู่ -->
            <div class="flex flex-wrap justify-center gap-2 mb-6 p-4 bg-indigo-100 rounded-xl">
                <a href="index.php" class="py-2 px-4 rounded-full text-sm font-bold transition-all
                    <?php echo $category_id === 0 ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-indigo-600 hover:bg-indigo-200'; ?>">
                    ทั้งหมด
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="index.php?category_id=<?php echo htmlspecialchars($cat['id']); ?>" class="py-2 px-4 rounded-full text-sm font-bold transition-all
                        <?php echo $category_id === (int)$cat['id'] ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-indigo-600 hover:bg-indigo-200'; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <!-- รายการสถานที่ท่องเที่ยว -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($locations)): ?>
                    <?php foreach ($locations as $location): ?>
                        <a href="location_detail.php?id=<?php echo htmlspecialchars($location['id'] ?? ''); ?>" class="block bg-gray-50 rounded-xl shadow-lg border-2 border-indigo-200 hover:shadow-2xl hover:scale-105 transition-all duration-300 relative overflow-hidden">
                            <div class="w-full h-48 bg-cover bg-center rounded-t-xl" style="background-image: url('<?php echo htmlspecialchars($location['image_url']); ?>');"></div>
                            <div class="p-6 relative z-10">
                                <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($location['name']); ?></h2>
                                <span class="bg-indigo-500 text-white text-xs px-2 py-1 rounded-full mt-2 inline-block font-bold shadow-md"><?php echo htmlspecialchars($location['category_name'] ?? 'ทั่วไป'); ?></span>
                                <p class="text-gray-700 mt-2 line-clamp-3"><?php echo htmlspecialchars($location['description']); ?></p>
                                <div class="mt-4 text-sm text-gray-500 flex items-center">
                                    <span class="mr-2 text-indigo-500">⭐</span> ยอดเข้าชม: <?php echo htmlspecialchars($location['total_views']); ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 col-span-full text-center p-8">ไม่พบสถานที่ท่องเที่ยวในหมวดหมู่นี้</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
    <footer class="bg-gray-800 text-white p-4 text-center mt-8 rounded-lg">
        <p>&copy; 2024 เที่ยวแม่แตง. All rights reserved.</p>
    </footer>
</body>
</html>
