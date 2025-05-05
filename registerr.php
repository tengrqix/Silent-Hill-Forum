<<<<<<< HEAD
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

=======
<?php include 'kokosy/header.php'; 
?>
<body>
    <div class="register-container">
>>>>>>> 4c9a175e21cc61150a4193bfe900bbc481503d9a
        <h2>Create Your Account</h2>
        <form action="process_register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Register</button>
        </form>
    </div>
<<<<<<< HEAD
    <div class="linehhh"></div>
<?php include 'kokosy/footer.php'; ?>
=======
    </div>    <div class="linehhh"></div>
<?php include 'kokosy/footer.php';
?>

>>>>>>> 4c9a175e21cc61150a4193bfe900bbc481503d9a
