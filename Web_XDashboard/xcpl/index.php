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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="message-container">
    <div id="error-message" class="error-message"></div>
    <div id="success-message" class="success-message"></div>
</div>

<div class="sidebar">
    <div class="logo-details">
        <i class='bx bxl-xing icon'></i>
        <div class="logo_name">XCPL</div>
        <i class="bx bx-menu" id="btn-sidebar"></i>
    </div>
    <ul class="nav-list">
        <li>
            <a href="javascript:void(0)">
                <i class="bx bx-grid-alt"></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li>
            <a href="javascript:void(0)">
                <i class="bx bx-user"></i>
                <span class="links_name">User</span>
            </a>
            <span class="tooltip">User</span>
        </li>
        <!--<li>
            <a href="#">
                <i class="bx bx-cart-alt"></i>
                <span class="links_name">Order</span>
            </a>
            <span class="tooltip">Order</span>
        </li>-->
        <li>
            <a href="javascript:void(0)">
                <i class="bx bx-cog"></i>
                <span class="links_name">Setting</span>
            </a>
            <span class="tooltip">Setting</span>
        </li>
        <li class="profile">
            <div class="profile-details">
                <img src="../assets/images/xlogo.png" alt="profileImg" />
                <div class="name_job">
                    <div class="name"><?php echo $admin_data['username'];?></div>
                    <div class="Status"><?php echo $admin_data['status'];?></div>
                </div>
            </div>
            <i class="bx bx-log-out" id="log_out"></i>
        </li>
    </ul>
</div>

<section class="dashboard-section"></section>

<section class="user-section"></section>

<section class="setting-section"></section>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
