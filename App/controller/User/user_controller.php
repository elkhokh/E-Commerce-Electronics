<?php
namespace App\controller\User;

use App\User;
use App\Massage;
use PDO;

class User_controller {
    public static function handler(PDO $db) {
        if (!isset($_GET['action'])) {
            Massage::set_Massages("danger", "No action specified");
            header("Location: index.php");
            exit;
        }

        $action = $_GET['action'];

        switch ($action) {
            case 'create':
                self::create($db);
                break;
            case 'update':
                self::update($db);
                break;
            case 'delete':
                self::delete($db);
                break;
            default:
                Massage::set_Massages("danger", "Invalid action");
                header("Location: index.php?page=user");
                exit;
        }
    }

    public static function create(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=user");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'user');
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;


        if (empty($name) || empty($email) ) {
            Massage::set_Massages("danger", "Name, email and password are required");
            header("Location: index.php?page=create_user");
            exit;
        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Massage::set_Massages("danger", "Invalid email format");
            header("Location: index.php?page=create_user");
            exit;
        }

   
        if (User::find_by_email($db, $email)) {
            Massage::set_Massages("danger", "Email already exists");
            header("Location: index.php?page=create_user");
            exit;
        }

        try {
            $user = User::create(
                $db,
                $name,
                $email,
                $password,
                $role,
                $status
            );
            

            if ($user) {
                Massage::set_Massages("success", "User created successfully");
                header("Location: index.php?page=user");
                exit;
            } else {
                Massage::set_Massages("danger", "Failed to create user");
                header("Location: index.php?page=create_user");
                exit;
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php?page=create_user");
            exit;
        }
    }

    public static function update(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=user");
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? 'user');
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

        if ($id <= 0 || empty($name) || empty($email)) {
            Massage::set_Massages("danger", "ID, name and email are required");
            header("Location: index.php?page=create_user&action=edit&user_id=" . $id);
            exit;
        }

        try {
            $user = User::find_by_id($db, $id);
            if (!$user) {
                Massage::set_Massages("danger", "User not found");
                header("Location: index.php?page=user");
                exit;
            }


            if ($email !== $user->get_email()) {
                if (User::find_by_email($db, $email)) {
                    Massage::set_Massages("danger", "Email already exists");
                    header("Location: index.php?page=create_user&action=edit&user_id=" . $id);
                    exit;
                }
            }

            if ($user->update($db,$id,$name,$email,$role,$status)) {
                Massage::set_Massages("success", "User updated successfully");
                header("Location: index.php?page=user");
                exit;
            } else {
                Massage::set_Massages("danger", "Failed to update user");
                header("Location: index.php?page=create_user&action=edit&user_id=" . $id);
                exit;
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php?page=create_user&action=edit&user_id=" . $id);
            exit;
        }
    }

    public static function delete(PDO $db) {
        if (!isset($_GET['id'])) {
            Massage::set_Massages("danger", "User ID not specified");
            header("Location: index.php?page=users");
            exit;
        }

        $id = (int)$_GET['id'];
        
        try {
            $user = User::find_by_id($db, $id);
            if (!$user) {
                Massage::set_Massages("danger", "User not found");
                header("Location: index.php?page=user");
                exit;
            }

            if (User::delete($db, $id)) {
                Massage::set_Massages("success", "User deleted successfully");
            } else {
                Massage::set_Massages("danger", "Failed to delete user");
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
        }

        header("Location: index.php?page=user");
        exit;
    }
}

User_controller::handler($db);