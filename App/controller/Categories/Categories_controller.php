<?php

namespace App\controller\Categories;

use App\Category;
use App\Massage;
use PDO;
// var_dump($_POST);
// exit;

class Categories_controller {
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
                header("Location: index.php?page=Categories");
                exit;
        }
    }

    public static function create(PDO $db) {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=Categories");
            exit;
        }
        $id = (int)$_POST['id'];
        $name = trim($_POST['name'] ?? '');
        $status = isset($_POST['status']) ?$_POST['status']: 0;

          if (empty($name)) {
            Massage::set_Massages("danger", "Category name is required");
            header("Location: index.php?page=Create_Category&action=create&id=" . $id);
            exit;
        }

        $category = new Category();
        $category->setName($name);
        $category->setStatus($status);

        if ($category->create($db)) {
            Massage::set_Massages("success", "Category created successfully");
            header("Location: index.php?page=Categories");
            exit;
        } else {
            Massage::set_Massages("danger", "Failed to create category");
            header("Location: index.php?page=Categories");
            exit;
        }
        
    }

   

   

    public static function update(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=Categories");
            exit;
        }

        $id = (int)$_POST['id'];
        $name = trim($_POST['name'] ?? '');
        $status = isset($_POST['status']) ? $_POST['status'] : 0;

        if (empty($name)) {
            Massage::set_Massages("danger", "Category name is required");
            header("Location: index.php?page=Create_Category&action=edit&id=" . $id);
            exit;
        }

        $category = Category::findById($db, $id);
        if (!$category) {
            Massage::set_Massages("danger", "Category not found");
            header("Location: index.php?page=Create_Category&action=edit&id=" . $id);
            exit;
        }

        $category->setName($name);
        $category->setStatus($status);

        if ($category->update($db)) {
            Massage::set_Massages("success", "Category updated successfully");
            header("Location: index.php?page=Categories");
        } else {
            Massage::set_Massages("danger", "Failed to update category");
            header("Location: index.php?page=Categories_controller&action=edit&id=" . $id);
        }
        exit;
    }

    public static function delete(PDO $db) {
        if (!isset($_GET['id'])) {
            Massage::set_Massages("danger", "Category ID not specified");
            header("Location: index.php?page=Categories_controller");
            exit;
        }

        $id = (int)$_GET['id'];
        $category = Category::findById($db, $id);
        
        if (!$category) {
            Massage::set_Massages("danger", "Category not found");
            header("Location: index.php?page=Categories");
            exit;
        }

        if ($category->delete($db)) {
            Massage::set_Massages("success", "Category deleted successfully");
        } else {
            Massage::set_Massages("danger", "Failed to delete category");
        }

        header("Location: index.php?page=Categories");
        exit;
    }

    
}

Categories_controller::handler($db);