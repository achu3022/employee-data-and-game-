<?php
session_start();
include "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Employee Login</h2>
        
        <input type="text" id="emp_id" placeholder="Enter Employee ID">
        <p id="error-msg" class="error" style="display: none;"></p>

        <div id="user-info" style="display: none;">
            <p><strong>Name:</strong> <span id="name"></span></p>
            <p><strong>Phone:</strong> <span id="phone"></span></p>
            <p><strong>Department:</strong> <span id="department"></span></p>
        </div>

        <div id="verification-section" style="display: none;">
            <p>Verification required.</p>
        </div>

        <!-- Register Button (Hidden by Default) -->
        <button id="register-btn" style="display: none;" onclick="window.location.href='register.php'">Register</button>

        <!-- Proceed Button -->
        <button id="next-btn" onclick="proceed()" style="display: none;">Next</button>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
