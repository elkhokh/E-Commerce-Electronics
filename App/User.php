<?php

namespace App;

use PDO;
use PDOException;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;


    function __construct(int $id, string $name, string $email, string $password, string $role = "user")
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
    function get_id(): int
    {
        return $this->id;
    }
    function get_name(): string
    {
        return $this->name;
    }
    function get_email(): string
    {
        return $this->email;
    }
    function get_password(): string
    {
        return $this->password;
    }
    function set_password($new_password): void
    {
        $this->password = $new_password;
    }
    function get_role(): string
    {
        return $this->role;
    }

    static function create(PDO $pdo, string $name, string $email, string $password, string $role = "user"): ?User
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role)
        VALUES (:name,:email,:password,:role)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":role", $role);
       $user = $stmt->execute();
         
        if ($user) {
            $id = (int) $pdo->lastInsertId();
            $_SESSION["user"] = [
                "name" => $name,
                "id" => $id
            ];
            return new self($id, $name, $email, $password, $role = "user");
        }
        return null;
    }
    static function delete(PDO $pdo, $id): bool
    {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $user = $stmt->execute();
        if (!$user) {
            return false;
        }
        return true;
    }
    static function find_by_email(PDO $pdo, string $email): ?User
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['id'], $row['name'], $row['email'], $row['password'], $row['role']);
        }
        return null;
    }
    static function find_by_id(PDO $pdo, string $id): ?User
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['id'], $row['name'], $row['email'], $row['password'], $row['role']);
        }
        return null;
    }
    static function login_user(PDO $pdo, string $email, string $password): bool
    {
        $user = self::find_by_email($pdo, $email);
        if ($user !== null) {
            if (password_verify($password, $user->get_password())) {
                $_SESSION["user"] = [
                    "name" => $user->get_name(),
                    "id" => $user->get_id()
                ];
                return true;
            }
        }

        return false;
    }
    static function change_password(PDO $pdo, string $email, string $new_password): bool
    {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :new_password WHERE email = :email;");
        $stmt->bindParam(":new_password", $password_hash);
        $stmt->bindParam(":email", $email);
        $stat=$stmt->execute();
        
        if ($stat) {
            return true;
        }
        return false;
    }

    static function update_profile_image(PDO $db, int $user_id, string $new_image_path): bool
    {
        try {
            $stmt = $db->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :id");
            $stmt->bindParam(":profile_image", $new_image_path);
            $stmt->bindParam(":id", $user_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    static function get_profile_image(PDO $db, int $user_id): ?string
    {
        try {
            $stmt = $db->prepare("SELECT profile_image FROM users WHERE id = :id");
            $stmt->bindParam(":id", $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['profile_image'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function getRole(PDO $pdo, int $userId): ?string
    {
        try {
            $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
            $stmt->bindParam(":id", $userId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['role'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
