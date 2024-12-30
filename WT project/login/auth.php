<?php
session_start();
$host = 'localhost';  // Replace with your database host
$db   = 'smart_home_dashboard';  // Replace with your database name
$user = 'root';  // Replace with your database username
$pass = '';  // Replace with your database password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sign-up functionality
if (isset($_POST['signup'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($query) === TRUE) {
        echo "Sign-up successful. Please log in.";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// Sign-in functionality
if (isset($_POST['signin'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: ../floors/floors.html");  // Redirect to the next page
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
$conn->close();
?>
