<?php
// รวมไฟล์เชื่อมต่อฐานข้อมูล
include '../config/db_connect.php';
session_start();

// ตรวจสอบว่าผู้ดูแลระบบล็อกอินแล้วหรือไม่
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // หากยังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอิน
    header("Location: login.php");
    exit();
}

// กำหนดไดเรกทอรีสำหรับอัปโหลดไฟล์ (สมมติว่าไฟล์นี้อยู่ในโฟลเดอร์ admin)
$upload_dir = __DIR__ . "/uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$locations = [];
$categories = [];

// ดึงข้อมูลสถานที่ทั้งหมด
$sql = "SELECT l.*, c.name AS category_name FROM location1 l LEFT JOIN category c ON l.category_id = c.id ORDER BY l.id DESC";
$result = $conn->query($sql);
if ($result) {
    $locations = $result->fetch_all(MYSQLI_ASSOC);
} else {
    error_log("Error fetching locations: " . $conn->error);
}

// ดึงข้อมูลหมวดหมู่ทั้งหมด
$sql_categories = "SELECT * FROM category";
$result_categories = $conn->query($sql_categories);
if ($result_categories) {
    $categories = $result_categories->fetch_all(MYSQLI_ASSOC);
} else {
    error_log("Error fetching categories: " . $conn->error);
}

// โค้ดสำหรับจัดการฟอร์ม (เพิ่ม/แก้ไข/ลบ)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'add_location') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $latitude = $_POST['latitude'] ?? null;
            $longitude = $_POST['longitude'] ?? null;
            
            // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูลหลักลงในตาราง location1
            $stmt = $conn->prepare("INSERT INTO location1 (name, description, category_id, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssiss", $name, $description, $category_id, $latitude, $longitude);
                if ($stmt->execute()) {
                    $new_location_id = $conn->insert_id;

                    // ส่วนจัดการอัปโหลดรูปภาพ
                    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
                        $image_name = uniqid() . '-' . basename($_FILES['image_file']['name']);
                        $image_path = $upload_dir . $image_name;
                        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path)) {
                            // URL ที่จะบันทึกลงฐานข้อมูล (สมมติว่าโฟลเดอร์ dashboard คือ admin)
                            $image_url = 'admin/uploads/' . $image_name; // <-- แก้ไขบรรทัดนี้
                            $stmt_media = $conn->prepare("INSERT INTO media (location_id, type, url) VALUES (?, ?, ?)");
                            $type_image = 'image';
                            $stmt_media->bind_param("iss", $new_location_id, $type_image, $image_url);
                            $stmt_media->execute();
                            $stmt_media->close();
                        } else {
                            error_log("Failed to move uploaded image file.");
                        }
                    }

                    // ส่วนจัดการอัปโหลดวิดีโอ
                    if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] == UPLOAD_ERR_OK) {
                        $video_name = uniqid() . '-' . basename($_FILES['video_file']['name']);
                        $video_path = $upload_dir . $video_name;
                        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $video_path)) {
                            // URL ที่จะบันทึกลงฐานข้อมูล (สมมติว่าโฟลเดอร์ dashboard คือ admin)
                            $video_url = 'admin/uploads/' . $video_name; // <-- แก้ไขบรรทัดนี้
                            $stmt_media = $conn->prepare("INSERT INTO media (location_id, type, url) VALUES (?, ?, ?)");
                            $type_video = 'video';
                            $stmt_media->bind_param("iss", $new_location_id, $type_video, $video_url);
                            $stmt_media->execute();
                            $stmt_media->close();
                        } else {
                            error_log("Failed to move uploaded video file.");
                        }
                    }

                } else {
                    error_log("Execute failed: " . $stmt->error);
                }
                $stmt->close();
            } else {
                error_log("Prepare failed: " . $conn->error);
            }
        } elseif ($action == 'delete_location') {
            $location_id = $_POST['location_id'];
            
            // ลบไฟล์มีเดียที่เกี่ยวข้องก่อน
            $stmt_media_urls = $conn->prepare("SELECT url FROM media WHERE location_id = ?");
            $stmt_media_urls->bind_param("i", $location_id);
            $stmt_media_urls->execute();
            $result_media_urls = $stmt_media_urls->get_result();
            while ($row = $result_media_urls->fetch_assoc()) {
                // สำหรับการลบไฟล์ ต้องสร้าง path จาก document root ของเว็บ ไม่ใช่ __DIR__
                // แต่เนื่องจากเราไม่รู้ document root ที่แน่นอน การใช้ __DIR__ แล้วย้อนกลับไปอาจไม่เสถียร
                // โค้ดเดิมจะทำงานได้ถ้า script ลบ อยู่ในโฟลเดอร์เดียวกับที่สร้าง path
                $file_path = __DIR__ . "/../../" . $row['url']; // สมมติว่า root อยู่สองระดับบน admin
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            $stmt_media_urls->close();

            // ลบข้อมูลสถานที่
            $stmt = $conn->prepare("DELETE FROM location1 WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $location_id);
                if (!$stmt->execute()) {
                    error_log("Execute failed: " . $stmt->error);
                }
                $stmt->close();
            } else {
                error_log("Prepare failed: " . $conn->error);
            }
        }
    }
    // Refresh หน้าหลังจากดำเนินการ
    header("Location: dashboard.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดผู้ดูแลระบบ - เที่ยวแม่แตง</title>
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
        .table-auto {
            border-collapse: collapse;
        }
        .table-auto th, .table-auto td {
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
        }
    </style>
</head>
<body class="font-kanit bg-gray-100 p-4">
    <header class="bg-emerald-600 text-white p-6 shadow-md rounded-xl">
        <div class="container mx-auto flex justify-between items-center">
            <a href="dashboard.php" class="text-3xl font-bold">แดชบอร์ดผู้ดูแลระบบ</a>
            <a href="logout.php" class="bg-white text-emerald-600 py-2 px-4 rounded-full font-bold hover:bg-gray-200 transition-colors">ออกจากระบบ</a>
        </div>
    </header>
    <main class="container mx-auto mt-8 p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">เพิ่มสถานที่ท่องเที่ยวใหม่</h2>
            <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_location">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-gray-700">ชื่อสถานที่:</label>
                        <input type="text" id="name" name="name" required class="w-full mt-1 p-2 border rounded-md">
                    </div>
                    <div>
                        <label for="category_id" class="block text-gray-700">หมวดหมู่:</label>
                        <select id="category_id" name="category_id" class="w-full mt-1 p-2 border rounded-md">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-gray-700">คำอธิบาย:</label>
                        <textarea id="description" name="description" rows="4" class="w-full mt-1 p-2 border rounded-md"></textarea>
                    </div>
                    <div>
                        <label for="image_file" class="block text-gray-700">อัปโหลดรูปภาพ:</label>
                        <input type="file" id="image_file" name="image_file" accept="image/*" class="w-full mt-1">
                    </div>
                    <div>
                        <label for="video_file" class="block text-gray-700">อัปโหลดวิดีโอ:</label>
                        <input type="file" id="video_file" name="video_file" accept="video/*" class="w-full mt-1">
                    </div>
                    <div>
                        <label for="latitude" class="block text-gray-700">ละติจูด (Latitude):</label>
                        <input type="text" id="latitude" name="latitude" class="w-full mt-1 p-2 border rounded-md">
                    </div>
                    <div>
                        <label for="longitude" class="block text-gray-700">ลองจิจูด (Longitude):</label>
                        <input type="text" id="longitude" name="longitude" class="w-full mt-1 p-2 border rounded-md">
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded transition-colors">เพิ่มสถานที่</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">รายการสถานที่ทั้งหมด</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="py-2 px-4">ชื่อสถานที่</th>
                            <th class="py-2 px-4">หมวดหมู่</th>
                            <th class="py-2 px-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($locations)): ?>
                            <?php foreach ($locations as $location): ?>
                                <tr class="border-b">
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($location['name']); ?></td>
                                    <td class="py-2 px-4">
                                        <?php echo htmlspecialchars($location['category_name'] ?? 'ไม่มีหมวดหมู่'); ?>
                                    </td>
                                    <td class="py-2 px-4">
                                        <form action="dashboard.php" method="POST" class="inline-block">
                                            <input type="hidden" name="action" value="delete_location">
                                            <input type="hidden" name="location_id" value="<?php echo htmlspecialchars($location['id']); ?>">
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-xs transition-colors">ลบ</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">ไม่พบสถานที่ในระบบ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <footer class="bg-gray-800 text-white p-4 text-center mt-8 rounded-lg">
        <p>&copy; <?php echo date("Y"); ?> เที่ยวแม่แตง. All rights reserved.</p>
    </footer>
</body>
</html>