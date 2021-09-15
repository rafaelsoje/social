<?php
/**
 * Created by PhpStorm.
 * User: Rafael
 * Date: 02/02/2021
 * Time: 13:17
 */

namespace src\controllers;


use core\Controller;
use src\handlers\PostHandler;
use src\handlers\UserHandler;
use src\models\PostLike;

class AjaxController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if(UserHandler::checkLogin() === false) {
            header("Content-type: application/json");
            echo json_encode(['Error' => 'Usuário não logado']);
            exit;
        }
    }

    public function like($atts)
    {
        $id = $atts['id'];

        if(PostHandler::isLiked($id, $this->loggedUser->id)){

            PostHandler::deleteLike($id, $this->loggedUser->id);

        }else{

            PostHandler::addLike($id, $this->loggedUser->id);
        }
    }

    public function comment()
    {
        $array = ['error' =>''];

        $id = filter_input(INPUT_POST, 'id');
        $txt = filter_input(INPUT_POST, 'txt');

        if($id && $txt){
            PostHandler::addComment($id, $txt, $this->loggedUser->id);

            $array['link'] = '/perfil/'.$this->loggedUser->id;
            $array['avatar'] = '/media/avatars/'.$this->loggedUser->avatar;
            $array['name'] = $this->loggedUser->name;
            $array['body'] = $txt;

        }

        header("Content-type: application/json");
        echo json_encode($array);
        exit;
    }

    public function upload()
    {
//        var_dump($_FILES);

        $array = ['error' => ''];

        if(isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])){

            $photo = $_FILES['photo'];
            $maxWidth = 800;
            $maxHeight = 800;

            if(in_array($photo['type'], ['image/png','image/jpg','image/jpeg'])){

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

                PostHandler::addPost($this->loggedUser->id, 'photo', $photoName);
            }

        }else{
            $array['error'] = 'Nenhuma imagem enviada';
        }


        header("Content-type: application/json");
        echo json_encode($array);
        exit;
    }

}