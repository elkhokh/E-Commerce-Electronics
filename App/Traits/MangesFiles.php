<?php

namespace App\Traits;
use App\Massage;
trait MangesFiles
{
    public static function UploadFile(array $file,array $AllowedExtension=['jpg','png','jpeg'], string $uploadFolder=null) {
     $filename=$file['name'];
     $Extension=pathinfo($filename,PATHINFO_EXTENSION);
    //  if(!in_array($Extension,$AllowedExtension)){
    //     Massage::set_Massages('error','Invalid file extension');
    //     return false;
    //  }

  
     $newFilename = uniqid() . '.' . $Extension;
     
 
     $uploadFolder = $uploadFolder ?? 'uploads';
     

     if (!file_exists($uploadFolder)) {
         mkdir($uploadFolder, 0777, true);
     }
     

     $uploadPath = $uploadFolder . '/' . $newFilename;
     

     if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
         return [
             'success' => true,
             'filename' => $newFilename,
             'path' => $uploadPath
         ];
     }
      Massage::set_Massages('error','Failed to upload file');
     return false;
    }
}
