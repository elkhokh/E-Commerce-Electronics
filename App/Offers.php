<?php

namespace App;

use PDO;

class Offers {
    private int $id;
    private int $product_id;
    private string $title;
    private string $description;
    private string $discount_percentage;
    private string $start_date;
    private string $end_date;
    private int $status;
    private string $created_at;

    public function __construct() {
    }

    public static function create(PDO $db, int $product_id, string $title, string $description, 
                             string $discount_percentage, string $start_date, string $end_date): ?Offers {
        try {
            $query = "INSERT INTO offers (product_id, title, description, discount_percentage, start_date, end_date) 
                     VALUES (:product_id, :title, :description, :discount_percentage, :start_date, :end_date)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'product_id' => $product_id,
                'title' => $title,
                'description' => $description,
                'discount_percentage' => $discount_percentage,
                'start_date' => $start_date,
                'end_date' => $end_date
            ]);

            $offer = new Offers();
            $offer->id = $db->lastInsertId();
            $offer->product_id = $product_id;
            $offer->title = $title;
            $offer->description = $description;
            $offer->discount_percentage = $discount_percentage;
            $offer->start_date = $start_date;
            $offer->end_date = $end_date;
            $offer->status = 1;

            return $offer;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public static function findById(PDO $db, int $id): ?Offers {
        try {
            $query = "SELECT * FROM offers WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id]);
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $offer = new Offers();
                $offer->id = $row['id'];
                $offer->product_id = $row['product_id'];
                $offer->title = $row['title'];
                $offer->description = $row['description'];
                $offer->discount_percentage = $row['discount_percentage'];
                $offer->start_date = $row['start_date'];
                $offer->end_date = $row['end_date'];
                $offer->status = $row['status'];
                $offer->created_at = $row['created_at'];
                
                return $offer;
            }
            return null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public static function getProductOffers(PDO $db, int $product_id): array {
        try {
            $query = "SELECT * FROM offers 
                     WHERE product_id = :product_id 
                     AND status = 1 
                     AND start_date <= NOW() 
                     AND end_date >= NOW()";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $product_id]);
            
            $offers = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $offer = new Offers();
                $offer->id = $row['id'];
                $offer->product_id = $row['product_id'];
                $offer->title = $row['title'];
                $offer->description = $row['description'];
                $offer->discount_percentage = $row['discount_percentage'];
                $offer->start_date = $row['start_date'];
                $offer->end_date = $row['end_date'];
                $offer->status = $row['status'];
                $offer->created_at = $row['created_at'];
                $offers[] = $offer;
            }
            return $offers;
        } catch (\PDOException $e) {
            return [];
        }
    }

    public static function getAll(PDO $db): array {
        try {
            $query = "SELECT * FROM offers ORDER BY created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            $offers = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $offer = new Offers();
                $offer->id = $row['id'];
                $offer->product_id = $row['product_id'];
                $offer->title = $row['title'];
                $offer->description = $row['description'];
                $offer->discount_percentage = $row['discount_percentage'];
                $offer->start_date = $row['start_date'];
                $offer->end_date = $row['end_date'];
                $offer->status = $row['status'];
                $offer->created_at = $row['created_at'];
                $offers[] = $offer;
            }
            return $offers;
        } catch (\PDOException $e) {
            return [];
        }
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getProductId(): int {
        return $this->product_id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDiscountPercentage(): string {
        return $this->discount_percentage;
    }

    public function getStartDate(): string {
        return $this->start_date;
    }

    public function getEndDate(): string {
        return $this->end_date;
    }

    public function getStatus(): int {
        return $this->status;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function getProduct(PDO $db): ?Product {
        return Product::findById($db, $this->product_id);
    }
}
