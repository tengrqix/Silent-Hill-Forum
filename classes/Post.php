<?php
require_once 'Database.php';

class Post {
    // Získať príspevky podľa ID sekcie
    public static function getBySectionId(int $sectionId): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE section_id = ? ORDER BY created_at DESC");
        $stmt->execute([$sectionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Uistíme sa, že použijeme FETCH_ASSOC
    }

    // Pridať nový príspevok
    public static function add(int $sectionId, int $userId, string $content): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO posts (section_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$sectionId, $userId, $content]);
    }


    public static function addComment(int $postId, int $userId, string $content): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $userId, $content]);
    }


    public static function getComments(int $postId): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT c.content, u.username, c.created_at 
                               FROM comments c
                               JOIN users u ON c.user_id = u.id
                               WHERE c.post_id = ?
                               ORDER BY c.created_at DESC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }
}
?>
