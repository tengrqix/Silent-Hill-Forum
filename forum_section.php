<?php
session_start();
require_once 'classes/Database.php';

if (!isset($_GET['id'])) {
    echo "Section not found.";
    exit;
}

$sectionId = (int)$_GET['id'];
$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("SELECT * FROM forum_sections WHERE id = ?");
$stmt->bind_param("i", $sectionId);
$stmt->execute();
$result = $stmt->get_result();
$section = $result->fetch_assoc();

if (!$section) {
    echo "Section not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $content = trim($_POST['comment']);
    $username = $_SESSION['user']; 

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $userData = $userResult->fetch_assoc();

    if ($userData) {
        $userId = $userData['id'];

        if (!empty($content)) {
            $stmt = $conn->prepare("INSERT INTO comments (section_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $sectionId, $userId, $content);
            $stmt->execute();
            header("Location: forum_section.php?id=$sectionId");
            exit;
        } else {
            echo "<p style='color:red;'>Comment cannot be empty.</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found.</p>";
    }
}

$stmt = $conn->prepare("SELECT c.content, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.section_id = ? ORDER BY c.created_at DESC");
$stmt->bind_param("i", $sectionId);
$stmt->execute();
$comments = $stmt->get_result();
?>
<?php include 'kokosy/header.php'; ?>
<h2><?php echo htmlspecialchars($section['name']); ?></h2>
<p><?php echo nl2br(htmlspecialchars($section['description'])); ?></p>

<h3>Comments</h3>
<?php if ($comments->num_rows > 0): ?>
    <?php while ($comment = $comments->fetch_assoc()): ?>
        <div style="border: 1px solid #ccc; margin: 10px 0; padding: 10px;">
            <strong><?php echo htmlspecialchars($comment['username']); ?></strong> wrote on <?php echo $comment['created_at']; ?>:
            <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No comments yet.</p>
<?php endif; ?>

<?php if (isset($_SESSION['user'])): ?>
    <h3>Add a Comment</h3>
    <form method="POST">
        <textarea name="comment" rows="4" cols="108" required></textarea><br>
        <button type="submit">Post Comment</button>
    </form>
<?php else: ?>
    <p><a href="index.php">Login</a> to add a comment.</p>
<?php endif; ?>
<?php include 'kokosy/footer.php'; ?>
