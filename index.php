<?php

require_once "vendor/autoload.php";
require_once "Config/database.php";

// use database

// database conn

//start from front

// check if front show all page of front another show all page of dashboard



/******************  route system **********************/
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

/****************** dashboard ********************** */

if ($user_type) {
      
    include 'View/dashboard/layout/header.php';

    $routes = [
        'home' => 'View/dashboard/pages/home.php',
        
    ];

    $file = isset($rout[$page]) ? $rout[$page] : './view/error/404.php';
    include $file;

    include 'View/dashboard/layout/footer.php';
    
}

/****************** front ********************** */
if ($user_type) {
    include 'View/front/layout/header.php';

    $routes = [
        'home' => 'View/front/pages/home.php',
    
    ];

    $file = $routes[$page] ?? 'View/Error/404.php';
    include $file;

    include 'View/front/layout/footer.php';
    
}