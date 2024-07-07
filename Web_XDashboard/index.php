<?php
//require "assets/php/dashboard_handler.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XDashboard</title>
    <link rel="icon" href="assets/images/xlogo.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<nav class="sidebar close">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="assets/images/xlogo.png" alt="Logo">
            </span>
            <div class="text logo-text">
                <span class="name">XDashboard</span>
                <span class="profession">VBeta</span>
            </div>
        </div>
        <i class='bx bx-chevron-right toggle'></i>
    </header>

    <div class="menu-bar">
        <div class="menu">
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="javascript:void(0)" onclick="showSection('dashboard')">
                        <i class='bx bx-home-alt icon'></i>
                        <span class="text nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="javascript:void(0)" onclick="showSection('training')">
                        <i class='bx bx-book icon'></i>
                        <span class="text nav-text">Training</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="javascript:void(0)" onclick="showSection('support')">
                        <i class='bx bx-support icon'></i>
                        <span class="text nav-text">Support</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="bottom-content">
            <li>
                <a href="javascript:void(0)" class="logout-link">
                    <i class='bx bx-log-out icon'></i>
                    <span class="text nav-text">Logout</span>
                </a>
            </li>

            <li class="mode">
                <div class="sun-moon">
                    <i class='bx bx-moon icon moon'></i>
                    <i class='bx bx-sun icon sun'></i>
                </div>
                <span class="mode-text text">Dark mode</span>
                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li>
        </div>
    </div>
</nav>

<section class="dashboard">
    <div class="text"><?php echo $username; ?></div>
    <div class="cards">
        <div class="card">
            <div class="card-header">
                <i class='bx bx-user icon'></i>
                <span class="card-title">User Info</span>
            </div>
            <div class="card-content">
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Mobile:</strong> <?php echo $mobile; ?></p>
                <p><strong>Status:</strong> <?php echo $status; ?></p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <i class='bx bx-time icon'></i>
                <span class="card-title">Subscription</span>
            </div>
            <div class="card-content">
                <p><strong>Start Date:</strong> <?php echo $start_date; ?></p>
                <p><strong>End Date:</strong> <?php echo $end_date; ?></p>
                <p><strong>Days Remaining:</strong> <?php echo $days_left; ?></p>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo $progress_percentage; ?>%;"></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <i class='bx bx-bar-chart icon'></i>
                <span class="card-title">Traffic</span>
            </div>
            <div class="card-content">
                <p><strong>Download:</strong> <?php echo formatTraffic($download); ?></p>
                <p><strong>Upload:</strong> <?php echo formatTraffic($upload); ?></p>
                <p><strong>Total:</strong> <?php echo formatTraffic($total_traffic); ?></p>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo $data_used_percentage; ?>%;"></div>
                </div>
                <?php if (!empty($traffic)): ?>
                    <p><strong>Remaining Data:</strong> <?php echo formatTraffic($remaining_data); ?></p>
                <?php else: ?>
                    <p><strong>Data Limit:</strong> Unlimited</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <i class='bx bx-info-circle icon'></i>
                <span class="card-title">Additional Info</span>
            </div>
            <div class="card-content">
                <p><strong>Referral:</strong> <?php echo $referral; ?></p>
                <p><strong>Description:</strong> <?php echo $desc; ?></p>
            </div>
        </div>
    </div>
</section>

<section class="training">
    <div class="text">Training</div>
    <div class="training-content">
        <p>Training.</p>
    </div>
</section>

<section class="support">
    <div class="text">Support</div>
</section>

<script src="assets/js/script.js"></script>
<script src="assets/js/script_nav_menu.js"></script>
</body>
</html>
