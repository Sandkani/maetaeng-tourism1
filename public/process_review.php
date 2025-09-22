<?php
/**
 * This file handles the form submission for a location review.
 * It simulates database operations using a MockDatabase class.
 */

// Start a session to manage user state (simulated)
session_start();

// Mock database connection and data
//---------------------------------------------------------
class MockDatabase {
    public function prepare($sql) {
        return new class($sql) {
            private $sql;
            private $params = [];

            public function __construct($sql) {
                $this->sql = $sql;
            }

            public function bind_param($types, &...$args) {
                $this->params = $args;
            }

            public function execute() {
                // Simulate a successful operation
                return true;
            }

            public function close() {}
        };
    }

    public function close() {}
}

$conn = new MockDatabase();
//---------------------------------------------------------

$response = [
    'success' => false,
    'message' => 'เกิดข้อผิดพลาดไม่ทราบสาเหตุ' // Unknown error occurred
];

// Check if the form was submitted with POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are present and not empty
    if (
        !isset($_POST['location_id']) ||
        !isset($_POST['username']) ||
        !isset($_POST['rating']) ||
        !isset($_POST['comment']) ||
        empty($_POST['location_id']) ||
        empty($_POST['username']) ||
        empty($_POST['rating']) ||
        empty($_POST['comment'])
    ) {
        $response['message'] = 'ข้อมูลไม่ครบถ้วน กรุณากรอกให้ครบทุกช่อง'; // Incomplete data. Please fill in all fields.
    } else {
        // Sanitize and validate inputs
        $locationId = htmlspecialchars($_POST['location_id']);
        $username = htmlspecialchars($_POST['username']);
        $rating = (int)$_POST['rating'];
        $comment = htmlspecialchars($_POST['comment']);
        
        // Simulate adding a review with a Prepared Statement for security
        // In a real application, you would use a real database connection here.
        // The mock class just demonstrates the concept.
        
        // Assume user_id is retrieved from session or authentication system
        // For this mock, we'll use a hardcoded user ID.
        // In a real app, you would not get the username from the form.
        $userId = 1; // Simulated user ID from authentication
        
        $sql = "INSERT INTO reviews (location_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            // Bind parameters to the statement
            // In real code, 'isss' would be 'iisi' for (location_id, user_id, rating, comment) as int, int, string, string.
            // Using 'isss' to match mock data types and demonstrate the concept.
            $stmt->bind_param("iiss", $locationId, $userId, $rating, $comment);
            
            // Execute the statement
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'ขอบคุณสำหรับรีวิวของคุณ!'; // Thank you for your review!
            } else {
                $response['message'] = 'มีข้อผิดพลาดในการบันทึกข้อมูลรีวิว'; // Error saving review data
            }
            
            $stmt->close();
        } else {
            $response['message'] = 'ไม่สามารถเตรียมคำสั่ง SQL ได้'; // Could not prepare SQL statement
        }
    }
} else {
    $response['message'] = 'การเข้าถึงไม่ถูกต้อง'; // Invalid access
}

$conn->close();

// Respond with JSON
header('Content-Type: application/json');
echo json_encode($response);

// In a real scenario, you might redirect after success
// header("Location: location_detail.php?id=" . $locationId);
// exit();
?>
