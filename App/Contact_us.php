<?php

namespace App;

use PDO;
use PDOException;

class Contact_us {
    private int $id;
    private string $name;
    private string $email;
    private string $message;
    private string $created_at;

    public function __construct() {
    }

    public static function create(PDO $db, string $name, string $email, string $message): ?Contact_us {
        try {
            $query = "INSERT INTO contact_messages (name, email, message) 
                     VALUES (:name, :email, :message)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'message' => $message
            ]);

            $contact = new Contact_us();
            $contact->id = $db->lastInsertId();
            $contact->name = $name;
            $contact->email = $email;
            $contact->message = $message;
            $contact->created_at = date('Y-m-d H:i:s');

            return $contact;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    public static function getAll(PDO $db): array {
        try {
            $query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public static function findById(PDO $db, int $id): ?Contact_us {
        try {
            $query = "SELECT * FROM contact_messages WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id]);
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $contact = new Contact_us();
                $contact->id = $row['id'];
                $contact->name = $row['name'];
                $contact->email = $row['email'];
                $contact->message = $row['message'];
                $contact->created_at = $row['created_at'];
                
                return $contact;
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

    public function delete(PDO $db): bool {
        try {
            $query = "DELETE FROM contact_messages WHERE id = :id";
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

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }
}
