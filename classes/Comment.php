<?php
require_once 'Database.php';

class Comment {

    public static function addComment(int $sectionId, int $userId, string $content): void {
        $conn = (new Database())->connect();
        $stmt = $conn->prepare("INSERT INTO comments (section_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sectionId, $userId, $content);
        $stmt->execute();
    }

    public static function getCommentsBySection(int $sectionId): array {
        $conn = (new Database())->connect();
        $stmt = $conn->prepare("SELECT c.id, c.content, c.created_at, c.user_id, u.username, u.profile_picture
                               FROM comments c
                               JOIN users u ON c.user_id = u.id
                               WHERE c.section_id = ?
                               ORDER BY c.created_at DESC");
        $stmt->bind_param("i", $sectionId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getCommentById(int $commentId): ?array {
        $conn = (new Database())->connect();
        $stmt = $conn->prepare("SELECT c.id, c.content, c.created_at, c.user_id, c.section_id, u.username
                               FROM comments c
                               JOIN users u ON c.user_id = u.id
                               WHERE c.id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();
        return $comment ?: null;
    }

    public static function updateComment(int $commentId, string $content): void {
        $conn = (new Database())->connect();
        $stmt = $conn->prepare("UPDATE comments SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $commentId);
        $stmt->execute();
    }

    public static function deleteComment(int $commentId): void {
        $conn = (new Database())->connect();
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
    }

}
