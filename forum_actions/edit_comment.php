<?php
session_start();
require_once '../classes/Database.php';

if (!isset($_SESSION['user'])) {
    echo "<div style='color: red; text-align: center;'>You must be logged in to edit a comment. <a href='../index.php'>Login here</a>.</div>";
    exit;
}

if (!isset($_GET['comment_id'])) {
    echo "Comment not found.";
    exit;
}

$commentId = (int)$_GET['comment_id'];
$conn = (new Database())->connect();

$stmt = $conn->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.id = ?");
$stmt->bind_param("i", $commentId);
$stmt->execute();
$result = $stmt->get_result();
$comment = $result->fetch_assoc();

if (!$comment || $comment['username'] != $_SESSION['user']) {
    echo "<p style='color:red;'>You cannot edit this comment.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);

    $stmt = $conn->prepare("UPDATE comments SET content = ? WHERE id = ?");
    $stmt->bind_param("si", $content, $commentId);

    if ($stmt->execute()) {
        header("Location: ../forum_section.php?id=" . $comment['section_id']);
        exit;
    } else {
        echo "<p style='color:red;'>Error updating comment.</p>";
    }
}

include '../partials/header.php';
?>

<h2>Edit Comment</h2>
<form action="edit_comment.php?comment_id=<?php echo $commentId; ?>" method="POST">
    <textarea name="content" rows="4" cols="108" required><?php echo htmlspecialchars($comment['content']); ?></textarea><br>
    <button type="submit">Update Comment</button>
</form>

<?php include '../partials/footer.php'; ?>
