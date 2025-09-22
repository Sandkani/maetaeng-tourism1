<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
include '../config/db_connect.php';

$message = '';
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "ลบรีวิวสำเร็จ!";
    } else {
        $message = "เกิดข้อผิดพลาดในการลบรีวิว: " . $conn->error;
    }
}

// Fetch all reviews with location and user details
$sql = "SELECT r.*, l.name AS location_name, u.username 
        FROM reviews r
        JOIN locations l ON r.location_id = l.id
        JOIN users u ON r.user_id = u.id
        ORDER BY r.created_at DESC";
$reviews = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการความคิดเห็น</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">จัดการความคิดเห็น</h1>
            <nav>
                <a href="dashboard.php" class="text-blue-600 hover:underline mx-2">แดชบอร์ด</a>
                <a href="manage_locations.php" class="text-blue-600 hover:underline mx-2">จัดการสถานที่</a>
                <a href="logout.php" class="text-red-600 hover:underline mx-2">ออกจากระบบ</a>
            </nav>
        </div>
    </header>
    <main class="container mx-auto mt-8 p-4">
        <h2 class="text-2xl font-bold mb-4">รายการความคิดเห็นทั้งหมด</h2>
        <?php if ($message): ?>
            <p class="bg-green-100 text-green-700 p-3 rounded-lg mb-4"><?php echo $message; ?></p>
        <?php endif; ?>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">สถานที่</th>
                        <th class="py-2 px-4 border-b text-left">ผู้ใช้</th>
                        <th class="py-2 px-4 border-b text-left">ความคิดเห็น</th>
                        <th class="py-2 px-4 border-b text-center">คะแนน</th>
                        <th class="py-2 px-4 border-b text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reviews) > 0): ?>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($review['location_name']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($review['username']); ?></td>
                                <td class="py-2 px-4 border-b max-w-sm overflow-hidden text-ellipsis"><?php echo htmlspecialchars($review['comment']); ?></td>
                                <td class="py-2 px-4 border-b text-center"><?php echo $review['rating']; ?></td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="manage_reviews.php?action=delete&id=<?php echo $review['id']; ?>" onclick="return confirm('คุณแน่ใจที่จะลบรีวิวนี้?');" class="text-red-500 hover:underline">ลบ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">ไม่พบความคิดเห็น</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">ผู้ใช้</th>
                        <th class="py-2 px-4 border-b text-left">ความคิดเห็น</th>
                        <th class="py-2 px-4 border-b text-center">คะแนน</th>
                        <th class="py-2 px-4 border-b text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reviews) > 0): ?>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($review['username']); ?></td>
                                <td class="py-2 px-4 border-b max-w-sm overflow-hidden text-ellipsis"><?php echo htmlspecialchars($review['comment']); ?></td>
                                <td class="py-2 px-4 border-b text-center"><?php echo $review['rating']; ?></td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="manage_reviews.php?action=delete&id=<?php echo $review['id']; ?>" onclick="return confirm('คุณแน่ใจที่จะลบรีวิวนี้?');" class="text-red-500 hover:underline">ลบ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4">ไม่พบความคิดเห็น</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>