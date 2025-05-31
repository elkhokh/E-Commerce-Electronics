<?php

namespace App;

use PDO;

class Order {
    private int $id;
    private ?int $user_id;
    private float $total_amount;
    private string $status;
    private string $shipping_address;
    private string $payment_method;
    private string $created_at;
    private array $items = [];

    public function __construct() {
    }


    public static function create(PDO $db, int $user_id, float $total_amount, string $shipping_address, string $payment_method): ?Order {
        try {
            $db->beginTransaction();

            $query = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) 
                     VALUES (:user_id, :total_amount, :shipping_address, :payment_method)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
                'total_amount' => $total_amount,
                'shipping_address' => $shipping_address,
                'payment_method' => $payment_method
            ]);

            $order = new Order();
            $order->id = $db->lastInsertId();
            $order->user_id = $user_id;
            $order->total_amount = $total_amount;
            $order->status = 'pending';
            $order->shipping_address = $shipping_address;
            $order->payment_method = $payment_method;

            $db->commit();
            return $order;
        } catch (\PDOException $e) {
            $db->rollBack();
            return null;
        }
    }


    public function addItem(PDO $db, int $product_id, int $quantity, float $price): bool {
        try {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                     VALUES (:order_id, :product_id, :quantity, :price)";
            
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'order_id' => $this->id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // الحصول على تفاصيل الطلب
    public static function findById(PDO $db, int $id): ?Order {
        try {
            $query = "SELECT * FROM orders WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id]);
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $order = new Order();
                $order->id = $row['id'];
                $order->user_id = $row['user_id'];
                $order->total_amount = $row['total_amount'];
                $order->status = $row['status'];
                $order->shipping_address = $row['shipping_address'];
                $order->payment_method = $row['payment_method'];
                $order->created_at = $row['created_at'];
                $order->items = $order->getItems($db);
                
                return $order;
            }
            return null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    // الحصول على جميع طلبات المستخدم
    public static function getOrdersByUserId(PDO $db, int $user_id): array {
        try {
            $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute(['user_id' => $user_id]);
            
            $orders = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $order = new Order();
                $order->id = $row['id'];
                $order->user_id = $row['user_id'];
                $order->total_amount = $row['total_amount'];
                $order->status = $row['status'];
                $order->shipping_address = $row['shipping_address'];
                $order->payment_method = $row['payment_method'];
                $order->created_at = $row['created_at'];
                $order->items = $order->getItems($db);
                $orders[] = $order;
            }
            return $orders;
        } catch (\PDOException $e) {
            return [];
        }
    }

    // الحصول على منتجات الطلب
    public function getItems(PDO $db): array {
        try {
            $query = "SELECT oi.*, p.name, p.main_image 
                     FROM order_items oi 
                     JOIN products p ON oi.product_id = p.id 
                     WHERE oi.order_id = :order_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['order_id' => $this->id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    // تحديث حالة الطلب
    public function updateStatus(PDO $db, string $status): bool {
        try {
            $query = "UPDATE orders SET status = :status WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'status' => $status,
                'id' => $this->id
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): ?int {
        return $this->user_id;
    }

    public function getTotalAmount(): float {
        return $this->total_amount;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getShippingAddress(): string {
        return $this->shipping_address;
    }

    public function getPaymentMethod(): string {
        return $this->payment_method;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function getItemsList(): array {
        return $this->items;
    }

    // الحصول على معلومات المستخدم
    public function getUser(PDO $db): ?array {
        try {
            $query = "SELECT * FROM users WHERE id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['user_id' => $this->user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function setUserId(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function setTotalAmount(float $total_amount): void {
        $this->total_amount = $total_amount;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setShippingAddress(string $shipping_address): void {
        $this->shipping_address = $shipping_address;
    }

    public function setPaymentMethod(string $payment_method): void {
        $this->payment_method = $payment_method;
    }
}
