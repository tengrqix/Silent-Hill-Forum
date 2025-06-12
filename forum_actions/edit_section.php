<?php
session_start();
require_once '../classes/ForumSection.php';
require_once '../classes/Database.php';

if (!isset($_SESSION['user'])) {
    echo "<div style='color: red; text-align: center;'>You must be logged in to edit a section. <a href='../index.php'>Login here</a>.</div>";
    exit;
}

if (!isset($_GET['section_id'])) {
    echo "Section not found.";
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

if (!$section || $section['user_id'] != $loggedUserId) {
    echo "<p style='color:red;'>You cannot edit this section.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("UPDATE forum_sections SET name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $description, $sectionId);

    if ($stmt->execute()) {
        header("Location: ../forum.php");
        exit;
    } else {
        echo "<p style='color:red;'>Error updating section.</p>";
    }
}

include '../partials/header.php';
?>

<h2>Edit Section</h2>
<form action="edit_section.php?section_id=<?php echo $sectionId; ?>" method="POST">
    <label for="name">Section Name:</label>
    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($section['name']); ?>">

    <label for="description">Description:</label>
    <textarea id="description" name="description"><?php echo htmlspecialchars($section['description']); ?></textarea>

    <button type="submit">Update Section</button>
</form>

<?php include '../partials/footer.php'; ?>
