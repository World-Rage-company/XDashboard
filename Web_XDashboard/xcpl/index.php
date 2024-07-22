<?php
require "assets/php/control_panel_handler.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XCPL</title>
    <link rel="icon" href="../assets/images/xlogo.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="message-container">
    <div id="error-message" class="error-message"></div>
    <div id="success-message" class="success-message"></div>
</div>

<div class="container">
    <div class="header-container">
        <div class="header-content">
            <div class="title">XCPL</div>
            <div class="bottom-content">
                <li>
                    <a href="javascript:void(0)" class="logout-link">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text" data-key="logout">Logout</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>
                    <div class="toggle-switch-mode">
                        <span class="switch-mode"></span>
                    </div>
                </li>
            </div>
        </div>
    </div>
    <div class="setting-container">
        <div class="setting-header"><i class='bx bx-cog icon'></i>Setting</div>
        <div class="setting-content">Coming Soon.</div>
    </div>
    <div class="Support-container">
        <div class="Support-header"><i class='bx bx-support icon'></i>Support</div>
        <div class="Support-content">
            <div class="ticket-content">
                <div class="table__body">
                    <table>
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Creation date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr data-ticket-id="<?php echo htmlspecialchars($ticket['id']); ?>">
                                <td><?php echo htmlspecialchars($ticket['username']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['title']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['status']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['priority']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="users-container">
        <div class="users-header"><i class='bx bx-user icon'></i>Users</div>
        <div class="users-content">
            <div class="table__body">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>USERNAME</th>
                        <th>ACCESS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td>
                                <div class="toggle-switch <?php echo $user['access'] ? 'on' : ''; ?>" data-userid="<?php echo $user['id']; ?>">
                                    <span class="switch"></span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
