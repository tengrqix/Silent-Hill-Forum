<<<<<<< HEAD
<?php
session_start();  


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    $conn = new mysqli("localhost", "root", "", "silent_hill_forum");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        
        $_SESSION['user'] = $username;

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
      
        $_SESSION['message'] = "Invalid username or password."; 
    }

    $conn->close();
}


if (isset($_SESSION['message'])) {
    echo "<div style='color: red; text-align: center; margin-bottom: 10px;'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
?>

<?php if (!isset($_SESSION['user'])): ?>
<div id="login-panel">
    <h3>Login</h3>
    <form action="" method="POST"> 
        <input type="text" name="username" placeholder="Username" required value=""> 
        <input type="password" name="password" placeholder="Password" required value=""> 
=======
<div id="login-panel">
    <h3>Login</h3>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
>>>>>>> 4c9a175e21cc61150a4193bfe900bbc481503d9a
        <button type="submit">Login</button>
    </form>
    <a href="registerr.php" id="register-btn">Register</a>
</div>
<<<<<<< HEAD
<?php else: ?>
<div id="login-panel">
    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h3>
    <form action="logout.php" method="POST">
        <button type="submit">Logout</button>
    </form>
</div>
<?php endif; ?>

<?php include 'kokosy/header.php'; ?>

<img src="images/main.jpg" alt="" />
<div id="content">
    <h2>Welcome to Silent Hill Community</h2>
    <p>Step into the fog and explore the depths of the Silent Hill universe. This community is a haven for fans of the legendary horror franchise, where we discuss theories, share memories, and uncover the mysteries that haunt the town.</p>
    <p>Whether you're a longtime fan or new to the world of Silent Hill, you'll find a welcoming space here to share your thoughts, art, and experiences.</p>
    <p>Stay updated on the latest news, deep-dive into discussions, and connect with fellow survivors of the fog.</p>
    <p><strong>Dare to enter? The nightmare awaits.</strong></p>
</div>
<div class="line"></div>

<?php include 'kokosy/footer.php'; ?>
=======


<?php include 'kokosy/header.php'; 
?>
<img src="images/main.jpg" alt="" />
  <div id="content">
        <h2>Welcome to Silent Hill Community</h2>
        <p>Step into the fog and explore the depths of the Silent Hill universe. This community is a haven for fans of the legendary horror franchise, where we discuss theories, share memories, and uncover the mysteries that haunt the town.</p>
        <p>Whether you're a longtime fan or new to the world of Silent Hill, you'll find a welcoming space here to share your thoughts, art, and experiences.</p>
        <p>Stay updated on the latest news, deep-dive into discussions, and connect with fellow survivors of the fog.</p>
        <p><strong>Dare to enter? The nightmare awaits.</strong></p>
    </div>    <div class="line"></div>
<?php include 'kokosy/footer.php';
?>
>>>>>>> 4c9a175e21cc61150a4193bfe900bbc481503d9a
