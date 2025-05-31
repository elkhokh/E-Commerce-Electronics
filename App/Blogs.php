<?php

namespace App;

use PDO;

class Blogs {
    private int $id;
    private int $user_id;
    private string $title;
    private string $content;
    private string $image;
    private string $created_at;

    public function __construct() {
    }

    public static function create(PDO $db, int $user_id, string $title, string $content, string $image): bool {
        try {
            $query = "INSERT INTO blogs (user_id, title, content, image) 
                     VALUES (:user_id, :title, :content, :image)";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'image' => $image
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public static function findById(PDO $db, int $id): ?Blogs {
        try {
            $query = "SELECT * FROM blogs WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id]);
            $blog = $stmt->fetchObject(self::class);
            return $blog ?: null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public static function getAll(PDO $db): array {
        try {
            $query = "SELECT b.*, u.name as author_name 
                     FROM blogs b 
                     JOIN users u ON b.user_id = u.id 
                     ORDER BY b.created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch (\PDOException $e) {
            return [];
        }
    }

    public static function getLatest(PDO $db, int $limit = 3): array {
        try {
            $query = "SELECT b.*, u.name as author_name 
                     FROM blogs b 
                     JOIN users u ON b.user_id = u.id 
                     ORDER BY b.created_at DESC 
                     LIMIT :limit";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch (\PDOException $e) {
            return [];
        }
    }
    public static function getRandomBlogs(PDO $db, int $limit = 3): array {
        try {
            $query = "SELECT b.*, u.name as author_name 
                     FROM blogs b 
                     JOIN users u ON b.user_id = u.id 
                     ORDER BY RAND() 
                     LIMIT :limit";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function update(PDO $db): bool {
        try {
            $query = "UPDATE blogs 
                     SET title = :title, content = :content, image = :image 
                     WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'id' => $this->id,
                'title' => $this->title,
                'content' => $this->content,
                'image' => $this->image
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete(PDO $db): bool {
        try {
            $query = "DELETE FROM blogs WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['id' => $this->id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getComments(PDO $db): array {
        try {
            $query = "SELECT bc.*, u.name as author_name 
                     FROM blog_comments bc 
                     JOIN users u ON bc.user_id = u.id 
                     WHERE bc.blog_id = :blog_id 
                     ORDER BY bc.created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute(['blog_id' => $this->id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function getCommentCount(PDO $db): int {
        try {
            $query = "SELECT COUNT(*) FROM blog_comments WHERE blog_id = :blog_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['blog_id' => $this->id]);
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    /**
     * إضافة تعليق جديد على المدونة
     */
    public static function addComment(PDO $db, int $blog_id, int $user_id, string $comment): bool {
        try {
            $query = "INSERT INTO blog_comments (blog_id, user_id, comment) 
                     VALUES (:blog_id, :user_id, :comment)";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'blog_id' => $blog_id,
                'user_id' => $user_id,
                'comment' => $comment
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * حذف تعليق من المدونة
     */
    public static function deleteComment(PDO $db, int $comment_id, int $user_id): bool {
        try {
            // التحقق من أن المستخدم هو صاحب التعليق
            $query = "DELETE FROM blog_comments 
                     WHERE id = :comment_id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'comment_id' => $comment_id,
                'user_id' => $user_id
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * الحصول على تعليق محدد
     */
    public static function getComment(PDO $db, int $comment_id): ?array {
        try {
            $query = "SELECT bc.*, u.name as author_name 
                     FROM blog_comments bc 
                     JOIN users u ON bc.user_id = u.id 
                     WHERE bc.id = :comment_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['comment_id' => $comment_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    /**
     * تحديث تعليق
     */
    public static function updateComment(PDO $db, int $comment_id, int $user_id, string $comment): bool {
        try {
            $query = "UPDATE blog_comments 
                     SET comment = :comment 
                     WHERE id = :comment_id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'comment_id' => $comment_id,
                'user_id' => $user_id,
                'comment' => $comment
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * الحصول على جميع التعليقات لمدونة معينة مع معلومات المستخدم
     */
    public static function getAllComments(PDO $db, int $blog_id): array {
        try {
            $query = "SELECT bc.*, u.name as author_name, u.image as author_image 
                     FROM blog_comments bc 
                     JOIN users u ON bc.user_id = u.id 
                     WHERE bc.blog_id = :blog_id 
                     ORDER BY bc.created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute(['blog_id' => $blog_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getImage(): string {
        return $this->image;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    // Setters
    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function setImage(string $image): void {
        $this->image = $image;
    }
}
