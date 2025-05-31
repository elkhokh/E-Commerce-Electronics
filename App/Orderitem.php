<?php

namespace App;

use PDO;

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
        } catch (\PDOException $e) {
            return null;
        }
    }


    public static function findById(PDO $db, int $id): ?Orderitem {
        try {
            $query = "SELECT * FROM order_items WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id]);
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $orderItem = new Orderitem();
                $orderItem->id = $row['id'];
                $orderItem->order_id = $row['order_id'];
                $orderItem->product_id = $row['product_id'];
                $orderItem->quantity = $row['quantity'];
                $orderItem->price = $row['price'];
                $orderItem->created_at = $row['created_at'];
                
                return $orderItem;
            }
            return null;
        } catch (\PDOException $e) {
            return null;
        }
    }


    public static function getItemsByOrderId(PDO $db, int $order_id): array {
        try {
            $query = "SELECT * FROM order_items WHERE order_id = :order_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['order_id' => $order_id]);
            
            $items = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $orderItem = new Orderitem();
                $orderItem->id = $row['id'];
                $orderItem->order_id = $row['order_id'];
                $orderItem->product_id = $row['product_id'];
                $orderItem->quantity = $row['quantity'];
                $orderItem->price = $row['price'];
                $orderItem->created_at = $row['created_at'];
                $items[] = $orderItem;
            }
            return $items;
        } catch (\PDOException $e) {
            return [];
        }
    }


    public function updateQuantity(PDO $db, int $quantity): bool {
        try {
            $query = "UPDATE order_items SET quantity = :quantity WHERE id = :id";
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
            $query = "DELETE FROM order_items WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['id' => $this->id]);
        } catch (\PDOException $e) {
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

    public function getProduct(PDO $db): ?Product {
        if ($this->product === null) {
            $this->product = Product::findById($db, $this->product_id);
        }
        return $this->product;
    }

    public function getOrder(PDO $db): ?Order {
        return Order::findById($db, $this->order_id);
    }
}
