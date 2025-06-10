<?php
session_start(); 
include 'kokosy/header.php';
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
        <form action="process_register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Register</button>
        </form>
    </div>
    <div class="linehhh"></div>
<?php include 'kokosy/footer.php'; ?>
