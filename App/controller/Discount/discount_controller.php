<?php

namespace App\controller\Discount;

use App\Discount;
use App\Massage;
use App\Cart;

class discount_controller {
    public static function handler($db) {
      
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'create':
                        self::create($db);
                        break;
                    case 'update':
                        self::update($db);
                        break;
                    case 'delete':
                        self::delete($db);
                        break;
                    case 'apply_discount':
                        self::apply_discount($db);
                        break;
                }
            }
        
    }

    private static function create($db) {
        
        if (!isset($_SESSION['user']['id']) ) {
            Massage::set_Massages("error", "No access");
            header('Location: index.php?page=Login');
            exit;
        }

  
        if (!isset($_POST['value']) || !isset($_POST['status'])) {
            Massage::set_Massages("error", "Missing required fields");
            header('Location: index.php?page=discount');
            exit;
        }

   
        $value = filter_var($_POST['value'], FILTER_VALIDATE_INT);
        $status = filter_var($_POST['status'], FILTER_VALIDATE_INT);

        if ($value === false || $value <= 0) {
            Massage::set_Massages("error", "Invalid discount value");
            header('Location: index.php?page=discount');
            exit;
        }

        if ($status !== 0 && $status !== 1) {
            Massage::set_Massages("error", "Invalid status value");
            header('Location: index.php?page=discount_create');
            exit;
        }


        $discount = new Discount($value);
        $discount->setStatus($status);

        if ($discount->save($db)) {
            Massage::set_Massages("success", "Discount created successfully");
        } else {
            Massage::set_Massages("error", "Failed to create discount");
        }

        header('Location: index.php?page=discount');
        exit;
    }

    private static function update($db) {
    
   


        if (!isset($_POST['id']) || !isset($_POST['value']) || !isset($_POST['status'])) {
            Massage::set_Massages("error", "Missing required fields");
            header('Location: index.php?page=Discount');
            exit;
        }

        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $value = filter_var($_POST['value'], FILTER_VALIDATE_INT);
        $status = filter_var($_POST['status'], FILTER_VALIDATE_INT);

        if ($id === false || $id <= 0) {
            Massage::set_Massages("error", "Invalid discount ID");
            header('Location: index.php?page=discount');
            exit;
        }

        if ($value === false || $value <= 0) {
            Massage::set_Massages("error", "Invalid discount value");
            header('Location: index.php?page=discount');
            exit;
        }

        if ($status !== 0 && $status !== 1) {
            Massage::set_Massages("error", "Invalid status value");
            header('Location: index.php?page=discount');
            exit;
        }

 
        $discount = Discount::findById($db, $id);
        if (!$discount) {
            Massage::set_Massages("error", "Discount not found");
            header('Location: index.php?page=discount');
            exit;
        }

        $discount->setValue($value);
        $discount->setStatus($status);

        if ($discount->save($db)) {
            Massage::set_Massages("success", "Discount updated successfully");
        } else {
            Massage::set_Massages("error", "Failed to update discount");
        }

        header('Location: index.php?page=discount');
        exit;
    }

    private static function delete($db) {
        //     var_dump($_GET['id']);
        //  exit;

        if (!isset($_GET['id'])) {
            Massage::set_Massages("error", "Missing discount ID");
            header('Location: index.php?page=discount');
            exit;
        }

   
        $id = filter_var((int)$_GET['id'], FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            Massage::set_Massages("error", "Invalid discount ID");
            header('Location: index.php?page=discount');
            exit;
        }

 
        $discount = Discount::findById($db, $id);
        if (!$discount) {
            Massage::set_Massages("error", "Discount not found");
            header('Location: index.php?page=discount');
            exit;
        }

        if ($discount->delete($db)) {
            Massage::set_Massages("success", "Discount deleted successfully");
        } else {
            Massage::set_Massages("error", "Failed to delete discount");
        }

        header('Location: index.php?page=discount');
        exit;
    }
    private static function apply_discount($db) {
        try {
            
            if (!isset($_SESSION['user']['id'])) {
                Massage::set_Massages("error", "Please login first");
                header('Location: index.php?page=Login');
                exit;
            }

            
            if (!isset($_POST['discount_code']) || empty($_POST['discount_code'])) {
                Massage::set_Massages("error", "Please enter a discount code");
                header('Location: index.php?page=Cart');
                exit;
            }

            $discount_code = trim($_POST['discount_code']);
            $discount = Discount::find_by_code($db, $discount_code);

            
            if (!$discount) {
                Massage::set_Massages("error", "Invalid discount code");
                header('Location: index.php?page=Cart');
                exit;
            }
            // var_dump($discount->is_valid());
            // exit;

            if (!$discount->is_valid()) {
                Massage::set_Massages("error", "This discount code is no longer valid");
                header('Location: index.php?page=Cart');
                exit;
            }

            
            $cart = new Cart($_SESSION['user']['id']);
            $cart->load($db);

            if ($cart->getItemsCount() == 0) {
                Massage::set_Massages("error", "Your cart is empty");
                header('Location: index.php?page=Cart');
                exit;
            }

            
            $cart_total = $cart->getFinalTotal();
            $discount_value = $discount->getValue();

            
            $discount_amount = min($discount_value, $cart_total);
            
            $_SESSION['discount_amount'] = $discount_amount;
            $_SESSION['discount_code'] = $discount_code;

            
            $discount->setStatus(0);
            $discount->save($db);

            Massage::set_Massages("success", "Discount of $" . $discount_amount . " applied successfully");
            header('Location: index.php?page=Cart');
            exit;

        } catch (\Exception $e) {
            
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
