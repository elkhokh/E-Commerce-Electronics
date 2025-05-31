<?php
session_start();

require_once "vendor/autoload.php";
require_once "Config/database.php";

// use database
use App\Database\Database;

/******************  route system **********************/
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$db = null;


if ($page !== 'Maintenance') {
    try {
        $db = Database::get_instance($config)->get_connection();
    } catch (PDOException $th) {
        $page = 'Maintenance'; 
    }
}
            // $_SESSION["user"] = [
            //     "name" => "name",
            //     "id" => "1"
            // ];

//start from front
// check if front show all page of front another show all page of dashboard
$user_type = 1;

/****************** dashboard ********************** */
// if ($user_type) {
//     include 'View/dashboard/layout/header.php';
//     $routes = [
//         'home' => 'View/dashboard/pages/home.php',
//     ];
//     $file = isset($rout[$page]) ? $rout[$page] : './view/error/404.php';
//     include $file;
//     include 'View/dashboard/layout/footer.php';
// }

/****************** front ********************** */
if ($user_type) {

  


    $routes = [
        'home' => 'View/front/pages/home.php',
        'Maintenance' => 'View/Error/Maintenance.php',
        'register' => 'View/front/pages/auth/register.php',
        'forget_password' => 'View/front/pages/auth/forgetPassword.php',
        'Cart' => 'View/front/pages/Cart_page.php',
        'register_controller' => 'App/controller/auth/register_controller.php',
        'Login_controller' => 'App/controller/auth/Login_controller.php',
        'Order_controller' => 'App/controller/order/Order_controller.php',
        'wishlist_controller' => 'App/controller/wishlist_controller.php',
        'Cart_controller' => 'App/controller/Cart/Cart_controller.php',
        'Comment_controller' => 'App/controller/Blogs/Comment_controller.php',
        'discount_controller' => 'App/controller/Cart/discount_controller.php',
        'Logout' => 'App/controller/auth/Logout_controller.php',
        'change_password' => 'App/controller/auth/change_password_controller.php',
        'product_details' => 'View/front/pages/product/product_details.php',
        'All_Blogs' => 'View/front/pages/Blogs/All_Blogs.php',
        'contact_us' => 'View/front/pages/contact_us.php',
        'tracking' => 'View/front/pages/tracking.php',
        'blog_details' => 'View/front/pages/Blogs/blog_details.php',
        'checkout' => 'View/front/pages/checkout.php',
        'thank_you' => 'View/front/pages/thank_you.php',
        'all_orders' => 'View/front/pages/all_orders.php',
        'Login' => 'View/front/pages/auth/login.php'
    ];
    ob_start(); 
    include 'View/front/layout/header.php';

    $file = isset($routes[$page]) ? $routes[$page] : 'View/Error/404.php';
    include $file;

    include 'View/front/layout/footer.php';
     ob_end_flush();
  
}