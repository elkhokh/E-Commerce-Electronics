<?php

namespace App;

use PDO;

class CartItem {
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $quantity;
    private ?Product $product = null;

    public function __construct() {
    }

  
    public static function create(PDO $db, int $user_id, int $product_id, int $quantity = 1): ?CartItem {
        try {
  
            $product = Product::findById($db, $product_id);
            if (!$product) {
                return null;
            }

            if ($product->getQuantity() < $quantity) {
                return null;
            }

            $query = "INSERT INTO carts (user_id, product_id, quantity) 
                     VALUES (:user_id, :product_id, :quantity)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $quantity
            ]);

            $cartItem = new CartItem();
            $cartItem->id = $db->lastInsertId();
            $cartItem->user_id = $user_id;
            $cartItem->product_id = $product_id;
            $cartItem->quantity = $quantity;
            $cartItem->product = $product;

            return $cartItem;
        } catch (\PDOException $e) {
            return null;
        }
    }

  
    public static function getUserCart(PDO $db, int $user_id): ?array {
        try {
            $query = "SELECT * FROM carts WHERE user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['user_id' => $user_id]);
            
            $cartItems = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cartItem = new CartItem();
                $cartItem->id = $row['id'];
                $cartItem->user_id = $row['user_id'];
                $cartItem->product_id = $row['product_id'];
                $cartItem->quantity = $row['quantity'];
                $cartItem->product = Product::findById($db, $row['product_id']);
                $cartItems[] = $cartItem;
            }
            
            return $cartItems;
        } catch (\PDOException $e) {
            return null;
        }
    }


    public function updateQuantity(PDO $db, int $quantity): bool {
        try {
  
            if ($this->product->getQuantity() < $quantity) {
                return false;
            }

            $query = "UPDATE carts SET quantity = :quantity WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'quantity' => $quantity,
                'id' => $this->id
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

   
    public function delete(PDO $db): bool {
        try {
            $query = "DELETE FROM carts WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['id' => $this->id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

      public function getTotalPrice(): float {
        if ($this->product) {
            return $this->product->getFinalPrice() * $this->quantity;
        }
        return 0;
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

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getProduct(): ?Product {
        return $this->product;
    }
}
