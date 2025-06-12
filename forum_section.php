<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Comment.php';

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


$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();
$loggedUserId = $userData['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $content = trim($_POST['comment']);
    if (!empty($content)) {
        Comment::addComment($sectionId, $loggedUserId, $content);
        header("Location: forum_section.php?id=$sectionId");
        exit;
    } else {
        echo "<p style='color:red;'>Comment cannot be empty.</p>";
    }
}


if (isset($_GET['delete_comment'])) {
    $commentIdToDelete = (int)$_GET['delete_comment'];
    $commentToDelete = Comment::getCommentById($commentIdToDelete);

    if ($commentToDelete && $commentToDelete['user_id'] == $loggedUserId) {
        Comment::deleteComment($commentIdToDelete);
        header("Location: forum_section.php?id=$sectionId");
        exit;
    } else {
        echo "<p style='color:red;'>You cannot delete this comment.</p>";
    }
}


$comments = Comment::getCommentsBySection($sectionId);

include 'partials/header.php';
?>

<h2><?php echo htmlspecialchars($section['name']); ?></h2>
<p><?php echo nl2br(htmlspecialchars($section['description'])); ?></p>

<h3>Comments</h3>

<?php if (count($comments) > 0): ?>
    <?php foreach ($comments as $comment): ?>
        <div style="border: 1px solid #ccc; margin: 10px 0; padding: 10px;">
            <div style="display: flex; align-items: center;">
                <?php if (!empty($comment['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($comment['profile_picture']); ?>" alt="Profile Pic" width="40" height="40" style="border-radius:50%; margin-right:10px;">
                <?php else: ?>
                    <img src="images/profile_pics/default.png" alt="Profile Pic" width="40" height="40" style="border-radius:50%; margin-right:10px;">
                <?php endif; ?>

                <strong><?php echo htmlspecialchars($comment['username']); ?></strong> wrote on <?php echo $comment['created_at']; ?>:
            </div>
            <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>

            <?php if ($comment['user_id'] == $loggedUserId): ?>
                <div>
                    <a href="forum_actions/edit_comment.php?comment_id=<?php echo $comment['id']; ?>">Edit Comment</a> |
                    <a href="forum_section.php?id=<?php echo $sectionId; ?>&delete_comment=<?php echo $comment['id']; ?>" style="color:red;" onclick="return confirm('Are you sure you want to delete this comment?');">Delete Comment</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
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

<?php include 'partials/footer.php'; ?>
