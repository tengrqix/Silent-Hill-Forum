<?php
session_start();
require_once 'classes/ForumSection.php';

if (!isset($_GET['id'])) {
    echo "Section not found.";
    exit;
}

$sectionId = (int)$_GET['id'];
$sectionObj = new ForumSection();


$loggedUserId = null;
if (isset($_SESSION['user'])) {
    $conn = (new Database())->connect();
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $loggedUserId = $userData['id'];
}

$section = $sectionObj->getSectionById($sectionId);

if (!$section) {
    echo "Section not found.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']) && isset($_POST['action']) && $_POST['action'] === 'add_comment') {
    $content = trim($_POST['comment']);

    if (!empty($content)) {
        $sectionObj->addComment($sectionId, $loggedUserId, $content);
        header("Location: forum_section.php?id=$sectionId");
        exit;
    } else {
        echo "<p style='color:red;'>Comment cannot be empty.</p>";
    }
}

if (isset($_GET['delete_comment']) && isset($_SESSION['user'])) {
    $commentIdToDelete = (int)$_GET['delete_comment'];

    $comments = $sectionObj->getCommentsBySection($sectionId);
    foreach ($comments as $comment) {
        if ($comment['id'] == $commentIdToDelete && $comment['user_id'] == $loggedUserId) {
            $sectionObj->deleteComment($commentIdToDelete);
            header("Location: forum_section.php?id=$sectionId");
            exit;
        }
    }

    echo "<p style='color:red;'>You cannot delete this comment.</p>";
}

$comments = $sectionObj->getCommentsBySection($sectionId);

include 'kokosy/header.php';
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

                <strong><?php echo htmlspecialchars($comment['username']); ?></strong>&nbsp;wrote on <?php echo $comment['created_at']; ?>

            </div>
            <p style="margin-left: 50px;"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>

            <?php if (isset($_SESSION['user']) && $comment['user_id'] == $loggedUserId): ?>
                <div style="margin-left: 50px;">
                    <a href='forum_section.php?id=<?php echo $sectionId; ?>&delete_comment=<?php echo $comment['id']; ?>' style='color:red;'>Delete Comment</a> |
                    <a href='edit_comment.php?section_id=<?php echo $sectionId; ?>&comment_id=<?php echo $comment['id']; ?>'>Edit Comment</a>
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
        <input type="hidden" name="action" value="add_comment">
        <textarea name="comment" rows="4" cols="108" required></textarea><br>
        <button type="submit">Post Comment</button>
    </form>
<?php else: ?>
    <p><a href="index.php">Login</a> to add a comment.</p>
<?php endif; ?>

<?php include 'kokosy/footer.php'; ?>
