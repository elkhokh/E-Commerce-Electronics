<?php

use App\Cart;
use App\CartItem;
use App\Product;
use App\Massage;
use App\Validate;

class Cart_controller    
{
    public static function handler($db){

        if (!isset($_SESSION['user']['id'])) {
            Massage::set_Massages('error', 'Please login first to add items to cart');
            header('Location: index.php?page=Cart');
            exit;
        }
        $user_id = $_SESSION['user']['id'];
        $cart = new Cart($user_id);
        $cart->load($db);

        $action = $_GET['action'] ?? null;

        switch ($action) {
            case 'add':
                $product_id = (int)$_POST['id'] ?? null;
                $quantity =(int) $_POST['quantity'] ?? 1;
            //    var_dump($quantity,$product_id);
            //     exit;
                if (!$product_id || !is_numeric($quantity) || $quantity < 1) {
                    Massage::set_Massages('error', 'Invalid product or quantity');
                    break;
                }
                if ($cart->addItem($db, $product_id, $quantity)) {
                    Massage::set_Massages('success', 'Product added to cart');
                } else {
                    Massage::set_Massages('error', 'Could not add product to cart');
                }
                break;

            case 'remove':
                $product_id =(int) $_GET['id'] ?? null;
                //  var_dump($product_id);
                //  exit;
                if (!$product_id) {
                    Massage::set_Massages('error', 'Invalid product');
                    break;
                }
                if ($cart->removeItem($db, $product_id)) {
                    Massage::set_Massages('success', 'Product removed from cart');
                } else {
                    Massage::set_Massages('error', 'Could not remove product from cart');
                }
                break;

            case 'chang':
                $product_id =(int) $_POST['id'] ?? null;
                $quantity =(int) $_POST['quantity'] ?? 1;
                //   var_dump($quantity,$product_id);
                //    exit;
                if (!$product_id || !is_numeric($quantity) || $quantity < 1) {
                    Massage::set_Massages('error', 'Invalid product or quantity');
                    break;
                }
                if ($cart->updateItemQuantity($db, $product_id, $quantity)) {
                    Massage::set_Massages('success', ' updated Quantity');
                } else {
                    Massage::set_Massages('error', 'Could not update cart');
                }
                break;

            case 'clear':
                if ($cart->clear($db)) {
                    Massage::set_Massages('success', 'Cart cleared');
                } else {
                    Massage::set_Massages('error', 'Could not clear cart');
                }
                break;

            default:
               
                break;
        }


        header('Location: index.php?page=Cart');
        exit;
    }
}

Cart_controller::handler($db);

