<?php

namespace App\Traits;
trait MangesFiles
{
    public static function UploadFile(array $file,array $AllowedExtension=['jpg','png','jpeg'],string $uploadFolder=null){
     $filename=$file['name'];
     $Extension=pathinfo($filename,PATHINFO_EXTENSION);
     if(!in_array($Extension,$AllowedExtension)){
        return ['error'=>'Invalid file extension OR Is empty'];
     }


     $newFilename = uniqid() . '.' . $Extension;
     

     $uploadFolder = $uploadFolder ?? 'Public/front/img/upload';
     

     if (!file_exists($uploadFolder)) {
         mkdir($uploadFolder, 0777, true);
     }
     

     $uploadPath = $uploadFolder . '/' . $newFilename;
     

     if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
         return [
             'success' => true,
             'filename' => $newFilename,
             'path' =>  $uploadPath
         ];
     }
     
     return ['error' => 'Failed to upload file'];
    }
}
