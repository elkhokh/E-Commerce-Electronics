<?php

namespace App\controller\Products;

use App\Product;
use App\Massage;
use PDO;
use PDOException;
use App\MangesFiles;
use Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if required files exist
$required_files = [
    __DIR__ . '/../../Product.php',
    __DIR__ . '/../../Massage.php',
    __DIR__ . '/../../MangesFiles.php'
];

foreach ($required_files as $file) {
    if (!file_exists($file)) {
        die("Required file not found: " . $file);
    }
    require_once $file;
}

class Product_controller
{
    public static function handler(PDO $db)
    {
        try {
            if (!isset($_GET['action'])) {
                Massage::set_Massages("danger", "No action specified");
                header("Location: index.php");
                exit;
            }
            
            // var_dump($_POST,$_FILES,$_GET);
            // exit;
            $action = $_GET['action'];

            switch ($action) {
                case 'add':
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
                    header("Location: index.php?page=Products");
                    exit;
            }
        } catch (Exception $e) {
            error_log("Error in Product_controller::handler: " . $e->getMessage());
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php");
            exit;
        }
    }

    public static function create(PDO $db)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Massage::set_Massages("danger", "Invalid request method");
                header("Location: index.php?page=Create_product");
                exit;
            }

            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $price = $_POST['price'];
            $qty = $_POST['qty'] ?? 1;
            $status = $_POST['status'] ?? 1;
            $category_id = (int) $_POST['category'];
            $sub_category_id = (int) $_POST['sub_category'];
            $colors = $_POST['colors'] ?? [];
            $main_image = $_FILES['main_image'];
            $product_images = $_FILES['product_images'];

            if (empty($title) || empty($description) || $price <= 0 || $category_id <= 0) {
                Massage::set_Massages("danger", "Please fill all required fields");
                header("Location: index.php?page=Create_product");
                exit;
            }

            $product = new Product();

            if ($stat = $product->create($db, $title, $description, $price, $qty, $main_image, $category_id, $sub_category_id, $colors, $status)) {
                if (!empty($product_images) && isset($product_images['tmp_name'])) {
                    foreach ($product_images['tmp_name'] as $key => $tmp_name) {
                        if ($product_images['error'][$key] === 0) {
                            $file = [
                                "name" => $product_images['name'][$key],
                                "tmp_name" => $tmp_name
                            ];
                            // var_dump(Product::addImage($db, $stat->getId(), $file, $title));
                            // exit;
                            Product::addImage($db, $stat->getId(), $file, $stat->getName());
                        }
                    }
                }

                Massage::set_Massages("success", "Product created successfully");
                header("Location: index.php?page=products");
            } else {
                Massage::set_Massages("danger", "Failed to create product");
                header("Location: index.php?page=Create_product");
            }
        } catch (Exception $e) {
            error_log("Error in Product_controller::create: " . $e->getMessage());
            Massage::set_Massages("danger", "Error: " . $e->getMessage());
            header("Location: index.php?page=Create_product");
        }
        exit;
    }

    public static function update(PDO $db)
    {
        // $db->beginTransaction();

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Massage::set_Massages("danger", "Invalid request method");
                header("Location: index.php?page=products");
                exit;
            }

            $product_id = (int)$_POST['product_id'];
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $price = $_POST['price'];
            $qty = (int)$_POST['qty'];
            $status = $_POST['status'];
            $colors = $_POST['colors'] ?? [];
            $category_id = (int)$_POST['category'];
            $sub_category_id = (int)$_POST['sub_category'];
            $main_image = $_FILES['main_image'];
            $product_images = $_FILES['product_images'];

            if (empty($title) || empty($description) || $price <= 0 || $category_id <= 0) {
                Massage::set_Massages("danger", "Please fill all required fields");
                header("Location: index.php?page=edit_product&id=" . $product_id);
                exit;
            }

            $product = Product::findById($db, $product_id);
            if (!$product) {
                Massage::set_Massages("danger", "Product not found");
                header("Location: index.php?page=products");
                exit;
            }

            if (!empty($product_images) && isset($product_images['tmp_name'])) {
                Product::deleteProductImages($db, $product_id);
                foreach ($product_images['tmp_name'] as $key => $tmp_name) {
                    if ($product_images['error'][$key] === 0) {
                        $file = [
                            "name" => $product_images['name'][$key],
                            "tmp_name" => $tmp_name
                        ];
                        Product::addImage($db, $product->getId(), $file, $product->getName());
                    }
                }
            }
                //  var_dump($product->update($db,  $title,  $description,  $price,  $qty,  $main_image,  $category_id,  $sub_category_id ,  $colors,$status ));
                // exit;

            if ($product->update($db, $product_id, $title, $description, $price, $qty, $main_image, $category_id, $sub_category_id, $colors, $status)) {
                Massage::set_Massages("success", "Product updated successfully");
                header("Location: index.php?page=products");
            } else {
                Massage::set_Massages("danger", "Failed to update product");
                header("Location: index.php?page=edit_product&id=" . $product_id);
            }
            // $db->commit();
        } catch (Exception $e) {
            // $db->rollBack();
            error_log("Error in Product_controller::update: " . $e->getMessage());
            Massage::set_Massages("danger", "Error: " . $e->getMessage());
            header("Location: index.php?page=edit_product&id=" . $product_id);
        }
        exit;
    }

    public static function delete(PDO $db)
    {
        try {
            if (!isset($_GET['id'])) {
                Massage::set_Massages("danger", "Product ID is required");
                header("Location: index.php?page=products");
                exit;
            }

            $product_id = (int)$_GET['id'];
            
            $product = Product::findById($db, $product_id);
            if (!$product) {
                Massage::set_Massages("danger", "Product not found");
                header("Location: index.php?page=products");
                exit;
            }

            if ($product->delete($db)) {
                Massage::set_Massages("success", "Product deleted successfully");
            } else {
                Massage::set_Massages("danger", "Failed to delete product");
            }
        } catch (Exception $e) {
            error_log("Error in Product_controller::delete: " . $e->getMessage());
            Massage::set_Massages("danger", "Error: " . $e->getMessage());
        }
        
        header("Location: index.php?page=products");
        exit;
    }
}

try {
    Product_controller::handler($db);
} catch (Exception $e) {
    error_log("Fatal error in Product_controller: " . $e->getMessage());
    Massage::set_Massages("danger", "A fatal error occurred. Please try again later.");
    header("Location: index.php");
    exit;
}
