<?php
session_start();
include '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location_id = $_POST['location_id'];
    $username = $_POST['username'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];
    
    // Find or create user
    $sql_user = "SELECT id FROM users WHERE username = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user = $result_user->fetch_assoc();

    if ($user) {
        $user_id = $user['id'];
    } else {
        // Create new user with a random password since this is just a review user
        $hashed_password = password_hash(uniqid(), PASSWORD_DEFAULT);
        $sql_insert_user = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $dummy_email = uniqid() . "@example.com";
        $stmt_insert_user->bind_param("sss", $username, $hashed_password, $dummy_email);
        $stmt_insert_user->execute();
        $user_id = $conn->insert_id;
    }
    
    // Insert the new review
    $sql_review = "INSERT INTO reviews (location_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt_review = $conn->prepare($sql_review);
    $stmt_review->bind_param("iiss", $location_id, $user_id, $rating, $comment);
    $stmt_review->execute();
    
    // Redirect back to the location detail page
    header("Location: location_detail.php?id=" . $location_id);
    exit;
}
?>
