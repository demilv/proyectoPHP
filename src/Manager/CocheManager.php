<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class CocheManager
{
    public function uploadImage(UploadedFile $file, $targetDir)
    {
        $newFileName = uniqid().'.'.$file->guessExtension();
                $file->move(
                    $targetDir, 
                    $newFileName
                );
                            //Alternativamente subir todo a cloudinary sustituyendo todo a partir de donde esta el move()

        return 'images/'.$newFileName;
    }
}

?>