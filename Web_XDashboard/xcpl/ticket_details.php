<?php
require '../assets/php/database/config.php';

if (isset($_GET['id'])) {
    $ticket_id = intval($_GET['id']);

    $stmt_ticket = $conn->prepare('
        SELECT tickets.id, tickets.title, tickets.description, tickets.status, tickets.priority, tickets.created_at, users.username
        FROM tickets
        JOIN users ON tickets.user_id = users.id
        WHERE tickets.id = :id
    ');
    $stmt_ticket->bindParam(':id', $ticket_id, PDO::PARAM_INT);
    $stmt_ticket->execute();
    $ticket = $stmt_ticket->fetch(PDO::FETCH_ASSOC);

    if ($ticket) {
        echo "<h1>Ticket Details</h1>";
        echo "<p><strong>Title:</strong> " . htmlspecialchars($ticket['title']) . "</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($ticket['description']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($ticket['status']) . "</p>";
        echo "<p><strong>Priority:</strong> " . htmlspecialchars($ticket['priority']) . "</p>";
        echo "<p><strong>Created at:</strong> " . htmlspecialchars($ticket['created_at']) . "</p>";
        echo "<p><strong>Username:</strong> " . htmlspecialchars($ticket['username']) . "</p>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $response = $_POST['response'];
            $stmt_response = $conn->prepare('
                INSERT INTO ticket_responses (ticket_id, response, responded_at) 
                VALUES (:ticket_id, :response, NOW())
            ');
            $stmt_response->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
            $stmt_response->bindParam(':response', $response, PDO::PARAM_STR);
            if ($stmt_response->execute()) {
                echo "<p>Response submitted successfully!</p>";
            } else {
                echo "<p>Error submitting response.</p>";
            }
        }

        echo '
            <form method="POST" action="">
                <textarea name="response" placeholder="Write your response here..." required></textarea>
                <button type="submit">Submit Response</button>
            </form>
        ';
    } else {
        echo "<p>No ticket found with the given ID.</p>";
    }
} else {
    echo "<p>No ticket ID provided.</p>";
}
