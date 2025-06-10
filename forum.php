<?php
session_start();
require_once 'classes/ForumSection.php';

if (!isset($_SESSION['user'])) {
    echo "<div style='color: red; text-align: center;'>You must be logged in to participate in the forum. <a href='index.php'>Login here</a>.</div>";
    exit;
}

$sectionObj = new ForumSection();

$conn = (new Database())->connect();
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$loggedUserId = $userData['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_section') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (!empty($name)) {
        $sectionObj->addSection($name, $description, $loggedUserId);
        header("Location: forum.php");
        exit;
    } else {
        echo "<p style='color:red;'>Section name cannot be empty.</p>";
    }
}


if (isset($_GET['delete_section'])) {
    $sectionIdToDelete = (int)$_GET['delete_section'];

    $section = $sectionObj->getSectionById($sectionIdToDelete);

    if ($section && $section['user_id'] == $loggedUserId) {
        $sectionObj->deleteSection($sectionIdToDelete);
        header("Location: forum.php");
        exit;
    } else {
        echo "<p style='color:red;'>You cannot delete this section.</p>";
    }
}


$stmt = $conn->prepare("SELECT fs.*, u.username, u.profile_picture 
                        FROM forum_sections fs
                        JOIN users u ON fs.user_id = u.id
                        ORDER BY fs.id DESC");
$stmt->execute();
$result = $stmt->get_result();
$sections = $result->fetch_all(MYSQLI_ASSOC);

include 'kokosy/header.php';
?>

<div id="forum-content">

    <div id="create-section-form">
        <h3>Create a New Forum Section</h3>
        <form action="forum.php" method="POST">
            <input type="hidden" name="action" value="create_section">
            <label for="name">Section Name:</label>
            <input type="text" id="name" name="name" required placeholder="Enter section name">
            <label for="description">Description:</label>
            <textarea id="description" name="description" placeholder="Enter description"></textarea>
            <button type="submit">Create Section</button>
        </form>
    </div>

    <h2>Forum Sections</h2>

    <?php foreach ($sections as $section): ?>
        <div class='forum-section'>
            <div style="display: flex; align-items: center;">
                <?php if (!empty($section['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($section['profile_picture']); ?>" alt="Profile Pic" width="40" height="40" style="border-radius:50%; margin-right:10px;">
                <?php else: ?>
                    <img src="images/profile_pics/default.png" alt="Profile Pic" width="40" height="40" style="border-radius:50%; margin-right:10px;">
                <?php endif; ?>

                <h3 style="margin: 0;">
                    <a href='forum_section.php?id=<?php echo $section['id']; ?>'>
                        <?php echo htmlspecialchars($section['name']); ?>
                    </a>
                </h3>
            </div>
            <p><?php echo nl2br(htmlspecialchars($section['description'])); ?></p>
            <p><em>Created by <?php echo htmlspecialchars($section['username']); ?></em></p>

            <?php if ($section['user_id'] == $loggedUserId): ?>
                <div>
                    <a href='forum.php?delete_section=<?php echo $section['id']; ?>' style='color:red;'>Delete Section</a> |
                    <a href='edit_section.php?section_id=<?php echo $section['id']; ?>'>Edit Section</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</div>

<?php include 'kokosy/footer.php'; ?>
