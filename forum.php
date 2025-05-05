<?php
session_start();  
if (!isset($_SESSION['user'])) {
    echo "<div style='color: red; text-align: center;'>You must be logged in to participate in the forum. <a href='index.php'>Login here</a>.</div>";
} else {
    include 'kokosy/header.php';  
    require_once 'classes/ForumSection.php';

    $sectionObj = new ForumSection();
    $sections = $sectionObj->getAllSections();

    echo "<div id='forum-content'>";

    echo "<div id='create-section-form'>";
    echo "<h3>Create a New Forum Section</h3>";
    echo "<form action='forum.php' method='POST'>";
    echo "<label for='name'>Section Name:</label>";
    echo "<input type='text' id='name' name='name' required placeholder='Enter section name'>";
    echo "<label for='description'>Description:</label>";
    echo "<textarea id='description' name='description' placeholder='Enter description'></textarea>";
    echo "<button type='submit'>Create Section</button>";
    echo "</form>";
    echo "</div>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];

        $conn = new mysqli("localhost", "root", "", "silent_hill_forum");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $query = "INSERT INTO forum_sections (name, description) VALUES ('$name', '$description')";
        if ($conn->query($query)) {
            header("Location: forum.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    echo "<h2>Forum Sections</h2>";

    foreach ($sections as $section) {
        echo "<div class='forum-section'>";
        echo "<h3><a href='forum_section.php?id=" . $section['id'] . "'>" . htmlspecialchars($section['name']) . "</a></h3>";
        echo "<p>" . htmlspecialchars($section['description']) . "</p>";
        echo "</div>";
    }

    echo "</div>"; 

    include 'kokosy/footer.php';  
}
?>
