<?php

namespace App;
use App\Traits\MangesFiles;

use PDO;
use PDOException;

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
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
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
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public static function getAll(PDO $db, int $limit = 0, int $offset = 0): array {
        try {
            $query = "SELECT b.*, u.name as author_name 
                     FROM blogs b 
                     JOIN users u ON b.user_id = u.id 
                     ORDER BY b.created_at ASC";
            
            if ($limit > 0) {
                $query .= " LIMIT :limit";
                if ($offset > 0) {
                    $query .= " OFFSET :offset";
                }
            }

            $stmt = $db->prepare($query);
            
            if ($limit > 0) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                if ($offset > 0) {
                    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public static function getLatest(PDO $db, int $limit = 3,$offset=0): array {
        try {
            $query = "SELECT b.*, u.name as author_name 
                     FROM blogs b 
                     JOIN users u ON b.user_id = u.id 
                     ORDER BY b.created_at DESC 
                     LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        }  catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
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
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

   

    public function delete(PDO $db): bool {
        try {
            $query = "DELETE FROM blogs WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['id' => $this->id]);
        }  catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
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
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public function getCommentCount(PDO $db): int {
        try {
            $query = "SELECT COUNT(*) FROM blog_comments WHERE blog_id = :blog_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['blog_id' => $this->id]);
            return (int) $stmt->fetchColumn();
        }catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

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
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public static function deleteComment(PDO $db, int $comment_id): bool {
        try {
            $query = "DELETE FROM blog_comments 
                     WHERE id = :comment_id ";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'comment_id' => $comment_id
            ]);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public static function getComment(PDO $db, int $comment_id): ?array {
        try {
            $query = "SELECT bc.*, u.name as author_name 
                     FROM blog_comments bc 
                     JOIN users u ON bc.user_id = u.id 
                     WHERE bc.id = :comment_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['comment_id' => $comment_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

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
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

        public static function find_by_name(PDO $db, string $title): array
    {
        try {
            $query = "SELECT * FROM blogs 
                     WHERE   title LIKE :name 
                     ORDER BY created_at DESC";
            
            $stmt = $db->prepare($query);
            $searchTerm = "%{$title}%";
            $stmt->bindParam(':name', $searchTerm);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\\Blogs');
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
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
    public static function getBlogsCount(PDO $db): int
    {
        try {
            $query = "SELECT COUNT(*) FROM blogs";
            $stmt = $db->prepare($query);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return 0;
        }
    }

    public function update(PDO $db): bool {
        try {
            $query = "UPDATE blogs SET 
                      title = :title,
                      content = :content,
                      image = :image
                      WHERE id = :id";
            
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'title' => $this->title,
                'content' => $this->content,
                'image' => $this->image,
                'id' => $this->id
            ]);
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }


}
