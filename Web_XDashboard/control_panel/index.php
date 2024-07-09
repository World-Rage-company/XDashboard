<?php
require "assets/php/control_panel_handler.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XDashboard CPL</title>
    <link rel="icon" href="../assets/images/xlogo.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="notifications"></div>

<div class="container">
    <div class="header-container">
        <div class="header-content">Header</div>
    </div>
    <div class="setting-container">
        <div class="setting-header"><i class='bx bx-cog icon'></i>Setting</div>
        <div class="setting-content">Coming Soon.</div>
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
