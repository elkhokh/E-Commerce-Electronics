<?php

namespace App\controller\Cart;

use App\Cart;
use App\Discount;
use App\Massage;

class discount_controller {
    public static function handler($db) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::apply_discount($db);
        }
    }

    private static function apply_discount($db) {
        try {
            // Check user authentication
        if (!isset($_SESSION['user']['id'])) {
                Massage::set_Massages("error", "Please login first");
            header('Location: index.php?page=Login');
            exit;
        }

            // Validate discount code
        if (!isset($_POST['discount_code']) || empty($_POST['discount_code'])) {
                Massage::set_Massages("error", "Please enter a discount code");
            header('Location: index.php?page=Cart');
            exit;
        }

        $discount_code = trim($_POST['discount_code']);
        $discount = Discount::find_by_code($db, $discount_code);

            // Validate discount
            if (!$discount) {
                Massage::set_Massages("error", "Invalid discount code");
                header('Location: index.php?page=Cart');
                exit;
            }

            if (!$discount->is_valid()) {
                Massage::set_Massages("error", "This discount code is no longer valid");
            header('Location: index.php?page=Cart');
            exit;
        }
     
            // Load and validate cart
        $cart = new Cart($_SESSION['user']['id']);
        $cart->load($db);

        if ($cart->getItemsCount() == 0) {
                Massage::set_Massages("error", "Your cart is empty");
                header('Location: index.php?page=Cart');
                exit;
            }

            // Get cart total and discount value
            $cart_total = $cart->getFinalTotal();
            $discount_value = (int)$discount->getValue();

            // Ensure discount doesn't exceed cart total
            $discount_amount = min($discount_value, $cart_total);
            
            $_SESSION['discount_amount'] = $discount_amount;
            $_SESSION['discount_code'] = $discount_code;

            // Deactivate the discount code instead of deleting it
            $discount->setStatus(0);
            $discount->save($db);

            Massage::set_Massages("success", "Discount of $" . $discount_amount . " applied successfully");
            header('Location: index.php?page=Cart');
            exit;

        } catch (\Exception $e) {
            // Log the error
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            
            Massage::set_Massages("error", "An error occurred while applying the discount");
        header('Location: index.php?page=Cart');
        exit;
    }
    }
}

discount_controller::handler($db);