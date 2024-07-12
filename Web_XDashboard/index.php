<?php
require "assets/php/dashboard_handler.php";
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
                        <span class="text nav-text" data-key="dashboard">Dashboard</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="javascript:void(0)" onclick="showSection('training')">
                        <i class='bx bx-book icon'></i>
                        <span class="text nav-text" data-key="training">Training</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="javascript:void(0)" onclick="showSection('support')">
                        <i class='bx bx-support icon'></i>
                        <span class="text nav-text" data-key="support">Support</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="bottom-content">
            <li>
                <a href="javascript:void(0)" class="logout-link">
                    <i class='bx bx-log-out icon'></i>
                    <span class="text nav-text" data-key="logout">Logout</span>
                </a>
            </li>

            <li class="language-dropdown">
                <div class="dropdown-toggle" onclick="toggleLanguageMenu()">
                    <i class='bx bx-globe icon'></i>
                    <span class="text nav-text" data-key="language">Language</span>
                    <i class='bx bx-chevron-up icon'></i>
                </div>
                <ul class="language-menu">
                    <li><a href="javascript:void(0)" data-lang="fa">فارسی</a></li>
                    <li><a href="javascript:void(0)" data-lang="en">English</a></li>
                    <li><a href="javascript:void(0)" data-lang="ru">Русский</a></li>
                </ul>
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

<div class="message-container">
    <div id="error-message" class="error-message"></div>
    <div id="success-message" class="success-message"></div>
</div>

<section class="dashboard">
    <div class="text" data-key="dashboard">Dashboard</div>
    <div class="cards">
        <div class="card">
            <div class="card-header">
                <i class='bx bx-user icon'></i>
                <span class="card-title" data-key="userInfo">User Info</span>
            </div>
            <div class="card-content">
                <p><strong data-key="username">Username:</strong> <?php echo $username; ?></p>
                <p><strong data-key="email">Email:</strong> <?php echo $email; ?></p>
                <p><strong data-key="mobile">Mobile:</strong> <?php echo $mobile; ?></p>
                <p><strong data-key="status">Status:</strong> <?php echo $status; ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class='bx bx-time icon'></i>
                <span class="card-title" data-key="subscription">Subscription</span>
            </div>
            <div class="card-content">
                <p><strong data-key="startDate">Start Date:</strong> <?php echo $start_date; ?></p>
                <p><strong data-key="endDate">End Date:</strong> <?php echo $end_date; ?></p>
                <p><strong data-key="daysRemaining">Days Remaining:</strong> <?php echo $days_left; ?></p>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo $progress_percentage; ?>%;"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class='bx bx-bar-chart icon'></i>
                <span class="card-title" data-key="traffic">Traffic</span>
            </div>
            <div class="card-content">
                <p><strong data-key="download">Download:</strong> <?php echo formatTraffic($download); ?></p>
                <p><strong data-key="upload">Upload:</strong> <?php echo formatTraffic($upload); ?></p>
                <p><strong data-key="total">Total:</strong> <?php echo formatTraffic($total_traffic); ?></p>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo $data_used_percentage; ?>%;"></div>
                </div>
                <?php if (!empty($traffic)): ?>
                    <p><strong data-key="remainingData">Remaining Data:</strong> <?php echo formatTraffic($remaining_data); ?></p>
                <?php else: ?>
                    <p><strong data-key="unlimited">Data Limit:</strong> Unlimited</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class='bx bx-info-circle icon'></i>
                <span class="card-title" data-key="additionalInfo">Additional Info</span>
            </div>
            <div class="card-content">
                <p><strong data-key="referral">Referral:</strong> <?php echo $referral; ?></p>
                <p><strong data-key="description">Description:</strong> <?php echo $desc; ?></p>
            </div>
        </div>

    </div>
</section>

<section class="training">
    <div class="text" data-key="training">Training</div>
    <div class="training-container" data-key="apple">
        <div class="training-header">
            <div class="header-content">
                <i class='bx bxl-apple'></i>
                <span>Apple</span>
            </div>
            <i class='bx bx-chevron-down toggle-icon'></i>
        </div>
        <div class="training-content">
            <p>Coming Soon</p>
        </div>
    </div>
    <div class="training-container" data-key="android">
        <div class="training-header">
            <div class="header-content">
                <i class='bx bxl-android'></i>
                <span>Android</span>
            </div>
            <i class='bx bx-chevron-down toggle-icon'></i>
        </div>
        <div class="training-content">
            <p>Coming Soon</p>
        </div>
    </div>
    <div class="training-container" data-key="windows">
        <div class="training-header">
            <div class="header-content">
                <i class='bx bxl-windows'></i>
                <span>Windows</span>
            </div>
            <i class='bx bx-chevron-down toggle-icon'></i>
        </div>
        <div class="training-content">
            <p>Coming Soon</p>
        </div>
    </div>
</section>

<section class="support">
    <div class="text" data-key="support">Support</div>
    <div class="support-content">
        <div class="support-header">
            <button class="add-ticket-btn" id="add-ticket-btn">
                <i class='bx bx-plus'></i>
                <span data-key="addTicket">New Ticket</span>
            </button>
        </div>
        <div class="ticket-content">
            <div class="table__body">
                <table>
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Creation date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr data-ticket-id="<?php echo htmlspecialchars($ticket['id']); ?>">
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
    <div class="new-ticket-container">
        <div class="new-ticket-header"><i class='bx bx-message-detail icon'></i>New Ticket</div>
        <div class="new-ticket-content">
            <input type="text" id="title" name="title" class="input" placeholder="Title" required>
            <textarea id="description" name="description" rows="4" cols="50" class="input-desc" placeholder="Description" required></textarea>
            <select id="priority" name="priority" class="input-select">
                <option value="high">Priority High</option>
                <option value="medium" selected>Priority Medium</option>
                <option value="low">Priority Low</option>
            </select>
            <div class="btn-ticket-group">
                <button class="ticket-cancel" id="ticket-cancel">Cancel</button>
                <button class="ticket-submit" id="ticket-submit">Submit Ticket</button>
            </div>
        </div>
    </div>
    <div class="op-ticket-container">
        <div class="op-ticket-header">
            <button class="close-op-ticket" id="btn-close-op-ticket"><i class='bx bx-arrow-back'></i></button>
            Ticket
        </div>
        <div class="op-ticket-content">
            <div class="op-details-ticket">
                <p class="title-ticket"></p>
                <div class="status-ticket"></div>
                <div class="priority-ticket"></div>
                <div class="creation-ticket"></div>
            </div>
            <div class="op-description-ticket">
                <p class="description-ticket"></p>
            </div>
            <div class="op-responses-ticket">
                <h3>Responses:</h3>
                <ul class="responses-list"></ul>
            </div>
        </div>
    </div>
</section>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/script_nav_menu.js"></script>
<script src="assets/js/translations.js"></script>
</body>
</html>
