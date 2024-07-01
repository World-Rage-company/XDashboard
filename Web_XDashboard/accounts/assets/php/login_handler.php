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

        $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user && $password === $user['password']) {
            $_SESSION['id'] = $user['id'];

            echo "success";
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
