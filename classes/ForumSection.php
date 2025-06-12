<?php
require_once 'Database.php';

class ForumSection
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function getAllSections(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM forum_sections ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSectionById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM forum_sections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }


    public function addSection(string $name, string $description, int $userId): void
    {
        $stmt = $this->conn->prepare("INSERT INTO forum_sections (name, description, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $description, $userId);
        $stmt->execute();
    }


    public function deleteSection(int $id): void
    {
        $stmt = $this->conn->prepare("DELETE FROM forum_sections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function getPostsBySection(int $sectionId): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE section_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $sectionId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
