<?php
session_start();
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    echo "<div style='color: red; text-align: center;'>You must be logged in to view your profile. <a href='index.php'>Login here</a>.</div>";
    exit;
}

$conn = (new Database())->connect();
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$loggedUserId = $userData['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (isset($_POST['new_username']) && !empty($_POST['new_username'])) {
        $newUsername = trim($_POST['new_username']);


        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $newUsername, $loggedUserId);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows == 0) {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $newUsername, $loggedUserId);
            $stmt->execute();

            $_SESSION['user'] = $newUsername;
            echo "<p style='color:green;'>Username updated successfully!</p>";
        } else {
            echo "<p style='color:red;'>Username already taken!</p>";
        }
    }


    if (isset($_POST['new_email']) && !empty($_POST['new_email'])) {
        $newEmail = trim($_POST['new_email']);
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $newEmail, $loggedUserId);
        $stmt->execute();

        echo "<p style='color:green;'>Email updated successfully!</p>";
    }

   
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $newPassword = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPassword, $loggedUserId);
        $stmt->execute();

        echo "<p style='color:green;'>Password updated successfully!</p>";
    }

    
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "images/profile_pics/";
        $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                
                $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $targetFile, $loggedUserId);
                $stmt->execute();

                echo "<p style='color:green;'>Profile picture updated successfully!</p>";
            } else {
                echo "<p style='color:red;'>Error uploading profile picture.</p>";
            }
        } else {
            echo "<p style='color:red;'>File is not an image.</p>";
        }
    }

   
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $loggedUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
}

include 'partials/header.php';
?>

<h2>My Profile</h2>

<p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
<p><strong>Profile Picture:</strong><br>
<?php if (!empty($userData['profile_picture'])): ?>
    <img src="<?php echo htmlspecialchars($userData['profile_picture']); ?>" alt="Profile Picture" width="150" style="border: 1px solid #ccc; padding: 5px;">
<?php else: ?>
    <p>No profile picture uploaded.</p>
<?php endif; ?>
</p>

<h3>Update Profile</h3>
<form method="POST" enctype="multipart/form-data">
    <label>New Username:</label><br>
    <input type="text" name="new_username" value="<?php echo htmlspecialchars($userData['username']); ?>"><br><br>

    <label>New Email:</label><br>
    <input type="email" name="new_email" value="<?php echo htmlspecialchars($userData['email']); ?>"><br><br>

    <label>New Password:</label><br>
    <input type="password" name="new_password"><br><br>

    <label>Upload Profile Picture:</label><br>
    <input type="file" name="profile_picture" accept="image/*"><br><br>

    <button type="submit">Save Changes</button>
</form>

<p><a href="forum.php">Back to Forum</a></p>

<?php include 'partials/footer.php'; ?>
