<?php
/**
 * Created by PhpStorm.
 * User: Rafael
 * Date: 29/01/2021
 * Time: 09:51
 */

namespace src\controllers;


use core\Controller;
use src\handlers\UserHandler;

class ConfigController extends Controller
{

    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if (UserHandler::checkLogin() === false) {
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $id = $this->loggedUser->id;

        $user = UserHandler::getUser($id, true);

        $this->render('config', [
            'loggedUser' => $user
        ]);

    }

    public function submit()
    {
        $updateFields['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $updateFields['name'] = filter_input(INPUT_POST, 'name');
        $updateFields['birthdate'] = filter_input(INPUT_POST, 'birthdate');
        $updateFields['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $updateFields['city'] = filter_input(INPUT_POST, 'city');
        $updateFields['work'] = filter_input(INPUT_POST, 'work');
        $updateFields['pswd1'] = filter_input(INPUT_POST, 'password-1');
        $updateFields['pswd2'] = filter_input(INPUT_POST, 'password-2');

        if(isset($_FILES['avatar']) && (!empty($_FILES['avatar']['tmp_name']))){

            $newAvatar = $_FILES['avatar'];

            if(in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])){
                $avatarName = $this->cutImage($newAvatar, 200, 200, 'media/avatars');
                $updateFields['avatar'] = $avatarName;
            }
        }

        if(isset($_FILES['cover']) && (!empty($_FILES['cover']['tmp_name']))){
            $newCover = $_FILES['cover'];

            if(in_array($newCover['type'], ['image/jpeg', 'image/jpg', 'image/png'])){
                $coverName = $this->cutImage($newCover, 850, 310, 'media/covers');
                $updateFields['cover'] = $coverName;
            }
        }

        if($updateFields['email']) {

            if ($updateFields['email'] != $this->loggedUser->email) {

                if (UserHandler::emailExists($updateFields['email'])) {
                    $this->render('/config', [
                        'flash' => 'O email informado já esta cadastrado!',
                        'loggedUser' => $this->loggedUser
                    ]);
                    exit;
                }

                $this->updateUser($updateFields);
            } else {

                $this->updateUser($updateFields);
            }
        }

        $this->render('config', [
            'loggedUser' => $this->loggedUser,
            'flash' => 'Email inválido!'
        ]);
    }

    public function updateUser($updateFields)
    {
        if ($updateFields['pswd1'] != $updateFields['pswd2']) {
            $this->render('config', [
                'loggedUser' => $this->loggedUser,
                'flash' => 'Senhas não conferem!'
            ]);
        } elseif (empty($updateFields['pswd1']) && empty($updateFields['pswd2'])) {

            UserHandler::updateUser($updateFields);

            $this->redirect('/config');
        } else {
            UserHandler::updateUser($updateFields);
            $this->redirect('/sair');

        }

    }

    private function cutImage($file, $w, $h, $folder)
    {
        list($widthOrig, $heightOrig) = getimagesize($file['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $w;
        $newHeight = $newWidth / $ratio;

        if($newHeight < $h){
            $newHeight = $h;
            $newWidth = $newHeight * $ratio;
        }

        $x = $w - $newWidth;
        $y = $h - $newHeight;

        $x = $x < 0 ? $x / 2 : $x;
        $y = $y < 0 ? $y / 2 : $y;

        $finalImage = imagecreatetruecolor($w, $h);
        switch ($file['type']){
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
            break;
        }

        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0,0,
            $newWidth, $newHeight, $widthOrig, $heightOrig
        );

        $fileName = md5(time().rand(0,9999)).'.jpg';

        imagejpeg($finalImage, $folder. '/' . $fileName);

        return $fileName;
    }

}