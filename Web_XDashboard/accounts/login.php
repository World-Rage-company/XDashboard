<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | XDashboard</title>
    <link rel="icon" href="assets/images/xlogo.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style_login.css">
</head>
<body>

<div class="loading_widget">
    <button class="loader__btn">
        <div class="loader"></div>
        Loading ...
    </button>
</div>

<div class="login-container">
    <div class="login-header">
        <div class="login-logo"></div>
        <h2>Login to XDashboard</h2>
    </div>
    <div class="message-container">
        <div id="error-message" class="error-message"></div>
        <div id="success-message" class="success-message"></div>
    </div>
    <form id="loginForm">
        <div class="input-container">
            <input type="text" name="username" id="username" class="input-field" placeholder="Username">
        </div>
        <div class="input-container">
            <input type="password" name="password" id="password" class="input-field" placeholder="Password">
        </div>
        <div class="button-container">
            <button type="button" class="login-button" id="login-button">Login</button>
        </div>
    </form>
</div>

<script src="assets/js/script_login.js"></script>
</body>
</html>
