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
    $user_id = (int)$_SESSION['user']['id'];
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
$user_type = 'user';
 if (isset($db)) {
    
  
$user_type =  isset($_SESSION['user']['id']) ?  User::getRole($db,$user_id) : 'user';
}
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
        'change_password' => 'View/dashboard/pages/auth/change_password.php',
        'Categories' => 'View/dashboard/pages/category/Categories.php',
        'Create_Category' => 'View/dashboard/pages/category/Create_Category.php',
        'Categories_controller' => 'App/controller/Categories/Categories_controller.php',
        'Search' => 'View/dashboard/pages/category/Search.php',
        'Create_product' => 'View/dashboard/pages/Product/Create_product.php',
        'products' => 'View/dashboard/pages/Product/products.php',
        'Search_product' => 'View/dashboard/pages/Product/Search_product.php',
        'edit_product' => 'View/dashboard/pages/Product/edit_product.php',
        'product_controller' => 'App/controller/product/product_controller.php',
        'Order_controller' => 'App/controller/order/Order_controller.php',
        'orders' => 'View/dashboard/pages/Order/orders.php',
        'order_detail' => 'View/dashboard/pages/Order/order_detail.php',
        'Search_order' => 'View/dashboard/pages/Order/Search_order.php',
        'offers' => 'View/dashboard/pages/Offers/offers.php',
        'create_offer' => 'View/dashboard/pages/Offers/create_offer.php',
        'offers_controller' => 'App/controller/Offers/offers_controller.php',
        'brands' => 'View/dashboard/pages/Brand/Brands.php',
        'create_brand' => 'View/dashboard/pages/Brand/create_brand.php',
        'brand_controller' => 'App/controller/Brand/brand_controller.php',
        'user' => 'View/dashboard/pages/User/user.php',
        'create_user' => 'View/dashboard/pages/User/create_user.php',
        'user_search' => 'View/dashboard/pages/User/user_search.php',
        'user_controller' => 'App/controller/User/user_controller.php',
        'blogs' => 'View/dashboard/pages/Blog/blogs.php',
        'blog_controller' => 'App/controller/Blogs/blog_controller.php',
        'Comment_controller' => 'App/controller/Blogs/Comment_controller.php',
        'Reply_controller' => 'App/controller/Blogs/Reply_controller.php',
        'create_blog' => 'View/dashboard/pages/Blog/create_blog.php',
        'blog_comment' => 'View/dashboard/pages/Blog/blog_comment.php',
        'blog_search' => 'View/dashboard/pages/Blog/blog_search.php',
        'error' => 'View/dashboard/pages/Error/error.php',
        'discount' => 'View/dashboard/pages/Discount/discount.php',
        'discount_controller' => 'App/controller/Discount/discount_controller.php',
        'discount_create' => 'View/dashboard/pages/Discount/discount_create.php'

    ];
    $file = isset($routes[$page]) ? $routes[$page] : './View/dashboard/pages/home.php';
    include $file;
    include 'View/dashboard/layout/footer.php';
    ob_end_flush();
}

/****************** front ********************** */
if ($user_type==='user') {

  


    $routes = [
        'home' => 'View/front/pages/home.php',
        'Maintenance' => 'View/Error/Maintenance.php',
        'admin' => 'View/front/pages/auth/admin.php',
        'register' => 'View/front/pages/auth/register.php',
        'forget_password' => 'View/front/pages/auth/forgetPassword.php',
        'Cart' => 'View/front/pages/Cart_page.php',
        'verify_email' => 'View/front/pages/auth/verify_email.php',
        'verify_email_controller' => 'App/controller/auth/verify_email_controller.php',
        'register_controller' => 'App/controller/auth/register_controller.php',
        'Login_controller' => 'App/controller/auth/Login_controller.php',
        'update_profile' => 'App/controller/auth/update_profile_controller.php',
        'Order_controller' => 'App/controller/order/Order_controller.php',
        'wishlist_controller' => 'App/controller/wishlist_controller.php',
        'Cart_controller' => 'App/controller/Cart/Cart_controller.php',
        'Comment_controller' => 'App/controller/Blogs/Comment_controller.php',
        'Reply_controller' => 'App/controller/Blogs/Reply_controller.php',
        'Contact_us_controller' => 'App/controller/Contact_us_controller.php',
        'Review_controller' => 'App/controller/product/Review_controller.php',
        'discount_controller' => 'App/controller/Discount/discount_controller.php',
        'Logout' => 'App/controller/auth/Logout_controller.php',
        'change_password' => 'App/controller/auth/change_password_controller.php',
        'product_controller' => 'App/controller/product/product_controller.php',
        'product_details' => 'View/front/pages/product/product_details.php',
        'All_Blogs' => 'View/front/pages/Blogs/All_Blogs.php',
        'contact_us' => 'View/front/pages/contact_us.php',
        'tracking' => 'View/front/pages/tracking.php',
        'blog_details' => 'View/front/pages/Blogs/blog_details.php',
        'checkout' => 'View/front/pages/checkout.php',
        'blog_search' => 'View/front/pages/Blogs/blog_search.php',
        'about' => 'View/front/pages/about.php',
        'frequently' => 'View/front/pages/frequently.php',
        'my_account' => 'View/front/pages/my_account.php',
        'privacy_policy' => 'View/front/pages/privacy_policy.php',
        'product_search' => 'View/front/pages/product/product_search.php',
        'thank_you' => 'View/front/pages/thank_you.php',
        'all_orders' => 'View/front/pages/all_orders.php',
        'Wishlist' => 'View/front/pages/product/Wishlist.php',
        'All_product' => 'View/front/pages/product/All_product.php',
        'reset_password' => 'View/front/pages/auth/reset_password.php',
        'request_password_reset_controller' => 'App/controller/auth/request_password_reset_controller.php',
        'Login' => 'View/front/pages/auth/login.php'
    ];
    if ($page==='admin') {
    $file = isset($routes[$page]) ? $routes[$page] : 'View/Error/404.php';
    include $file;
    }else {
        
    
    ob_start(); 
    include 'View/front/layout/header.php';

    $file = isset($routes[$page]) ? $routes[$page] : 'View/Error/404.php';
    include $file;

    include 'View/front/layout/footer.php';
     ob_end_flush();
  }
}