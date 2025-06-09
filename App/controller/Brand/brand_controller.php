<?php

namespace App\controller\Brand;

use App\Brand;
use App\Massage;
use PDO;
// var_dump($_POST);
// exit;

class Brand_controller {
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
                header("Location: index.php?page=brands");
                exit;
        }
    }

    public static function create(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=brands");
            exit;
        }


        $name = trim($_POST['name'] ?? '');
        $category_id = (int)($_POST['category_id'] ?? 0);


        if (empty($name) || $category_id <= 0) {
            Massage::set_Massages("danger", "Brand name and category are required");
            header("Location: index.php?page=create_brand");
            exit;
        }

        try {
            $brand = new Brand();
            $brand->setName($name);
            $brand->setCategoryId($category_id);

            if ($brand->create($db)) {
                Massage::set_Massages("success", "Brand created successfully");
                header("Location: index.php?page=brands");
                exit;
            } else {
                Massage::set_Massages("danger", "Failed to create brand");
                header("Location: index.php?page=create_brand");
                exit;
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php?page=create_brand");
            exit;
        }
    }

   

   

    public static function update(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=brands");
            exit;
        }


        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        

        if ($id <= 0 || empty($name)) {
            Massage::set_Massages("danger", "Brand ID and name are required");
            header("Location: index.php?page=edit_brand&id=" . $id);
            exit;
        }

        try {
            $brand = Brand::findById($db, $id);
            if (!$brand) {
                Massage::set_Massages("danger", "Brand not found");
                header("Location: index.php?page=brands");
                exit;
            }

            $brand->setName($name);

            if ($brand->update($db)) {
                Massage::set_Massages("success", "Brand updated successfully");
                header("Location: index.php?page=brands");
                exit;
            } else {
                Massage::set_Massages("danger", "Failed to update brand");
                header("Location: index.php?page=edit_brand&id=" . $id);
                exit;
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php?page=edit_brand&id=" . $id);
            exit;
        }
    }

    public static function delete(PDO $db) {
        if (!isset($_GET['id'])) {
            Massage::set_Massages("danger", "Brand ID not specified");
            header("Location: index.php?page=brands");
            exit;
        }

        $id = (int)$_GET['id'];
        
        try {
            $brand = Brand::findById($db, $id);
            if (!$brand) {
                Massage::set_Massages("danger", "Brand not found");
                header("Location: index.php?page=brands");
                exit;
            }

            if ($brand->delete($db)) {
                Massage::set_Massages("success", "Brand deleted successfully");
            } else {
                Massage::set_Massages("danger", "Failed to delete brand");
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
        }

        header("Location: index.php?page=brands");
        exit;
    }

    
}

Brand_controller::handler($db);