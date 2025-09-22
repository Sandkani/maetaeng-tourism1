<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$host = "localhost";
$dbname = "login_system";
$user = "root";
$pass = ""; // รหัสผ่านของ MySQL (ปรับตามของคุณ)

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["message" => "Database connection failed"]);
  exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  echo json_encode(["message" => "Login successful"]);
} else {
  http_response_code(401);
  echo json_encode(["message" => "Invalid credentials"]);
}

$stmt->close();
$conn->close();
?>
