<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]); 
    $email = $_POST["email"];

   
    $conn = new mysqli("localhost", "root", "", "silent_hill_forum");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='index.php'>Go back</a>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
