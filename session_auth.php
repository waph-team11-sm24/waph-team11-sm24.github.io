<?php
session_start();

// Session settings
$lifetime = 15 * 60;
$path = "/";
$domain = ""; // Use an empty string for localhost
$secure = FALSE; // Change to TRUE for HTTPS
$httponly = TRUE;

session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);

// CSRF token generation
if (!isset($_SESSION['nocsrftoken'])) {
    $_SESSION['nocsrftoken'] = bin2hex(random_bytes(16));
}
?>
