<?php include 'kokosy/header.php'; 
?>
<body>
    <div class="register-container">
        <h2>Create Your Account</h2>
        <form action="process_register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Register</button>
        </form>
    </div>
    </div>    <div class="linehhh"></div>
<?php include 'kokosy/footer.php';
?>

