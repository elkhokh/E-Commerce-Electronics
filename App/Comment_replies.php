<?php

namespace App;

use PDO;
use PDOException;

class Comment_replies
{
    private int $id;
    private int $comment_id;
    private int $user_id;
    private string $reply;
    private string $created_at;

    public function __construct(int $id, int $comment_id, int $user_id, string $reply, string $created_at)
    {
        $this->id = $id;
        $this->comment_id = $comment_id;
        $this->user_id = $user_id;
        $this->reply = $reply;
        $this->created_at = $created_at;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getCommentId(): int
    {
        return $this->comment_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getReply(): string
    {
        return $this->reply;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }


    public static function create(PDO $pdo, int $comment_id, int $user_id, string $reply): ?Comment_replies
    {
        try {
            $stmt = $pdo->prepare("INSERT INTO comment_replies (comment_id, user_id, reply) VALUES (:comment_id, :user_id, :reply)");
            $stmt->bindParam(":comment_id", $comment_id);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":reply", $reply);
            
            if ($stmt->execute()) {
                $id = (int) $pdo->lastInsertId();
                return new self($id, $comment_id, $user_id, $reply, date('Y-m-d H:i:s'));
            }
            return null;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }


    public static function getRepliesForComment(PDO $pdo, int $comment_id): array
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM comment_replies WHERE comment_id = :comment_id ORDER BY created_at ASC");
            $stmt->bindParam(":comment_id", $comment_id);
            $stmt->execute();
            
            $replies = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $replies[] = new self(
                    $row['id'],
                    $row['comment_id'],
                    $row['user_id'],
                    $row['reply'],
                    $row['created_at']
                );
            }
            return $replies;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }


    public static function delete(PDO $pdo, int $reply_id): bool
    {
        try {
            $stmt = $pdo->prepare("DELETE FROM comment_replies WHERE id = :id");
            $stmt->bindParam(":id", $reply_id);
            return $stmt->execute();
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }


    public static function findById(PDO $pdo, int $reply_id): ?Comment_replies
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM comment_replies WHERE id = :id");
            $stmt->bindParam(":id", $reply_id);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new self(
                    $row['id'],
                    $row['comment_id'],
                    $row['user_id'],
                    $row['reply'],
                    $row['created_at']
                );
            }
            return null;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }


    public static function getReplyCount(PDO $pdo, int $comment_id): int
    {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM comment_replies WHERE comment_id = :comment_id");
            $stmt->bindParam(":comment_id", $comment_id);
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
}
