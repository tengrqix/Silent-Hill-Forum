<?php
session_start();
require_once 'classes/Auth.php';

// Spracovanie logoutu cez GET parameter:
if (isset($_GET['logout'])) {
    Auth::logout();
}

// Spracovanie loginu:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (Auth::login($username, $password)) {
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['message'] = "Invalid username or password.";
    }
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
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="register.php" id="register-btn">Register</a>
</div>
<?php else: ?>
<div id="login-panel">
    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h3>
</div>

<?php endif; ?>

<?php include 'partials/header.php'; ?>

<img src="images/main.jpg" alt="" />
<div id="content">
    <h2>Welcome to Silent Hill Community</h2>
    <p>Step into the fog and explore the depths of the Silent Hill universe. This community is a haven for fans of the legendary horror franchise, where we discuss theories, share memories, and uncover the mysteries that haunt the town.</p>
    <p>Whether you're a longtime fan or new to the world of Silent Hill, you'll find a welcoming space here to share your thoughts, art, and experiences.</p>
    <p>Stay updated on the latest news, deep-dive into discussions, and connect with fellow survivors of the fog.</p>
    <p><strong>Dare to enter? The nightmare awaits.</strong></p>
</div>
<div class="line"></div>

<?php include 'partials/footer.php'; ?>
