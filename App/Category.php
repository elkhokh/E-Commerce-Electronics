<?php

namespace App;

use PDO;

class Category {
    private $id;
    private $name;
    private $description;
    private $created_at;

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

    public function getDescription() {
        return $this->description;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    // Database Operations
    public static function getAll(PDO $db) {
        $stmt = $db->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function findById(PDO $db, $id) {
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public static function findByName(PDO $db, $name) {
        $stmt = $db->prepare("SELECT * FROM categories WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetchObject(self::class);
    }


    public function delete(PDO $db) {
        if ($this->id) {
            $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
            return $stmt->execute([$this->id]);
        }
        return false;
    }


    public function getProducts(PDO $db, $LIMIT = 9, $offset = 0)  :array {
        $stmt = $db->prepare("SELECT * FROM products WHERE category_id = ? LIMIT ? OFFSET ?");
        $stmt->execute([$this->id, $LIMIT, $offset]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Product::class);
    }

   

}
