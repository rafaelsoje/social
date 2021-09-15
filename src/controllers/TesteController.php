<?php
/**
 * Created by PhpStorm.
 * User: Rafael
 * Date: 04/02/2021
 * Time: 10:39
 */

namespace src\controllers;


use core\Controller;

class TesteController extends Controller
{
    public function teste()
    {
        $this->render('/teste');
    }

    public function upload()
    {
//        var_dump($_FILES);

        if(isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])){

            $photo = $_FILES['photo'];

            $maxWidth = 800;
            $maxHeight = 800;

            var_dump($photo['type']);

            if(in_array($photo['type'], ['image/png', 'image/jpg', 'image/jpeg'])){

                echo 'aqui';

                list($widthOrig, $heightOrig) = getimagesize($photo['tmp_name']);
                $ratio = $widthOrig / $heightOrig;

                $newWidth = $maxWidth;
                $newHeight = $maxHeight;
                $ratioMax = $maxWidth / $maxHeight;

                if($ratioMax > $ratio){
                    $newWidth = $newHeight * $ratio;
                }else{
                    $newHeight = $newWidth / $ratio;
                }

                $finalImage = imagecreatetruecolor($newWidth, $newHeight);
                switch ($photo['type']){
                    case 'image/png':
                        $image = imagecreatefrompng($photo['tmp_name']);
                        break;

                    case 'image/jpg':
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($photo['tmp_name']);
                        break;
                }

                imagecopyresampled(
                    $finalImage, $image,
                    0,0,0,0,
                    $newWidth, $newHeight, $widthOrig, $heightOrig
                );

                $photoName = md5(time().rand(0,9999)).'.jpg';

                imagejpeg($finalImage, 'media/uploads/'.$photoName);

                PostHandler::addPost(6, 'photo', $photoName);
            }

        }else{
            $array['error'] = 'Nenhuma imagem enviada';
        }


    }

}