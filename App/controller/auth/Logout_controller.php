<?php
use App\Massage;
//session_start();
session_unset();
session_destroy();
Massage::set_Massages("success", "Logout successfully");
header('Location:index.php?page=Login ');
exit;

?>