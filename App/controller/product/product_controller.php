<?php

namespace App\controller\Products;

use App\Product;
use App\Massage;
use PDO;
use PDOException;
use App\MangesFiles;

class Product_controller
{
    public static function handler(PDO $db)
    {
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
    }

    public static function create(PDO $db)
    {
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

        try {
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
        } catch (PDOException $e) {
            Massage::set_Massages("danger", "Error: " . $e->getMessage());
            header("Location: index.php?page=Create_product");
        }
        exit;
    }

    public static function update(PDO $db)
    {
        // $db->beginTransaction();

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

        try {
            $product = Product::findById($db, $product_id);
            if (!$product) {
                Massage::set_Massages("danger", "Product not found");
                header("Location: index.php?page=products");
                exit;
            }


            


                if (!empty($product_images) && isset($product_images['tmp_name'])) {
                    Product::deleteProductImages($db,$product_id);
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

            if ($product->update($db,  $title,  $description,  $price,  $qty,  $main_image,  $category_id,  $sub_category_id ,  $colors,$status )) {
                Massage::set_Massages("success", "Product updated successfully");
                header("Location: index.php?page=products");
            } else {
                Massage::set_Massages("danger", "Failed to update product");
                header("Location: index.php?page=edit_product&id=" . $product_id);
            }
            // $db->commit();
        } catch (PDOException $e) {
            // $db->rollBack();
            Massage::set_Massages("danger", "Error: " . $e->getMessage());
            header("Location: index.php?page=edit_product&id=" . $product_id);
        }
        exit;
    }

    public static function delete(PDO $db)
    {
        if (!isset($_GET['id'])) {
            Massage::set_Massages("danger", "Product ID is required");
            header("Location: index.php?page=products");
            exit;
        }

        $product_id = (int)$_GET['id'];
        
        try {
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
        } catch (PDOException $e) {
            Massage::set_Massages("danger", "Error: " . $e->getMessage());
        }
        
        header("Location: index.php?page=products");
        exit;
    }
}


Product_controller::handler($db);
