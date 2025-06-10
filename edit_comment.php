<?php
session_start();
require_once 'classes/ForumSection.php';

if (!isset($_GET['comment_id']) || !isset($_GET['section_id'])) {
    echo "Invalid request.";
    exit;
}

$commentId = (int)$_GET['comment_id'];
$sectionId = (int)$_GET['section_id'];

$sectionObj = new ForumSection();


$conn = (new Database())->connect();
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$loggedUserId = $userData['id'];


$comments = $sectionObj->getCommentsBySection($sectionId);
$commentData = null;
foreach ($comments as $comment) {
    if ($comment['id'] == $commentId) {
        $commentData = $comment;
        break;
    }
}

if (!$commentData) {
    echo "Comment not found.";
    exit;
}

if ($commentData['user_id'] != $loggedUserId) {
    echo "You cannot edit this comment.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newContent = trim($_POST['content']);
    if (!empty($newContent)) {
        $sectionObj->updateComment($commentId, $newContent);
        header("Location: forum_section.php?id=$sectionId");
        exit;
    } else {
        echo "<p style='color:red;'>Comment cannot be empty.</p>";
    }
}

include 'kokosy/header.php';
?>

<h2>Edit Comment</h2>
<form method="POST">
    <textarea name="content" rows="4" cols="100" required><?php echo htmlspecialchars($commentData['content']); ?></textarea><br>
    <button type="submit">Save Changes</button>
</form>
<p><a href="forum_section.php?id=<?php echo $sectionId; ?>">Back to Section</a></p>

<?php include 'kokosy/footer.php'; ?>
