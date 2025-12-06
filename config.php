<?php
/**
 * Database Configuration File
 * Online Job Portal
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'job_portal');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Helper function to sanitize input
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Helper function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Helper function to check user type
function check_user_type($required_type) {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
    if ($_SESSION['user_type'] != $required_type) {
        header("Location: index.php");
        exit();
    }
}

// Helper function to display error messages
function display_error($message) {
    return '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
}

// Helper function to display success messages
function display_success($message) {
    return '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}
?>
