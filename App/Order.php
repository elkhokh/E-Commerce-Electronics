<?php

namespace App;

use PDO;
use PDOException;

class Order {
    private int $id;
    private ?int $user_id;
    private float $total_amount;
    private string $status;
    private string $shipping_address;
    private string $payment_method;
    private string $created_at;
    private array $items = [];
    private string $phone;

    public function __construct() {
    }


    public static function create(PDO $db, int $user_id, float $total_amount, string $shipping_address, string $payment_method, string $phone): ?Order {
        try {
            $db->beginTransaction();

            $query = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, phone) 
                     VALUES (:user_id, :total_amount, :shipping_address, :payment_method, :phone)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
                'total_amount' => $total_amount,
                'shipping_address' => $shipping_address,
                'payment_method' => $payment_method,
                'phone' => $phone
            ]);

            $order = new Order();
            $order->id = $db->lastInsertId();
            $order->user_id = $user_id;
            $order->total_amount = $total_amount;
            $order->status = 'pending';
            $order->shipping_address = $shipping_address;
            $order->payment_method = $payment_method;
            $order->phone = $phone;

            $db->commit();
            return $order;
        } catch(PDOException $ex){
            $db->rollBack();
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
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
        }catch(PDOException $ex){
                if(file_exists('Config/log.log')){
                    $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                    file_put_contents('Config/log.log', $error, FILE_APPEND);
                }
                return false;
            }
    }


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
                $order->phone = $row['phone'] ?? '';
                $order->items = $order->getItems($db);
                
                return $order;
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
                $order->phone = $row['phone'] ?? '';
                $order->items = $order->getItems($db);
                $orders[] = $order;
            }
            return $orders;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }


    public function getItems(PDO $db): array {
        try {
            $query = "SELECT oi.*, p.name, p.main_image 
                     FROM order_items oi 
                     JOIN products p ON oi.product_id = p.id 
                     WHERE oi.order_id = :order_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['order_id' => $this->id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, Orderitem::class);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }


    public function updateStatus(PDO $db, string $status): bool {
        try {
            $query = "UPDATE orders SET status = :status WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'status' => $status,
                'id' => $this->id
            ]);
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

    public function getPhone(): string {
        return $this->phone;
    }

    public static function getAll(PDO $db, int $limit = 0, int $offset = 0): array {
        try {
            $query = "SELECT * FROM orders ORDER BY created_at ASC";
            
            if ($limit > 0) {
                $query .= " LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $db->prepare($query);
            
            if ($limit > 0) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
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
                $order->phone = $row['phone'] ?? '';
                $order->items = $order->getItems($db);
                $orders[] = $order;
            }
            return $orders;
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public static function get_Total_Sale(PDO $db): int {
        try {
            $orders = Order::getAll($db);
            $total_sale = 0;
            foreach ($orders as $order) {
                if ($order->getStatus()=='completed') {
                     $total_sale += $order->getTotalAmount();
                }
               
            }
            return (int)$total_sale;
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return 0;
        }
    }

    public static function getCount(PDO $db): int {
        try {
            $query = "SELECT COUNT(*) as total FROM orders";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return 0;
        }
    }
}
