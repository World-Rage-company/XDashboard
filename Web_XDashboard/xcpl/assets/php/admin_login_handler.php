<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../../assets/php/database/db.php';

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
        exit;
    }

    try {
        $conn = getDbConnection();

        $stmt = $conn->prepare('SELECT id, password, permission FROM admins WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            if ($admin['permission'] === 'admin') {
                $_SESSION['admin_id'] = $admin['id'];
                echo "success";
            } else {
                echo "Access denied. You do not have permission to log in as admin.";
            }
        } else {
            echo "Invalid username or password.";
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "Something went wrong. Please try again.";
    }
} else {
    echo "Invalid request method.";
}
