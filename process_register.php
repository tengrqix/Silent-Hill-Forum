<?php
session_start();
require_once 'classes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    $conn = (new Database())->connect();


    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultEmail = $stmt->get_result();

    if ($resultEmail && $resultEmail->num_rows > 0) {
        $_SESSION['message'] = "Email already exists.";
        header("Location: registerr.php");
        exit();
    }

    
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultUsername = $stmt->get_result();

    if ($resultUsername && $resultUsername->num_rows > 0) {
        $_SESSION['message'] = "Username already exists.";
        header("Location: registerr.php");
        exit();
    }

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $email);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful. You can now login.";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }

    header("Location: registerr.php");
    exit();
}
?>
