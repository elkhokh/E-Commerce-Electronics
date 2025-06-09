<?php

namespace App\controller\order;

use App\Massage;
use App\Order;
use App\Orderitem;
use App\Cart;
use App\Validate;
use PDO;
use App\Product;

class Order_controller
{
    public static function handler(PDO $db)
    {
        if (!isset($_SESSION['user'])) {
            Massage::set_Massages("error", "Please login first");
            header('Location: index.php?page=Login');
            exit;
        }

        // var_dump($_POST);
        // exit;
        $user_id =(int) $_SESSION['user']['id'];
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'create':
                self::createOrder($db, $user_id);
               break;
            case 'cancel':
                self::cancelOrder($db, $user_id);
                break;
            case 'update_status':
                self::updateStatus($db);
                break;
       }
    }
    
    private static function createOrder(PDO $db, int $user_id)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $first_name = htmlspecialchars(trim($_POST['first_name']));
                $last_name = htmlspecialchars(trim($_POST['last_name']));
                $company_name = htmlspecialchars(trim($_POST['company_name']));
                $country = htmlspecialchars(trim($_POST['country']));
                $address_street = htmlspecialchars(trim($_POST['address_street']));
                $address_apartment = htmlspecialchars(trim($_POST['address_apartment']));
                $city = htmlspecialchars(trim($_POST['city']));
                $state = htmlspecialchars(trim($_POST['state']));
                $phone = htmlspecialchars(trim($_POST['phone']));
                $email = htmlspecialchars(trim($_POST['email']));
                $payment_method = htmlspecialchars(trim($_POST['payment_method']?? 'cash'));
                $Order_Total=$_POST['Order_Total'];

                $error = Validate::validate_order($first_name, $last_name, $company_name, $country, $address_street, $address_apartment, $city, $state, $phone, $email);
                if (!empty($error)) {
                    Massage::set_Massages("danger", $error);
                    header('Location: index.php?page=checkout');
                    exit;
                }

                $cart = new Cart($user_id);
                $cart->load($db);
                
                if (empty($cart->getItems())) {
                    Massage::set_Massages("error", "Your cart is empty");
                    header('Location: index.php?page=Cart');
                    exit;
                }
                
                $shipping_address = "$address_street, $address_apartment,<br>, $city, $state, $country";
                
                $order = Order::create($db, $user_id, $Order_Total, $shipping_address, $payment_method ,$phone);
                
                if ($order) {
                    foreach ($cart->getItems() as $item) {
                        $product = Product::findById($db, $item->getProductId());
                        if ($product) {
                            $new_quantity = $product->getQuantity() - $item->getQuantity();
                            $product->updateQuantity($db,$new_quantity);
                        }

                        Orderitem::create(
                            $db,
                            $order->getId(),
                            $item->getProductId(),
                            $item->getQuantity(),
                            $item->getProduct()->getFinalPrice($db)
                        );
                    }

                    $cart->clear($db);
                    if (isset($_SESSION['discount_amount'])) {
                     unset( $_SESSION['discount_amount']);   
                     unset( $_SESSION['discount_code']);   
                    }
                    
                    Massage::set_Massages("success", "Order created successfully");
                    header('Location: index.php?page=thank_you&id=' . $order->getId());
                    exit;
                }
            }
            Massage::set_Massages("error", "Failed to create order");
            header('Location: index.php?page=checkout');
            exit;
        } catch (\Exception $e) {
            Massage::set_Massages("error", "An error occurred: " . $e->getMessage());
            header('Location: index.php?page=checkout');
            exit;
        }
    }


    private static function cancelOrder(PDO $db, int $user_id)
    {
        $order_id = $_GET['id'] ?? 0;
        $order = Order::findById($db, $order_id);

        if (!$order || $order->getUserId() !== $user_id) {
            Massage::set_Massages("error", "Order not found");
            header('Location: index.php?page=Order_controller');
            exit;
        }

        if ($order->getStatus() === 'pending') {
            if ($order->updateStatus($db, 'cancelled')) {
                Massage::set_Massages("success", "Order cancelled successfully");
            } else {
                Massage::set_Massages("error", "Failed to cancel order");
            }
        } else {
            Massage::set_Massages("error", "Cannot cancel this order");
        }

        header('Location: index.php?page=all_orders&id=' . $order_id);
        exit;
    }
    private static function updateStatus(PDO $db)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("error", "Invalid request method");
            header('Location: index.php?page=orders');
            exit;
        }

        $order_id = (int)($_POST['order_id'] ?? 0);
        $new_status = htmlspecialchars(trim($_POST['status'] ?? ''));
        $order = Order::findById($db, $order_id);

        if (!$order) {
            Massage::set_Massages("error", "Order not found");
            header('Location: index.php?page=orders');
            exit;
        }

        $valid_statuses = ['pending', 'processing', 'completed', 'cancelled'];
        if (!in_array($new_status, $valid_statuses)) {
            Massage::set_Massages("error", "Invalid status");
            header('Location: index.php?page=order_detail&id=' . $order_id);
            exit;
        }

        if ($order->updateStatus($db, $new_status)) {
            Massage::set_Massages("success", "Order status updated successfully");
        } else {
            Massage::set_Massages("error", "Failed to update order status");
        }

        header('Location: index.php?page=order_detail&id=' . $order_id);
        exit;
    }

}

// Execute controller
Order_controller::handler($db);
