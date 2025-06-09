<?php

namespace App;

use PDO;

class Discount {
    private $id;
    private $code;
    private $value;
    private $status;
    private $created_at;

  
    public function __construct($value = null) {
        $this->value = $value;
        $this->status = 1; 
    }

    private static function generateUniqueCode(PDO $db) {
        // do {
 
        //     $code = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
            

        //     $sql = "SELECT COUNT(*) FROM discounts WHERE code = :code";
        //     $stmt = $db->prepare($sql);
        //     $stmt->execute([':code' => $code]);
        //     $exists = $stmt->fetchColumn();
        // } while ($exists);

        return strtoupper(uniqid());
    }

 
    public function getId() { return $this->id; }
    public function getCode() { return $this->code; }
    public function getValue() { return $this->value; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->created_at; }

 
    public function setValue($value) { $this->value = $value; }
    public function setStatus($status) { $this->status = $status; }


    public function save(PDO $db) {
        if ($this->id) {
            return $this->update($db);
        }
        return $this->create($db);
    }

    private function create(PDO $db) {
        $this->code = self::generateUniqueCode($db);
        
        $sql = "INSERT INTO discounts (code, value, status, created_at) 
                VALUES (:code, :value, :status, NOW())";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':code' => $this->code,
            ':value' => $this->value,
            ':status' => $this->status
        ]);

        if ($result) {
            $this->id = $db->lastInsertId();
            return true;
        }
        return false;
    }

    private function update(PDO $db) {
        $sql = "UPDATE discounts 
                SET value = :value, 
                    status = :status 
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id' => $this->id,
            ':value' => $this->value,
            ':status' => $this->status
        ]);
    }

    public function delete(PDO $db) {
        if ($this->id) {
            $sql = "DELETE FROM discounts WHERE id = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([':id' => $this->id]);
        }
        return false;
    }


    public static function findById(PDO $db, $id) {
        $sql = "SELECT * FROM discounts WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return self::createFromArray($row);
        }
        return null;
    }

    public static function find_by_code(PDO $db, $code) {
        $sql = "SELECT * FROM discounts WHERE code = :code";
        $stmt = $db->prepare($sql);
        $stmt->execute([':code' => $code]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return self::createFromArray($row);
        }
        return null;
    }

    public static function getAll(PDO $db) {
        $sql = "SELECT * FROM discounts ORDER BY created_at ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        $discounts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $discounts[] = self::createFromArray($row);
        }
        return $discounts;
    }

    private static function createFromArray($array) {
        $discount = new self();
        $discount->id = $array['id'];
        $discount->code = $array['code'];
        $discount->value = $array['value'];
        $discount->status = $array['status'];
        $discount->created_at = $array['created_at'];
        return $discount;
    }

    public function is_valid(): bool {
        return (int)$this->status === 1;
    }

    public function calculate_discount(float $total): float {
        return $this->value;
    }
}