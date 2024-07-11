<?php
require "database/db.php";

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$priority = $_POST['priority'] ?? '';

$response = [
    'status' => 'success',
    'message' => 'Ticket submitted successfully!'
];

header('Content-Type: application/json');

echo json_encode($response);
