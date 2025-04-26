<?php
<<<<<<< HEAD
session_start(); 

=======
>>>>>>> 4c9a175e21cc61150a4193bfe900bbc481503d9a
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]); 
    $email = $_POST["email"];

<<<<<<< HEAD
=======
   
>>>>>>> 4c9a175e21cc61150a4193bfe900bbc481503d9a
    $conn = new mysqli("localhost", "root", "", "silent_hill_forum");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
<<<<<<< HEAD
    $checkEmail = "SELECT id FROM users WHERE email = '$email'";
    $resultEmail = $conn->query($checkEmail);

   
    $checkUsername = "SELECT id FROM users WHERE username = '$username'";
    $resultUsername = $conn->query($checkUsername);

    if ($resultEmail->num_rows > 0) {
        $_SESSION['message'] = "Email already taken.";
    } elseif ($resultUsername->num_rows > 0) {
        $_SESSION['message'] = "Username already taken";
    } else {
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Registration was sucessfull";
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
        }
    }

    $conn->close();

   
    header("Location: registerr.php");
    exit();
}
?>
=======
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='index.php'>Go back</a>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
>>>>>>> 4c9a175e21cc61150a4193bfe900bbc481503d9a
