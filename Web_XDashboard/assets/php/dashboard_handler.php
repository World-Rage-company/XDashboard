<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: accounts/login.php");
    exit();
}

require_once 'database/db.php';

$userId = $_SESSION['id'];

try {
    $conn = getDbConnection();

    $stmt_user = $conn->prepare('SELECT username, email, mobile, multiuser, start_date, end_date, date_one_connect, customer_user, status, traffic, referral, `desc`, access FROM users WHERE id = :id');
    $stmt_user->bindParam(':id', $userId);
    $stmt_user->execute();

    $user = $stmt_user->fetch();

    if (!$user) {
        echo "User not found.";
        exit();
    }

    if (!$user['access']) {
        session_unset();
        session_destroy();
        header("Location: accounts/login.php");
        exit();
    }

    $stmt_traffic = $conn->prepare('SELECT download, upload, total FROM traffic WHERE username = :username');
    $stmt_traffic->bindParam(':username', $user['username']);
    $stmt_traffic->execute();

    $traffic_info = $stmt_traffic->fetch();

    if (!$traffic_info) {
        echo "Traffic information not found.";
        exit();
    }

    $stmt_ticket = $conn->prepare('SELECT id, title, description, status, priority, created_at FROM tickets WHERE user_id = :user_id');
    $stmt_ticket->bindParam(':user_id', $userId);
    $stmt_ticket->execute();

    $tickets = $stmt_ticket->fetchAll(PDO::FETCH_ASSOC);

    if (!$tickets) {
        echo json_encode(["message" => "No tickets found."]);
    }

    $username = $user['username'];
    $email = $user['email'];
    $mobile = $user['mobile'];
    $multiuser = $user['multiuser'];
    $start_date = $user['start_date'];
    $end_date = $user['end_date'];
    $date_one_connect = $user['date_one_connect'];
    $customer_user = $user['customer_user'];
    $status = $user['status'];
    $traffic = $user['traffic'];
    $referral = $user['referral'];
    $desc = $user['desc'];

    $download = $traffic_info['download'];
    $upload = $traffic_info['upload'];
    $total_traffic = $traffic_info['total'];

    // Function to format traffic
    function formatTraffic($traffic) {
        if ($traffic < 1024) {
            return $traffic . ' MB';
        } else {
            return round($traffic / 1024, 2) . ' GB';
        }
    }

    // Calculate remaining days for Subscription
    $current_date = date('Y-m-d');
    $days_left = '-';
    $progress_percentage = 0;

    if (!empty($end_date)) {
        $days_left = ceil((strtotime($end_date) - strtotime($current_date)) / (60 * 60 * 24));
        $days_total = ceil((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24));
        $progress_percentage = ($days_total - $days_left) / $days_total * 100;
    } else {
        $progress_percentage = 100;
    }

    // Calculate remaining data for Traffic
    $remaining_data = '-';
    $data_used_percentage = 0;

    if (!empty($traffic)) {
        $remaining_data = $traffic - $total_traffic;
        if ($traffic > 0) {
            $data_used_percentage = ($total_traffic / $traffic) * 100;
        } else {
            $data_used_percentage = 100;
        }
    } else {
        $data_used_percentage = 100;
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "Something went wrong. Please try again.";
    exit();
}
