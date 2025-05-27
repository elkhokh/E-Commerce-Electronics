<?php

namespace App\Traits;
trait MangesFiles
{
    public static function UploadFile(array $file,array $AllowedExtension=['jpg','png','jpeg'],string $uploadFolder=null){
     $filename=$file['name'];
     $Extension=pathinfo($filename,PATHINFO_EXTENSION);
     if(!in_array($Extension,$AllowedExtension)){
        return ['error'=>'Invalid file extension'];
     }

     // Generate unique filename
     $newFilename = uniqid() . '.' . $Extension;
     
     // Set default upload folder if not provided
     $uploadFolder = $uploadFolder ?? 'uploads';
     
     // Create upload directory if it doesn't exist
     if (!file_exists($uploadFolder)) {
         mkdir($uploadFolder, 0777, true);
     }
     
     // Set full path for the file
     $uploadPath = $uploadFolder . '/' . $newFilename;
     
     // Check if file was successfully uploaded
     if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
         return [
             'success' => true,
             'filename' => $newFilename,
             'path' => $uploadPath
         ];
     }
     
     return ['error' => 'Failed to upload file'];
    }
}
