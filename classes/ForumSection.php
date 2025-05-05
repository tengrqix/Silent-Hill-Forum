<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  
}

class ForumSection {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "silent_hill_forum");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
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


    public function getPostsBySection($sectionId) {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE section_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $sectionId); 
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        return $result->fetch_all(MYSQLI_ASSOC); 
    }
}