<?php

namespace App\controller\product;

use App\Review;
use App\Product;
use App\User;
use App\Massage;
use PDO;

class Review_controller {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public static function handler(PDO $db) {
        if (!isset($_SESSION['user'])) {
            Massage::set_Massages("danger", "Please login to add a review");
            header("Location: index.php?page=Login");
            exit;
        }

        if (!isset($_GET['action'])) {
            Massage::set_Massages("danger", "No action specified");
            header("Location: index.php");
            exit;
        }

        $user_id = (int)$_SESSION['user']['id'];
        $action = $_GET['action'];

        switch ($action) {
            case 'add':
                self::addReview($db, $user_id);
                break;
            // case 'delete':
            //     self::deleteReview($db, $user_id);
            //     break;
            default:
                Massage::set_Massages("danger", "Invalid action");
                header("Location: index.php");
                exit;
        }
    }

    public static function addReview($db, $user_id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request method");
            header("Location: index.php");
            exit;
        }

        $product_id =(int) filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $rating =(int) filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
        $comment = trim($_POST['comment'] ?? '');
        // var_dump(Review::create($db, $user_id, $product_id, $rating, $comment));
        // exit;
        if (!$product_id || !$rating || empty($comment)) {
            Massage::set_Massages("danger", "Please fill all required fields");
            header("Location: index.php?page=product_details&id=" . $product_id);
            exit;
        }

        if ($rating < 1 || $rating > 5) {
            Massage::set_Massages("danger", "Rating must be between 1 and 5");
            header("Location: index.php?page=product_details&id=" . $product_id);
            exit;
        }

        if (Review::createOrUpdate($db, $user_id, $product_id, $rating, $comment)) {
            Massage::set_Massages("success", "Review added successfully");
        } else {
            Massage::set_Massages("danger", "Failed to add review");
        }

        header("Location: index.php?page=product_details&id=" . $product_id);
        exit;
    }

    // public static function deleteReview($db, $user_id): void {
    //     if (!isset($_GET['review_id'])) {
    //         Massage::set_Massages("danger", "Review ID not specified");
    //         header("Location: index.php");
    //         exit;
    //     }

    //     $review_id = filter_input(INPUT_GET, 'review_id', FILTER_VALIDATE_INT);
    //     if (!$review_id) {
    //         Massage::set_Massages("danger", "Invalid review ID");
    //         header("Location: index.php");
    //         exit;
    //     }

    //     $reviews = Review::getProductReviews($db, $review_id);
    //     if (empty($reviews)) {
    //         Massage::set_Massages("danger", "Review not found");
    //         header("Location: index.php");
    //         exit;
    //     }

    //     $review = $reviews[0];
    //     if ($review->getUserId() !== $user_id) {
    //         Massage::set_Massages("danger", "You can only delete your own reviews");
    //         header("Location: index.php");
    //         exit;
    //     }

    //     if ($review->delete($db)) {
    //         Massage::set_Massages("success", "Review deleted successfully");
    //     } else {
    //         Massage::set_Massages("danger", "Failed to delete review");
    //     }

    //     header("Location: index.php?page=product_details&id=" . $review->getProductId());
    //     exit;
    // }
}

Review_controller::handler($db);