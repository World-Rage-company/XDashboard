<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: accounts/login.php");
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
