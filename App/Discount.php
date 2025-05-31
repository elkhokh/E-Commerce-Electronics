<?php

namespace App;

use PDO;

class Discount {
    private $id;
    private $code;
    private $type;
    private $value;
    private $start_date;
    private $end_date;
    private $status;
    private $created_at;

    // Constructor
    public function __construct($code = null, $type = null, $value = null, $start_date = null, $end_date = null) {
        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->status = 1; // Default active
    }

    // Getters
    public function getId() { return $this->id; }
    public function getCode() { return $this->code; }
    public function getType() { return $this->type; }
    public function getValue() { return $this->value; }
    public function getStartDate() { return $this->start_date; }
    public function getEndDate() { return $this->end_date; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->created_at; }

    // Setters
    public function setCode($code) { $this->code = $code; }
    public function setType($type) { $this->type = $type; }
    public function setValue($value) { $this->value = $value; }
    public function setStartDate($start_date) { $this->start_date = $start_date; }
    public function setEndDate($end_date) { $this->end_date = $end_date; }
    public function setStatus($status) { $this->status = $status; }

    // Database Operations
    public function save(PDO $db) {
        if ($this->id) {
            // Update existing discount
            $sql = "UPDATE discounts SET 
                    code = :code,
                    type = :type,
                    value = :value,
                    start_date = :start_date,
                    end_date = :end_date,
                    status = :status
                    WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':code' => $this->code,
                ':type' => $this->type,
                ':value' => $this->value,
                ':start_date' => $this->start_date,
                ':end_date' => $this->end_date,
                ':status' => $this->status,
                ':id' => $this->id
            ]);
        } else {
            // Insert new discount
            $sql = "INSERT INTO discounts (code, type, value, start_date, end_date, status) 
                    VALUES (:code, :type, :value, :start_date, :end_date, :status)";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':code' => $this->code,
                ':type' => $this->type,
                ':value' => $this->value,
                ':start_date' => $this->start_date,
                ':end_date' => $this->end_date,
                ':status' => $this->status
            ]);
            
            if ($result) {
                $this->id = $db->lastInsertId();
            }
            return $result;
        }
    }

    // Find discount by ID
    public static function find_by_id(PDO $db, $id) {
        $sql = "SELECT * FROM discounts WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $discount = new self();
            $discount->id = $row['id'];
            $discount->code = $row['code'];
            $discount->type = $row['type'];
            $discount->value = $row['value'];
            $discount->start_date = $row['start_date'];
            $discount->end_date = $row['end_date'];
            $discount->status = $row['status'];
            $discount->created_at = $row['created_at'];
            return $discount;
        }
        return null;
    }

    // Find discount by code
    public static function find_by_code(PDO $db, $code) {
        $sql = "SELECT * FROM discounts WHERE code = :code";
        $stmt = $db->prepare($sql);
        $stmt->execute([':code' => $code]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $discount = new self();
            $discount->id = $row['id'];
            $discount->code = $row['code'];
            $discount->type = $row['type'];
            $discount->value = $row['value'];
            $discount->start_date = $row['start_date'];
            $discount->end_date = $row['end_date'];
            $discount->status = $row['status'];
            $discount->created_at = $row['created_at'];
            return $discount;
        }
        return null;
    }

    // Get all active discounts
    public static function get_active_discounts(PDO $db) {
        $sql = "SELECT * FROM discounts WHERE status = 1 AND start_date <= NOW() AND end_date >= NOW()";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        $discounts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $discount = new self();
            $discount->id = $row['id'];
            $discount->code = $row['code'];
            $discount->type = $row['type'];
            $discount->value = $row['value'];
            $discount->start_date = $row['start_date'];
            $discount->end_date = $row['end_date'];
            $discount->status = $row['status'];
            $discount->created_at = $row['created_at'];
            $discounts[] = $discount;
        }
        return $discounts;
    }

    // Calculate discount amount
    public function calculate_discount($original_price) {
        if ($this->type === 'percentage') {
            return ($original_price * $this->value) / 100;
        } else {
            return $this->value;
        }
    }

    // Check if discount is valid
    public function is_valid() {
        $now = new \DateTime();
        $start = new \DateTime($this->start_date);
        $end = new \DateTime($this->end_date);
        
        return $this->status == 1 && $now >= $start && $now <= $end;
    }

    // Delete discount
    public function delete(PDO $db) {
        if ($this->id) {
            $sql = "DELETE FROM discounts WHERE id = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([':id' => $this->id]);
        }
        return false;
    }
}