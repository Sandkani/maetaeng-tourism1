<?php
// รวมไฟล์เชื่อมต่อฐานข้อมูล
include '../config/db_connect.php';
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // ใช้ Prepared Statement เพื่อค้นหาผู้ใช้
    // เปลี่ยนชื่อตารางจาก 'users' เป็น 'admin'
    $sql = "SELECT adminID, password FROM admin WHERE adminUsername = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // ตรวจสอบรหัสผ่านที่เข้ารหัสแล้ว
            // password_verify() จะคืนค่า true หากรหัสผ่านที่ป้อนตรงกับแฮช
            if (password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['adminID'];
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
            }
        } else {
            $message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ดูแลระบบ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
        .container {
            max-width: 500px;
        }
    </style>
</head>
<body class="bg-gray-100 p-4 flex items-center justify-center min-h-screen">
    <div class="container bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-center mb-6">เข้าสู่ระบบผู้ดูแลระบบ</h1>
        <?php if ($message): ?>
            <div class="bg-red-100 text-red-800 p-3 rounded-md mb-4 text-center">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded transition-colors">เข้าสู่ระบบ</button>
        </form>
        <p class="text-center mt-4 text-sm text-gray-500">สำหรับทดสอบ: ชื่อผู้ใช้ <span class="font-mono bg-gray-200 px-1 rounded">admin</span> / รหัสผ่าน <span class="font-mono bg-gray-200 px-1 rounded">password</span></p>
    </div>
</body>
</html>
