<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once __DIR__ . '/../../../assets/php/database/db.php';

$conn = getDbConnection();

$query = "SELECT id, username, access FROM users";
$result = $conn->query($query);

if ($result) {
    $users = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $users[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'access' => $row['access']
        ];
    }

    $result->closeCursor();

} else {
    echo "Error retrieving users data.";
}

$admin_id = $_SESSION['admin_id'];
$admin_query = "SELECT * FROM admins WHERE id = :admin_id";
$stmt_admin = $conn->prepare($admin_query);
$stmt_admin->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt_admin->execute();

if ($stmt_admin->rowCount() > 0) {
    $admin_data = $stmt_admin->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Error retrieving admin data.";
}
