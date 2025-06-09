<!doctype html>
<html class="no-js" lang="en">

<!--   03:20:39 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ElectroWorld - Your Electronics Universe</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="public/assets/front/img/favicon.ico">
    
    <!-- CSS 
    ========================= -->
   

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="public/assets/front/css/plugins.css">
    
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="public/assets/front/css/style.css">
    <link rel="stylesheet" href="public/assets/front/css/custom.css">

</head>
<?php 
use App\Massage;
use App\Cart;
use App\User;


$cart = null;
$user_email = null;
$profile_image ='Public\assets\front\img\users\default_user.jpg';
if (isset($db)) {
    if (isset($_SESSION['user']['id'])) {
        $cart = new Cart($_SESSION['user']['id']);
        $cart->load($db);
         $profile_image =$_SESSION['user'] ? User::get_profile_image($db, $_SESSION['user']['id']) : 'Public/assets/front/img/users/default_user.png';
        try {
            $user = User::find_by_id($db, $_SESSION['user']['id']);
            $user_email = $user->get_email();
        } catch (Exception $e) {
            $user_email = null;
        }
    }
} else {
    include 'View/Error/Maintenance.php';
    exit;
}
//var_dump(   $user = User::find_by_id($db, $_SESSION['user']['id'])); 
?>

<body>

    <!--header area start-->
    <!--Offcanvas menu area start-->
    <div class="off_canvars_overlay">
            
    </div>
    <div class="Offcanvas_menu">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="canvas_open">
                        <a href="javascript:void(0)"><i class="ion-navicon"></i></a>
                    </div>
                    <div class="Offcanvas_menu_wrapper">
                        <div class="canvas_close">
                              <a href="javascript:void(0)"><i class="ion-android-close"></i></a>  
                        </div>
                        <div class="support_info">
                            <?php if (isset($_SESSION['user']) && $user_email): ?>
                                <p>Email: <a href="mailto:"><?= $user_email ?></a></p>
                            <?php else: ?>
                                <p>Welcome, Guest!</p>
                            <?php endif; ?>
                        </div>
                        <div class="top_right text-right">
                            <ul>
                               <?php if (isset($_SESSION['user'])): ?>
                                   <li><a href="index.php?page=Logout">Logout</a></li>
                               <?php else: ?>
                                   <li><a href="index.php?page=Login">Login</a></li>
                                   <li><a href="index.php?page=register">Register</a></li>
                               <?php endif; ?>
                               <li>
                                   <a href="index.php?page=my_account">
                                       <div class="user-mini-profile">
                                           <img src="<?php echo $profile_image; ?>" alt="User Profile" class="user-mini-avatar">
                                       </div></a>
                               </li> 
                            </ul>
                        </div> 
                        <div class="search_container">
                           <form action="index.php?page=product_search" method="post">
                                <div class="search_box">
                                    <input type="text" name="name" placeholder="Search product..." >
                                    <button type="submit">Search</button> 
                                </div>
                            </form>
                        </div> 
                        
                        <div class="middel_right_info">
                            <div class="header_wishlist">
                                <a href="index.php?page=Wishlist"><img src="public/assets/front/img/user.png" alt="user"></a>
                            </div>
                            <div class="mini_cart_wrapper">
                                <a href="index.php?page=Cart"><img src="public/assets/front/img/shopping-bag.png" alt="cart"></a>
                                <?php if($cart && $cart->getItemsCount() > 0): ?>
                                    <span class="cart_quantity"><?=$cart->getItemsCount()?></span>
                                <?php endif; ?>
                                <!--mini cart-->
                                <div class="mini_cart">
                                    <?php if($cart && $cart->getItemsCount() > 0): ?>
                                        <?php foreach ($cart->getItems() as $item): ?>
                                            <div class="cart_item">
                                                <div class="cart_img">
                                                    <a href="#"><img src="<?=$item->getProduct()->getMainImage()?>" alt=""></a>
                                                </div>
                                                <div class="cart_info">
                                                    <a href="#"><?=$item->getProduct()->getName()?></a>
                                                    <p>Qty: <?=$item->getQuantity()?> X <span> <?=$item->getProduct()->getPrice()?> </span></p>      
                                                </div>
                                                <div class="cart_remove">
                                                    <a href="index.php?page=Cart_controller&action=remove&id=<?=$item->getProductId()?>"><i class="ion-android-close"></i></a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="mini_cart_table">
                                            <div class="cart_total">
                                                <span>Sub total:</span>
                                                <span class="price">$<?=$cart->getFinalTotal()?></span>
                                            </div>
                                            <div class="cart_total mt-10">
                                                <span>total:</span>
                                                <span class="price">$<?=$cart->getFinalTotal()?></span>
                                            </div>
                                        </div>

                                        <div class="mini_cart_footer">
                                            <div class="cart_button">
                                                <a href="index.php?page=Cart">View cart</a>
                                            </div>
                                            <div class="cart_button">
                                                <a href="checkout.html">Checkout</a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info">Your cart is empty .</div>
                                    <?php endif; ?>
                                </div>
                                <!--mini cart end-->
                            </div>
                        </div>
                        <div id="menu" class="text-left ">
                            <ul class="offcanvas_main_menu">
                                <li class="menu-item-has-children active">
                                    <a href="index.php?page=home">Home</a>
                                </li>
                                <li><a class="active" href="">page <i class="fa fa-angle-down"></i></a>
                                    <ul class="sub_menu pages">
                                       <li><a href="index.php?page=about">About Us</a></li>
                                       <li><a href="index.php?page=frequently">Frequently Questions</a></li>
                                       <li><a href="index.php?page=privacy_policy">Privacy Policy</a></li>
                                   </ul>
                                </li>
                                <li><a class="active" href="index.php?page=All_product">Product </a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="index.php?page=All_Blogs">blog</a>
                                </li>
                            </ul>
                        </div>

                        <div class="Offcanvas_footer">
                            <span><a href="#"><i class="fa fa-envelope-o"></i> <?= $user_email ?></a></span>
                            <ul>
                                <li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li class="pinterest"><a href="#"><i class="fa fa-pinterest-p"></i></a></li>
                                <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Offcanvas menu area end-->
    
    <header>
        <div class="main_header">
            <!--header top start-->
            <div class="header_top">
                <div class="container">  
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">
                            <div class="support_info">
                                <?php if (isset($_SESSION['user']) && $user_email): ?>
                                    <p>Email: <a href="mailto:"><?= $user_email ?></a></p>
                                <?php else: ?>
                                    <p>Welcome, Guest!</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="top_right text-right">
                                <ul> 
                                    <?php if (isset($_SESSION['user'])): ?>
                                        <li><a href="index.php?page=Logout">Logout</a></li>
                                    <?php else: ?>
                                        <li><a href="index.php?page=Login">Login</a></li>
                                        <li><a href="index.php?page=register">Register</a></li>
                                    <?php endif; ?>
                                    <li>
                                        <a href="index.php?page=my_account">

                                       <div class="user-mini-profile">
                                           <img src="<?php echo $profile_image; ?>" alt="User Profile" class="user-mini-avatar">
                                       </div>
                             
                                    </a></li> 
                                </ul>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
            <!--header top start-->
            <!--header middel start-->
            <div class="header_middle">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-6">
                            <div class="logo">
                                <a href="index.php?page=home"><img src="public/assets/front/img/logo/logo.jpg" alt="ElectroWorld"></a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-6">
                            <div class="middel_right">
                                <div class="search_container">
                           <form action="index.php?page=product_search" method="post">
                                <div class="search_box">
                                    <input type="text" name="name" placeholder="Search product..." >
                                    <button type="submit">Search</button> 
                                </div>
                            </form>
                                </div>
                                <div class="middel_right_info">
                                    <div class="header_wishlist">
                                        <a href="index.php?page=Wishlist"><img src="public/assets/front/img/user.png" alt="user"></a>
                                    </div>
                                    <div class="mini_cart_wrapper">
                                        <a href="index.php?page=Cart"><img src="public/assets/front/img/shopping-bag.png" alt="cart"></a>
                                        <?php if($cart && $cart->getItemsCount() > 0): ?>
                                            <span class="cart_quantity"><?=$cart->getItemsCount()?></span>
                                        <?php endif; ?>
                                        <!--mini cart-->
                                        <div class="mini_cart">
                                            <?php if($cart && $cart->getItemsCount() > 0): ?>
                                                <?php foreach ($cart->getItems() as $item): ?>
                                                    <div class="cart_item">
                                                        <div class="cart_img">
                                                            <a href="#"><img src="<?=$item->getProduct()->getMainImage()?>" alt=""></a>
                                                        </div>
                                                        <div class="cart_info">
                                                            <a href="#"><?=$item->getProduct()->getName()?></a>
                                                            <p>Qty: <?=$item->getQuantity()?> X <span> <?=$item->getProduct()->getPrice()?> </span></p>      
                                                        </div>
                                                        <div class="cart_remove">
                                                            <a href="index.php?page=Cart_controller&action=remove&id=<?=$item->getProductId()?>"><i class="ion-android-close"></i></a>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                                <div class="mini_cart_table">
                                                    <div class="cart_total">
                                                        <span>Sub total:</span>
                                                        <span class="price">$<?=$cart->getFinalTotal()?></span>
                                                    </div>
                                                    <div class="cart_total mt-10">
                                                        <span>total:</span>
                                                        <span class="price">$<?=$cart->getFinalTotal()?></span>
                                                    </div>
                                                </div>

                                                <div class="mini_cart_footer">
                                                    <div class="cart_button">
                                                        <a href="index.php?page=Cart">View cart</a>
                                                    </div>
                                                    <div class="cart_button">
                                                        <a href="index.php?page=checkout">Checkout</a>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-info">Your cart is empty .</div>
                                            <?php endif; ?>
                                        </div>
                                        <!--mini cart end-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--header middel end-->
            <!--header bottom satrt-->
            <div class="main_menu_area">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-12">
                            <div class="main_menu menu_position"> 
                                <nav>  
                                    <ul>
                                        <li><a href="index.php?page=home">home</a></li>
                                        <li><a class="active" href="">Page <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu pages">
                                                <li><a href="index.php?page=about">About Us</a></li>
                                                <li><a href="index.php?page=frequently">Frequently Questions</a></li>
                                                <li><a href="index.php?page=privacy_policy">Privacy Policy</a></li>
                                            </ul>
                                        </li>
                                        <li><a class="active" href="index.php?page=All_product">Product </a>
                                        </li>
                                        <li><a href="index.php?page=All_Blogs">blogs</a>
                                        </li>
                                    </ul>  
                                </nav> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--header bottom end-->
        </div> 
    </header>
    <?php
    Massage::show_Massages();
    ?>

<style>
    .user-mini-profile {
        display: inline-block;
        margin-left: 10px;
        vertical-align: middle;
    }
    
    .user-mini-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #007bff;
        vertical-align: middle;
    }
</style>