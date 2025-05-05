<?php
session_start();
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    echo "Please <a href='index.php'>login</a> to create a section.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $conn = (new Database())->connect();
    $stmt = $conn->prepare("INSERT INTO forum_sections (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();

    header("Location: forum.php");
    exit;
}
?>

<h2>Create New Forum Section</h2>
<form action="create_section.php" method="POST">
    <label>Section Name:</label>
    <input type="text" name="name" required><br>
    <label>Description:</label>
    <textarea name="description"></textarea><br>
    <button type="submit">Create</button>
</form>
