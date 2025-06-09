<?php

namespace App;

use PDO;
use PDOException;

class Category {
    private $id;
    private $name;
    private $created_at;
    private $status;

    public function __construct() {
        $this->created_at = date('Y-m-d H:i:s');
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getStatus():int {
        return $this->status;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    // Database Operations
    public static function getAll(PDO $db, int $limit = 0, int $offset = 0): array {
        try {
            $query = "SELECT id, name, created_at, status FROM categories ORDER BY created_at DESC";
            
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
            $stmt = $db->prepare("SELECT id, name, created_at, status FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetchObject(self::class);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public static function findByName(PDO $db, $name) {
        try {
            $stmt = $db->prepare("SELECT id, name, created_at, status FROM categories WHERE name = ?");
            $stmt->execute([$name]);
            return $stmt->fetchObject(self::class);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public function delete(PDO $db) {
        try {
            if ($this->id) {
                $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
                return $stmt->execute([$this->id]);
            }
            return false;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public function getProducts(PDO $db, $LIMIT = 9, $offset = 0) :array {
        try {
            $stmt = $db->prepare("SELECT * FROM products WHERE category_id = ? LIMIT ? OFFSET ?");
            $stmt->execute([$this->id, $LIMIT, $offset]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, Product::class);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public static function search(PDO $db, string $search): ?array {
        try {
            $stmt = $db->prepare("SELECT id, name, created_at, status FROM categories WHERE name LIKE :searchTerm ORDER BY name");
            $searchTerm = "%{$search}%";
            $stmt->bindParam(':searchTerm', $searchTerm);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public function update(PDO $db): bool {
        try {
            if (!$this->id) {
                return false;
            }

            $query = "UPDATE categories 
                     SET name = :name, 
                         status = :status, 
                         created_at = :created_at 
                     WHERE id = :id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':status', $this->status, PDO::PARAM_INT);
            $stmt->bindValue(':created_at', $this->created_at, PDO::PARAM_STR);
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

    public function create(PDO $db): bool {
        try {
            $query = "INSERT INTO categories (name, status, created_at) 
                     VALUES (:name, :status, :created_at)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':status', $this->status, PDO::PARAM_INT);
            $stmt->bindValue(':created_at', $this->created_at, PDO::PARAM_STR);
            
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
}
