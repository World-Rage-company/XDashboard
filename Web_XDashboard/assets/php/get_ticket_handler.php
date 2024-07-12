<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit();
}

require_once 'database/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$ticketId = $data['id'];

try {
    $conn = getDbConnection();

    $stmt_ticket = $conn->prepare('SELECT title, description, status, priority, created_at FROM tickets WHERE id = :id AND user_id = :user_id');
    $stmt_ticket->bindParam(':id', $ticketId);
    $stmt_ticket->bindParam(':user_id', $_SESSION['id']);
    $stmt_ticket->execute();
    $ticket = $stmt_ticket->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        echo json_encode(['status' => 'error', 'message' => 'Ticket not found.']);
        exit();
    }

    $stmt_responses = $conn->prepare('SELECT response, responder_id, responded_at FROM ticket_responses WHERE ticket_id = :ticket_id');
    $stmt_responses->bindParam(':ticket_id', $ticketId);
    $stmt_responses->execute();
    $responses = $stmt_responses->fetchAll(PDO::FETCH_ASSOC);

    $ticket['responses'] = $responses;

    echo json_encode(['status' => 'success', 'ticket' => $ticket]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again.']);
}
