<?php

namespace App;

use PDO;
use PDOException;

class Brand {
    private $id;
    private $name;
    private $category_id;

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setCategoryId($category_id) {
        $this->category_id = $category_id;
    }

    // Database Operations
    public static function getAll(PDO $db, int $limit = 0, int $offset = 0): array {
        try {
            $query = "SELECT b.*, c.name as category_name 
                     FROM subcategories b 
                     LEFT JOIN categories c ON b.category_id = c.id 
                     ORDER BY b.name ASC";
            
            if ($limit > 0) {
                $query .= " LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $db->prepare($query);
            
            if ($limit > 0) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public static function findById(PDO $db, $id) {
        try {
            $stmt = $db->prepare("SELECT b.*, c.name as category_name 
                                 FROM subcategories b 
                                 LEFT JOIN categories c ON b.category_id = c.id 
                                 WHERE b.id = ?");
            $stmt->execute([$id]);
            return $stmt->fetchObject(self::class);
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public static function findByCategory(PDO $db, $category_id): array {
        try {
            $stmt = $db->prepare("SELECT * FROM subcategories WHERE category_id = ? ORDER BY name ASC");
            $stmt->execute([$category_id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public function create(PDO $db): bool {
        try {
            $query = "INSERT INTO subcategories (name, category_id) 
                     VALUES (:name, :category_id)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $this->id = $db->lastInsertId();
                return true;
            }
            return false;
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public function update(PDO $db): bool {
        try {
            if (!$this->id) {
                return false;
            }

            $query = "UPDATE subcategories 
                     SET name = :name, 
                         category_id = :category_id 
                     WHERE id = :id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public function delete(PDO $db): bool {
        try {
            if (!$this->id) {
                return false;
            }
            $stmt = $db->prepare("DELETE FROM subcategories WHERE id = ?");
            return $stmt->execute([$this->id]);
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }
}
