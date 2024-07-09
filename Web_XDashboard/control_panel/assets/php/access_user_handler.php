<?php
require_once __DIR__ . '/../../../assets/php/database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userId']) && isset($_POST['newAccess'])) {
        $userId = intval($_POST['userId']);
        $newAccess = intval($_POST['newAccess']);

        try {
            $conn = getDbConnection();
            $query = "UPDATE users SET access = :newAccess WHERE id = :userId";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':newAccess', $newAccess, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Access state updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update access state.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
