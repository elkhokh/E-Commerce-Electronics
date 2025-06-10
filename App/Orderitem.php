<?php

namespace App;

use PDO;
use PDOException;

class Orderitem {
    private int $id;
    private int $order_id;
    private int $product_id;
    private int $quantity;
    private float $price;
    private string $created_at;
    private ?Product $product = null;

    public function __construct() {
    }

    public static function create(PDO $db, int $order_id, int $product_id, int $quantity, float $price): ?Orderitem {
        try {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                     VALUES (:order_id, :product_id, :quantity, :price)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'order_id' => $order_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price
            ]);

            $orderItem = new Orderitem();
            $orderItem->id = $db->lastInsertId();
            $orderItem->order_id = $order_id;
            $orderItem->product_id = $product_id;
            $orderItem->quantity = $quantity;
            $orderItem->price = $price;

            return $orderItem;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public static function getItemsByOrderId(PDO $db, int $order_id): array {
        try {
            $query = "SELECT * FROM order_items WHERE order_id = :order_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['order_id' => $order_id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public function updateQuantity(PDO $db, int $quantity): bool {
        try {
            $query = "UPDATE order_items SET quantity = :quantity WHERE id = :id";
            $stmt = $db->prepare($query);
            $result = $stmt->execute([
                'quantity' => $quantity,
                'id' => $this->id
            ]);

            if ($result) {
                $this->quantity = $quantity;
            }
            return $result;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public function delete(PDO $db): bool {
        try {
            $query = "DELETE FROM order_items WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['id' => $this->id]);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public function getTotalPrice(): float {
        return $this->quantity * $this->price;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getOrderId(): int {
        return $this->order_id;
    }

    public function getProductId(): int {
        return $this->product_id;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }
}
