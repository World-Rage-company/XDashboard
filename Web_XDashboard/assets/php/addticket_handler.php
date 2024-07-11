<?php
require "database/db.php";

session_start();

$userId = $_SESSION['id'];

$data = json_decode(file_get_contents('php://input'), true);

$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$priority = $data['priority'] ?? '';

try {
    $conn = getDbConnection();

    $stmt_admin = $conn->prepare('SELECT id FROM admins WHERE permission = "admin" LIMIT 1');
    $stmt_admin->execute();

    $admin = $stmt_admin->fetch();
    $adminId = $admin['id'];

    if (!$adminId) {
        throw new Exception('Admin not found.');
    }

    error_log('Priority: ' . $priority);

    if (!in_array($priority, ['high', 'medium', 'low'])) {
        throw new Exception('Invalid priority value.');
    }

    $stmt = $conn->prepare('INSERT INTO tickets (user_id, admin_id, title, description, priority) VALUES (:user_id, :admin_id, :title, :description, :priority)');
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':admin_id', $adminId);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':priority', $priority);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Ticket submitted successfully!'
    ];

} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => 'Error submitting ticket: ' . $e->getMessage()
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
