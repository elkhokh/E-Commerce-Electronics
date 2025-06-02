<?php

use App\Massage;
use App\User;
use App\Traits\MangesFiles;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
      $user_id=htmlspecialchars(trim($_POST['user_id'])) ;
   $file=$_FILES['userImage'] ;

    $sta=MangesFiles::UploadFile($file,['jpg','png','jpeg'],'Public/assets/front/img/users');
  
        if (empty($sta)||isset($sta['error'])) {
        Massage::set_Massages("danger",$sta['error']??'Failed to upload file' );
        header('Location:./index.php?page=my_account');
        exit;
    }
        if (isset($sta['success'])) {
       $old_imag= User::get_profile_image($db,$user_id);
    //    var_dump($old_imag);
    //    exit;
       unlink($old_imag);
        User::update_profile_image($db,$user_id,$sta['path']);
        Massage::set_Massages("success","update image  successfully" );
        header('Location:./index.php?page=my_account');
        exit;
    }  
    } catch (\Throwable $th) {
        if(file_exists('Config/log.log')){
        $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
        file_put_contents('Config/log.log', $error, FILE_APPEND);
     }
    }
   
}   
