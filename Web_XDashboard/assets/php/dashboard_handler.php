<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: accounts/login.php");
    exit();
}

require_once 'database/db.php';

$userId = $_SESSION['id'];

try {
    $conn = getDbConnection();

    $stmt_user = $conn->prepare('SELECT username, email, mobile, multiuser, start_date, end_date, date_one_connect, customer_user, status, traffic, referral, `desc` FROM users WHERE id = :id');
    $stmt_user->bindParam(':id', $userId);
    $stmt_user->execute();

    $user = $stmt_user->fetch();

    if (!$user) {
        echo "User not found.";
        exit();
    }

    $stmt_traffic = $conn->prepare('SELECT download, upload, total FROM traffic WHERE username = :username');
    $stmt_traffic->bindParam(':username', $user['username']);
    $stmt_traffic->execute();

    $traffic_info = $stmt_traffic->fetch();

    if (!$traffic_info) {
        echo "Traffic information not found.";
        exit();
    }

    $username = $user['username'];
    $email = $user['email'];
    $mobile = $user['mobile'];
    $multiuser = $user['multiuser'];
    $start_date = $user['start_date'];
    $end_date = $user['end_date'];
    $date_one_connect = $user['date_one_connect'];
    $customer_user = $user['customer_user'];
    $status = $user['status'];
    $traffic = $user['traffic'];
    $referral = $user['referral'];
    $desc = $user['desc'];

    $download = $traffic_info['download'];
    $upload = $traffic_info['upload'];
    $total_traffic = $traffic_info['total'];

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "Something went wrong. Please try again.";
    exit();
}
