<?php

namespace App;

use PDO;
use PDOException;

class Review {
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $rating;
    private string $comment;
    private string $created_at;
    private ?User $user = null;
    private ?Product $product = null;

    public function __construct() {
    }

    public static function create(PDO $db, int $user_id, int $product_id, int $rating, string $comment) {
        try {
            if ($rating < 1 || $rating > 5) {
                return null;
            }
            

            $query = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at) 
                     VALUES (:user_id, :product_id, :rating, :comment, :created_at)";
            
            $created_at = date('Y-m-d H:i:s');
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => $created_at
            ]);
            
            if (!$result) {
                return null;
            }

            $review = new Review();
            $review->id = $db->lastInsertId();
            $review->user_id = $user_id;
            $review->product_id = $product_id;
            $review->rating = $rating;
            $review->comment = $comment;
            $review->created_at = $created_at;

            return $review;
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public static function getProductReviews(PDO $db, int $product_id): array {
        try {
            $query = "SELECT * FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $product_id]);
            
            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $review = new Review();
                $review->id = $row['id'];
                $review->user_id = $row['user_id'];
                $review->product_id = $row['product_id'];
                $review->rating = $row['rating'];
                $review->comment = $row['comment'];
                $review->created_at = $row['created_at'];
                $reviews[] = $review;
            }
            return $reviews;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public static function getAverageRating(PDO $db, int $product_id): float {
        try {
            $query = "SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = :product_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $product_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return round($result['avg_rating'] ?? 0, 1);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return 0.0;
        }
    }

    public function delete(PDO $db): bool {
        try {
            $query = "DELETE FROM reviews WHERE id = :id";
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

    public static function findUserReview(PDO $db, int $user_id, int $product_id) {
        try {
            $query = "SELECT * FROM reviews WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $db->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $product_id
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $review = new Review();
                $review->id = $result['id'];
                $review->user_id = $result['user_id'];
                $review->product_id = $result['product_id'];
                $review->rating = $result['rating'];
                $review->comment = $result['comment'];
                $review->created_at = $result['created_at'];
                return $review;
            }
            return null;
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public static function update(PDO $db, int $review_id, int $rating, string $comment) {
        try {
            if ($rating < 1 || $rating > 5) {
                return null;
            }

            $query = "UPDATE reviews 
                     SET rating = :rating, 
                         comment = :comment, 
                         created_at = :created_at 
                     WHERE id = :id";
            
            $created_at = date('Y-m-d H:i:s');
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute([
                'id' => $review_id,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => $created_at
            ]);
            
            if (!$result) {
                return null;
            }

            return true;
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public static function createOrUpdate(PDO $db, int $user_id, int $product_id, int $rating, string $comment) {
        
        $existingReview = self::findUserReview($db, $user_id, $product_id);
        
        if ($existingReview) {

            return self::update($db, $existingReview->id, $rating, $comment);
        } else {
            return self::create($db, $user_id, $product_id, $rating, $comment);
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

    public function getRating(): int {
        return $this->rating;
    }

    public function getComment(): string {
        return $this->comment;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }
}
