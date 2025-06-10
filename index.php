<?php
session_start();
require_once 'classes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $passwordInput = $_POST["password"];

    $conn = (new Database())->connect();

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($passwordInput, $user["password"])) {
            $_SESSION['user'] = $username;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['message'] = "Invalid username or password.";
        }
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
        <button type="submit">Login</button>
    </form>
    <a href="registerr.php" id="register-btn">Register</a>
</div>
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
