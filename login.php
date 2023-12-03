<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "users_db";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = sanitizeInput($_POST["username"]);
    $password = sanitizeInput($_POST["password"]);

    // Query to check if the user exists
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    $message = "Invalid password";
    // Check if the user exists
    if ($result->num_rows > 0) {
        header("Location: http://localhost/personaldiary/diaryinfo.php");
    } else {
        header("Location: http://localhost/login.system/index.php?message=" . urlencode($message));
    }
}

// Close the database connection
$conn->close();
