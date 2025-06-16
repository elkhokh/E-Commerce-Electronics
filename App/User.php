<?php

namespace App;

use PDO;
use PDOException;
use App\Traits\Mailer;

class User
{
    use Mailer;

    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    private int $status;
    private ?string $verification_code;
    private ?string $verification_code_expires;

    function __construct(int $id, string $name, string $email, string $password, string $role = "user", int $status = 1, ?string $verification_code = null, ?string $verification_code_expires = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
        $this->verification_code = $verification_code;
        $this->verification_code_expires = $verification_code_expires;
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
    function get_status(): int
    {
        return $this->status;
    }
    function set_status(int $status): void
    {
        $this->status = $status;
    }

    static function register(PDO $pdo, string $name, string $email, string $password, string $role = "user", int $status = 0): ?User
    {
        $verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $verification_code_expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status, verification_code, verification_code_expires)
        VALUES (:name, :email, :password, :role, :status, :verification_code, :verification_code_expires)");
        
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":verification_code", $verification_code);
        $stmt->bindParam(":verification_code_expires", $verification_code_expires);
        
        $user = $stmt->execute();
         
        if ($user) {
            $id = (int) $pdo->lastInsertId();
            $newUser = new self($id, $name, $email, $password, $role, $status, $verification_code, $verification_code_expires);
            
            $newUser->sendVerificationEmail($email, $verification_code, 'register');
            
            return $newUser;
        }
        return null;
    }
    static function create(PDO $pdo, string $name, string $email, string $password, string $role = "user", int $status = 1): ?User
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role,status)
        VALUES (:name,:email,:password,:role,:status)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":status", $status);
       $user = $stmt->execute();
         
        if ($user) {
            $id = (int) $pdo->lastInsertId();
            return new self($id, $name, $email, $password, $role, $status);
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
            return new User(
                $row['id'],
                $row['name'],
                $row['email'],
                $row['password'],
                $row['role'],
                $row['status']
            );
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
            return new User(
                $row['id'],
                $row['name'],
                $row['email'],
                $row['password'],
                $row['role'],
                $row['status']
            );
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

    static function update_profile_image(PDO $pdo, int $user_id, string $new_image_path): bool
    {
        try {
            $stmt = $pdo->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :id");
            $stmt->bindParam(":profile_image", $new_image_path);
            $stmt->bindParam(":id", $user_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    static function get_profile_image(PDO $pdo, int $user_id): ?string
    {
        try {
            $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = :id");
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

    static function getAll(PDO $pdo, int $limit = 0, int $offset = 0): array
    {
        try {
            $query = "SELECT * FROM users ORDER BY id ASC";
            
            if ($limit > 0) {
                $query .= " LIMIT :limit";
                if ($offset > 0) {
                    $query .= " OFFSET :offset";
                }
            }

            $stmt = $pdo->prepare($query);
            
            if ($limit > 0) {
                $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
                if ($offset > 0) {
                    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
                }
            }

            $stmt->execute();
            
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role'],
                    $row['status']
                );
                $users[] = $user;
            }
            return $users;
        } catch (PDOException $e) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    static function findByName(PDO $pdo, string $name): ?User
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :name");
            $searchTerm = "%{$name}%";
            $stmt->bindParam(":name", $searchTerm);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new User(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role'],
                    $row['status']
                );
            }
            return null;
        } catch (PDOException $e) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    static function updateStatus(PDO $pdo, int $user_id, int $status): bool
    {
        try {
            $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
            $stmt->bindParam(":status", $status, PDO::PARAM_INT);
            $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    static function update(PDO $pdo, int $id, string $name, string $email, string $role, int $status): bool
    {
        try {
            $stmt = $pdo->prepare("UPDATE users SET 
                name = :name,
                email = :email,
                role = :role,
                status = :status
                WHERE id = :id");

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":role", $role);
            $stmt->bindParam(":status", $status, PDO::PARAM_INT);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    static function updateWithPassword(PDO $pdo, int $id, string $name, string $email, string $role, int $status, string $password): bool
    {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("UPDATE users SET 
                name = :name,
                email = :email,
                role = :role,
                status = :status,
                password = :password
                WHERE id = :id");

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":role", $role);
            $stmt->bindParam(":status", $status, PDO::PARAM_INT);
            $stmt->bindParam(":password", $password_hash);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    static function verify_email(PDO $pdo, string $email, string $code): bool
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND verification_code = :code AND verification_code_expires > NOW()");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":code", $code);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {

            $updateStmt = $pdo->prepare("UPDATE users SET status = 1, verification_code = NULL, verification_code_expires = NULL WHERE email = :email");
            $updateStmt->bindParam(":email", $email);
            return $updateStmt->execute();
        }
        return false;
    }

    static function request_password_reset(PDO $pdo, string $email): bool
    {
        $user = self::find_by_email($pdo, $email);
        if ($user) {
            $verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $verification_code_expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            
            $stmt = $pdo->prepare("UPDATE users SET verification_code = :code, verification_code_expires = :expires WHERE email = :email");
            $stmt->bindParam(":code", $verification_code);
            $stmt->bindParam(":expires", $verification_code_expires);
            $stmt->bindParam(":email", $email);
            
            if ($stmt->execute()) {
                $user->sendVerificationEmail($email, $verification_code, 'reset');
                return true;
            }
        }
        return false;
    }

    static function verify_reset_code(PDO $pdo, string $email, string $code): bool
    {
        try {
            $stmt = $pdo->query("SELECT * FROM users WHERE email = :email AND verification_code = :code AND verification_code_expires > NOW()");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":code", $code);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error verifying reset code: " . $e->getMessage());
            return false;
        }
    }

    static function reset_password(PDO $pdo, string $email, string $code, string $new_password): bool
    {
        try {

            if (self::verify_email($pdo, $email, $code)) {
                return false;
            }

            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password, verification_code = NULL, verification_code_expires = NULL WHERE email = :email");
            $stmt->bindParam(":password", $password_hash);
            $stmt->bindParam(":email", $email);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error resetting password: " . $e->getMessage());
            return false;
        }
    }
}
