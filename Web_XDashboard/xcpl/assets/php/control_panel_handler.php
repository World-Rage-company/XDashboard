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

$stmt_ticket = $conn->prepare('
    SELECT tickets.id, tickets.title, tickets.description, tickets.status, tickets.priority, tickets.created_at, users.username
    FROM tickets
    JOIN users ON tickets.user_id = users.id
');
$stmt_ticket->execute();

$tickets = $stmt_ticket->fetchAll(PDO::FETCH_ASSOC);

if ($tickets) {
    echo json_encode($tickets);
} else {
    echo json_encode(["message" => "No tickets found."]);
}

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
