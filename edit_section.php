<?php
session_start();
require_once 'classes/ForumSection.php';

if (!isset($_GET['section_id'])) {
    echo "Invalid request.";
    exit;
}

$sectionId = (int)$_GET['section_id'];

$sectionObj = new ForumSection();


$conn = (new Database())->connect();
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$loggedUserId = $userData['id'];

$section = $sectionObj->getSectionById($sectionId);

if (!$section) {
    echo "Section not found.";
    exit;
}

if ($section['user_id'] != $loggedUserId) {
    echo "You cannot edit this section.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim($_POST['name']);
    $newDescription = trim($_POST['description']);
    if (!empty($newName)) {
        $sectionObj->updateSection($sectionId, $newName, $newDescription);
        header("Location: forum.php");
        exit;
    } else {
        echo "<p style='color:red;'>Section name cannot be empty.</p>";
    }
}

include 'kokosy/header.php';
?>

<h2>Edit Section</h2>
<form method="POST">
    <label>Section Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($section['name']); ?>" required><br>
    <label>Description:</label><br>
    <textarea name="description" rows="4" cols="100"><?php echo htmlspecialchars($section['description']); ?></textarea><br>
    <button type="submit">Save Changes</button>
</form>
<p><a href="forum.php">Back to Forum</a></p>

<?php include 'kokosy/footer.php'; ?>
