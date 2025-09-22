<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
include '../config/db_connect.php';

$message = '';
$location_to_edit = null;

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_location'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $video_url = $_POST['video_url'];
        $map_url = $_POST['map_url'];

        $sql = "INSERT INTO locations (name, description, category, video_url, map_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $description, $category, $video_url, $map_url);
        if ($stmt->execute()) {
            $message = "เพิ่มสถานที่สำเร็จ!";
        } else {
            $message = "เกิดข้อผิดพลาดในการเพิ่มสถานที่: " . $conn->error;
        }
    } elseif (isset($_POST['update_location'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $video_url = $_POST['video_url'];
        $map_url = $_POST['map_url'];

        $sql = "UPDATE locations SET name=?, description=?, category=?, video_url=?, map_url=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $description, $category, $video_url, $map_url, $id);
        if ($stmt->execute()) {
            $message = "แก้ไขสถานที่สำเร็จ!";
        } else {
            $message = "เกิดข้อผิดพลาดในการแก้ไข: " . $conn->error;
        }
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM locations WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "ลบสถานที่สำเร็จ!";
        } else {
            $message = "เกิดข้อผิดพลาดในการลบ: " . $conn->error;
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM locations WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $location_to_edit = $result->fetch_assoc();
    }
}

$locations = $conn->query("SELECT * FROM locations ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสถานที่ท่องเที่ยว</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">จัดการสถานที่ท่องเที่ยว</h1>
            <nav>
                <a href="dashboard.php" class="text-blue-600 hover:underline mx-2">แดชบอร์ด</a>
                <a href="manage_reviews.php" class="text-blue-600 hover:underline mx-2">จัดการรีวิว</a>
                <a href="logout.php" class="text-red-600 hover:underline mx-2">ออกจากระบบ</a>
            </nav>
        </div>
    </header>
    <main class="container mx-auto mt-8 p-4">
        <h2 class="text-2xl font-bold mb-4">จัดการสถานที่ท่องเที่ยว</h2>
        <?php if ($message): ?>
            <p class="bg-green-100 text-green-700 p-3 rounded-lg mb-4"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Add/Edit Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="text-xl font-bold mb-4"><?php echo $location_to_edit ? 'แก้ไขสถานที่' : 'เพิ่มสถานที่ใหม่'; ?></h3>
            <form action="manage_locations.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $location_to_edit ? $location_to_edit['id'] : ''; ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">ชื่อสถานที่:</label>
                    <input type="text" name="name" value="<?php echo $location_to_edit ? htmlspecialchars($location_to_edit['name']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">หมวดหมู่:</label>
                    <input type="text" name="category" value="<?php echo $location_to_edit ? htmlspecialchars($location_to_edit['category']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">รายละเอียด:</label>
                    <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" rows="4"><?php echo $location_to_edit ? htmlspecialchars($location_to_edit['description']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">ลิงก์วิดีโอ (YouTube Embed):</label>
                    <input type="text" name="video_url" value="<?php echo $location_to_edit ? htmlspecialchars($location_to_edit['video_url']) : ''; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">ลิงก์แผนที่ (Google Maps):</label>
                    <input type="text" name="map_url" value="<?php echo $location_to_edit ? htmlspecialchars($location_to_edit['map_url']) : ''; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <button type="submit" name="<?php echo $location_to_edit ? 'update_location' : 'add_location'; ?>"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    บันทึกข้อมูล
                </button>
            </form>
        </div>

        <!-- Location List Table -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">รายการสถานที่ทั้งหมด</h3>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">ชื่อสถานที่</th>
                        <th class="py-2 px-4 border-b text-left">หมวดหมู่</th>
                        <th class="py-2 px-4 border-b text-center">ยอดเข้าชม</th>
                        <th class="py-2 px-4 border-b text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($locations) > 0): ?>
                        <?php foreach ($locations as $location): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($location['name']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($location['category']); ?></td>
                                <td class="py-2 px-4 border-b text-center"><?php echo $location['views']; ?></td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="manage_locations.php?action=edit&id=<?php echo $location['id']; ?>" class="text-blue-500 hover:underline mr-2">แก้ไข</a>
                                    <a href="manage_locations.php?action=delete&id=<?php echo $location['id']; ?>" onclick="return confirm('คุณแน่ใจที่จะลบสถานที่นี้?');" class="text-red-500 hover:underline">ลบ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4">ไม่พบสถานที่ท่องเที่ยว</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
