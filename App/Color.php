<?php

namespace App;

use PDO;
use PDOException;

class Color {
    private $id;
    private $name;
    private $code;

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCode() {
        return $this->code;
    }

  
    public static function getAllColors(PDO $db): array {
        try {
            $query = "SELECT id, name, code FROM colors ORDER BY name ASC";
            
            $stmt = $db->prepare($query);
            
            
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
}
