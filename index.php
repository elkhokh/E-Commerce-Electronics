<?php
session_start();

require_once "vendor/autoload.php";
require_once "Config/database.php";

// use database
use App\Database\Database;
use App\User;

/******************  route system **********************/
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if (isset($_SESSION['user']['id'])) {
 $user_id =  (int)$_SESSION['user']['id']  ;
}
$db = null;


if ($page !== 'Maintenance') {
    try {
        $db = Database::get_instance($config)->get_connection();
    } catch (PDOException $th) {
        $page = 'Maintenance'; 
    }
}


//start from front
// check if front show all page of front another show all page of dashboard
$user_type =  isset($_SESSION['user']['id']) ?  User::getRole($db,$user_id) : 'user';
// var_dump($user_type);
// exit;

/****************** dashboard ********************** */
if ($user_type==='admin') {
      ob_start();
    include 'View/dashboard/layout/header.php';
    include 'View/dashboard/layout/nav.php';
    include 'View/dashboard/layout/sidebar.php';
    $routes = [
        'home' => 'View/dashboard/pages/home.php',
        'Logout' => 'App/controller/auth/Logout_controller.php',
    ];
    $file = isset($routes[$page]) ? $routes[$page] : './view/error/404.php';
    include $file;
    include 'View/dashboard/layout/footer.php';
    ob_end_flush();
}

/****************** front ********************** */
if ($user_type==='user') {

  


    $routes = [
        'home' => 'View/front/pages/home.php',
        'Maintenance' => 'View/Error/Maintenance.php',
        'register' => 'View/front/pages/auth/register.php',
        'forget_password' => 'View/front/pages/auth/forgetPassword.php',
        'Cart' => 'View/front/pages/Cart_page.php',
        'register_controller' => 'App/controller/auth/register_controller.php',
        'Login_controller' => 'App/controller/auth/Login_controller.php',
        'update_profile' => 'App/controller/auth/update_profile_controller.php',
        'Order_controller' => 'App/controller/order/Order_controller.php',
        'wishlist_controller' => 'App/controller/wishlist_controller.php',
        'Cart_controller' => 'App/controller/Cart/Cart_controller.php',
        'Comment_controller' => 'App/controller/Blogs/Comment_controller.php',
        'discount_controller' => 'App/controller/Cart/discount_controller.php',
        'Logout' => 'App/controller/auth/Logout_controller.php',
        'change_password' => 'App/controller/auth/change_password_controller.php',
        'product_controller' => 'App/controller/product/product_controller.php',
        'product_details' => 'View/front/pages/product/product_details.php',
        'All_Blogs' => 'View/front/pages/Blogs/All_Blogs.php',
        'contact_us' => 'View/front/pages/contact_us.php',
        'tracking' => 'View/front/pages/tracking.php',
        'blog_details' => 'View/front/pages/Blogs/blog_details.php',
        'checkout' => 'View/front/pages/checkout.php',
        'about' => 'View/front/pages/about.php',
        'frequently' => 'View/front/pages/frequently.php',
        'my_account' => 'View/front/pages/my_account.php',
        'privacy_policy' => 'View/front/pages/privacy_policy.php',
        'product_search' => 'View/front/pages/product/product_search.php',
        'thank_you' => 'View/front/pages/thank_you.php',
        'all_orders' => 'View/front/pages/all_orders.php',
        'Wishlist' => 'View/front/pages/product/Wishlist.php',
        'All_product' => 'View/front/pages/product/All_product.php',
        'Login' => 'View/front/pages/auth/login.php'
    ];
    ob_start(); 
    include 'View/front/layout/header.php';

    $file = isset($routes[$page]) ? $routes[$page] : 'View/Error/404.php';
    include $file;

    include 'View/front/layout/footer.php';
     ob_end_flush();
  
}