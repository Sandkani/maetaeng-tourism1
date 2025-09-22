<?php
session_start();
// รวมไฟล์เชื่อมต่อฐานข้อมูล
include '../config/db_connect.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่าชื่อผู้ใช้หรืออีเมลมีอยู่แล้วหรือไม่
    $sql_check = "SELECT userID FROM User WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $message = "ชื่อผู้ใช้หรืออีเมลนี้มีอยู่แล้ว";
    } else {
        // คำสั่ง INSERT ที่ถูกต้องสำหรับฐานข้อมูลที่ใช้ AUTO_INCREMENT
        // ไม่ต้องระบุคอลัมน์ userID เพราะฐานข้อมูลจะจัดการให้เอง
        $sql_insert = "INSERT INTO User (username, password, email) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $username, $hashed_password, $email);
        
        if ($stmt_insert->execute()) {
            $message = "ลงทะเบียนสำเร็จ! คุณสามารถเข้าสู่ระบบได้แล้ว";
        } else {
            $message = "เกิดข้อผิดพลาด: " . $conn->error;
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนผู้ใช้งาน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<body class="font-kanit bg-indigo-600 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-sm">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">ลงทะเบียน ✍️</h1>
        <?php if ($message): ?>
            <div class="<?php echo strpos($message, 'สำเร็จ') !== false ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?> p-3 rounded-md mb-4 text-center">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required
                       class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">อีเมล:</label>
                <input type="email" id="email" name="email" required
                       class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required
                       class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-shadow">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl focus:outline-none focus:shadow-outline w-full transition-colors">
                    ลงทะเบียน
                </button>
            </div>
        </form>
        <p class="text-center text-gray-500 text-sm mt-4">มีบัญชีอยู่แล้ว? <a href="login.php" class="text-indigo-500 hover:underline">เข้าสู่ระบบ</a></p>
    </div>
</body>
</html>
