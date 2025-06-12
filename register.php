<?php
session_start();
require_once 'classes/Auth.php';
include 'partials/header.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    if (Auth::register($username, $password, $email)) {
        header("Location: register.php");
        exit();
    }
    
}
?>
<body>
    <div class="register-container">
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div style='color: red; text-align: center; margin-bottom: 10px;'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <h2>Create Your Account</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Register</button>
        </form>
    </div>
    <div class="linehhh"></div>
<?php include 'partials/footer.php'; ?>
