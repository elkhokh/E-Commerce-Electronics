<?php

namespace App;

use PDO;

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
        } catch (\PDOException $e) {
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
        } catch (\PDOException $e) {
            return false;
        }
    }

   
   
    public static function clear(PDO $db, int $user_id): bool {
        try {
            $query = "DELETE FROM wishlist WHERE user_id = :user_id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['user_id' => $user_id]);
        } catch (\PDOException $e) {
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
}
