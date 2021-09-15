<?php
/**
 * Created by PhpStorm.
 * User: Rafael
 * Date: 14/01/2021
 * Time: 13:40
 */

namespace src\controllers;


use core\Controller;
use src\handlers\UserHandler;

class LoginController extends Controller
{
    public function sigin()
    {
        $flash = '';
        if(!empty($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('sigin', [
            'flash' => $flash
        ]);
    }

    public function siginAction()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if($email && $password){
            $token = UserHandler::verifyLogin($email, $password);
            if($token){
                $_SESSION['token'] = $token;
                $this->redirect('/');
            }else{
                $_SESSION['flash'] = 'Email e/ou senha inválidos';
                $this->redirect('/login');
            }

        }else{
            $_SESSION['flash'] = 'Digite os campos de usuário e senha';
            $this->redirect('/login');
        }
    }

    public function sigup()
    {
        $flash = '';
        if(!empty($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('sigup', [
            'flash' => $flash
        ]);
    }

    public function sigupAction()
    {
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthdate = filter_input(INPUT_POST, 'birthdate');

        if($name && $email && $password && $birthdate){

            $birthdate = array_reverse(explode('/', $birthdate));

            if(count($birthdate) != 3) {
                $_SESSION['flash'] = 'Data de nascimento inválida';
                $this->redirect('/cadastro');
            }

            $birthdate = implode('/', $birthdate);

            if(strtotime($birthdate === false)){
                $_SESSION['flash'] = 'Data de nascimento inválida';
                $this->redirect('/cadastro');
            }

            if(UserHandler::emailExists($email) === false){
                $token = UserHandler::addUser($name, $email, $password, $birthdate);
                $_SESSION['token'] = $token;
                $this->redirect('/');
            }else{
                $_SESSION['flash'] = 'Email já cadastrado';
                $this->redirect('/cadastro');
            }

        }else{
            $this->redirect('/cadastro');
        }
    }

    public function recovery()
    {
        $flash = '';
        if(!empty($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('recovery', [
            'flash' => $flash
        ]);
    }

    public function recoveryAction()
    {
        
    }

    public function logout()
    {
        $_SESSION['token'] = '';
        $this->redirect('/login');
    }

}