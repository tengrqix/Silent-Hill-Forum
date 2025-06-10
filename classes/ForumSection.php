<?php
require_once 'Database.php';

class ForumSection {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getAllSections() {
        $result = $this->conn->query("SELECT * FROM forum_sections");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSectionById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM forum_sections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addSection($name, $description, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO forum_sections (name, description, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $description, $userId);
        $stmt->execute();
    }

    public function updateSection($id, $name, $description) {
        $stmt = $this->conn->prepare("UPDATE forum_sections SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        $stmt->execute();
    }

    public function deleteSection($id) {
        $stmt = $this->conn->prepare("DELETE FROM forum_sections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function addComment($sectionId, $userId, $content) {
        $stmt = $this->conn->prepare("INSERT INTO comments (section_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sectionId, $userId, $content);
        $stmt->execute();
    }

    public function getCommentsBySection($sectionId) {
        $stmt = $this->conn->prepare("SELECT c.id, c.content, c.created_at, u.username, c.user_id, u.profile_picture
                                      FROM comments c
                                      JOIN users u ON c.user_id = u.id
                                      WHERE c.section_id = ?
                                      ORDER BY c.created_at DESC");
        $stmt->bind_param("i", $sectionId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateComment($commentId, $content) {
        $stmt = $this->conn->prepare("UPDATE comments SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $commentId);
        $stmt->execute();
    }

    public function deleteComment($commentId) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
    }
}
?>
