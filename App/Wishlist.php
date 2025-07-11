<?php

namespace App;

use PDO;
use PDOException;

class Wishlist {
    private int $id;
    private int $user_id;
    private int $product_id;
    private string $created_at;

    public function __construct() {
    }

  
    public static function add(PDO $db, int $user_id, int $product_id): bool {
        try {
            $query = "INSERT INTO wishlist (user_id, product_id) 
                     VALUES (:user_id, :product_id)";
            
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $product_id
            ]);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

   
    public static function remove(PDO $db, int $user_id, int $product_id): bool {
        try {
            $query = "DELETE FROM wishlist 
                     WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $product_id
            ]);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

   
   
    public static function clear(PDO $db, int $user_id): bool {
        try {
            $query = "DELETE FROM wishlist WHERE user_id = :user_id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['user_id' => $user_id]);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function getProductId(): int {
        return $this->product_id;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public static function getUserWishlist(PDO $db, int $user_id): array
    {
        try {
            $query = "SELECT p.*, w.created_at as added_date 
                     FROM wishlist w 
                     JOIN products p ON w.product_id = p.id 
                     WHERE w.user_id = :user_id 
                     ORDER BY w.created_at DESC";
            
            $stmt = $db->prepare($query);
            $stmt->execute(['user_id' => $user_id]);
            
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\Product');
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

}
