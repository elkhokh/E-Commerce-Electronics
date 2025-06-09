<?php
namespace App\controller\Offers;

use App\Offers;
use App\Massage;
use PDO;
use DateTime;

// var_dump($_POST,$_GET);
// exit;
class Offers_controller {
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
                header("Location: index.php?page=offers");
                exit;
        }
    }

    public static function create(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=offers");
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $product_id = (int)($_POST['product_id'] ?? 0);
        $discount_percentage = trim($_POST['discount_percentage'] ?? '');
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;
        $end_date = trim($_POST['end_date'] ?? '');

        if (empty($title) || empty($description) || $product_id <= 0 || empty($discount_percentage) || empty($end_date)) {
            Massage::set_Massages("danger", "All fields are required");
            header("Location: index.php?page=create_offer");
            exit;
        }

        if (!is_numeric($discount_percentage) || $discount_percentage < 0 || $discount_percentage > 100) {
            Massage::set_Massages("danger", "Discount percentage must be between 0 and 100");
            header("Location: index.php?page=create_offer");
            exit;
        }

        $end_date_obj = new DateTime($end_date);
        $now = new DateTime();
        if ($end_date_obj <= $now) {
            Massage::set_Massages("danger", "End date must be in the future");
            header("Location: index.php?page=create_offer");
            exit;
        }

        try {
            $offer = Offers::create(
                $db,
                $product_id,
                $title,
                $description,
                $discount_percentage,
                date('Y-m-d H:i:s'),
                $end_date
            );

            if ($offer) {
                Massage::set_Massages("success", "Offer created successfully");
                header("Location: index.php?page=offers");
                exit;
            } else {
                Massage::set_Massages("danger", "Failed to create offer");
                header("Location: index.php?page=create_offer");
                exit;
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php?page=create_offer");
exit;
        }
    }

    public static function update(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=offers");
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $product_id = (int)($_POST['product_id'] ?? 0);
        $discount_percentage = trim($_POST['discount_percentage'] ?? '');
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;
        $end_date = trim($_POST['end_date'] ?? '');

        if ($id <= 0 || empty($title) || empty($description) || $product_id <= 0 || empty($discount_percentage) || empty($end_date)) {
            Massage::set_Massages("danger", "All fields are required");
            header("Location: index.php?page=edit_offer&id=" . $id);
            exit;
        }

        if (!is_numeric($discount_percentage) || $discount_percentage < 0 || $discount_percentage > 100) {
            Massage::set_Massages("danger", "Discount percentage must be between 0 and 100");
            header("Location: index.php?page=edit_offer&id=" . $id);
            exit;
        }

        $end_date_obj = new DateTime($end_date);
        $now = new DateTime();
        if ($end_date_obj <= $now) {
            Massage::set_Massages("danger", "End date must be in the future");
            header("Location: index.php?page=edit_offer&id=" . $id);
            exit;
        }

        try {
            $current_offer = Offers::findById($db, $id);
            if (!$current_offer) {
                Massage::set_Massages("danger", "Offer not found");
                header("Location: index.php?page=offers");
                exit;
            }

            $query = "UPDATE offers SET 
                      title = :title,
                      description = :description,
                      product_id = :product_id,
                      discount_percentage = :discount_percentage,
                      status = :status,
                      end_date = :end_date
                      WHERE id = :id";

            $stmt = $db->prepare($query);
            $result = $stmt->execute([
                'title' => $title,
                'description' => $description,
                'product_id' => $product_id,
                'discount_percentage' => $discount_percentage,
                'status' => $status,
                'end_date' => $end_date,
                'id' => $id
            ]);

            if ($result) {
                Massage::set_Massages("success", "Offer updated successfully");
                header("Location: index.php?page=offers");
                exit;
            } else {
                Massage::set_Massages("danger", "Failed to update offer");
                header("Location: index.php?page=edit_offer&id=" . $id);
                exit;
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php?page=edit_offer&id=" . $id);
            exit;
        }
    }

    public static function delete(PDO $db) {
        if (!isset($_GET['id'])) {
            Massage::set_Massages("danger", "No offer ID specified");
            header("Location: index.php?page=offers");
            exit;
        }

        $id = (int)$_GET['id'];
        
        try {
            $offer = Offers::findById($db, $id);
            if (!$offer) {
                Massage::set_Massages("danger", "Offer not found");
                header("Location: index.php?page=offers");
                exit;
            }

            $query = "DELETE FROM offers WHERE id = :id";
            $stmt = $db->prepare($query);
            $result = $stmt->execute(['id' => $id]);

            if ($result) {
                Massage::set_Massages("success", "Offer deleted successfully");
            } else {
                Massage::set_Massages("danger", "Failed to delete offer");
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
        }

        header("Location: index.php?page=offers");
        exit;
    }
}
Offers_controller::handler( $db);