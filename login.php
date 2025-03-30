<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]); 

   
    $conn = new mysqli("localhost", "root", "", "silent_hill_forum");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

       
        if ($password == $row["password"]) {
        
            header("Location: index.php"); 
            exit();
        } else {
           
            echo "Incorrect password.";
        }
    } else {
        
        echo "User not found.";
    }

    $conn->close();
}
?>

