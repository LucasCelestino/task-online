<?php

namespace App\Controllers;

use App\Helpers\Helpers;

class LoginController extends Controller
{

    public function __construct()
    {
        if(isset($_SESSION['user_auth']))
        {
            header("Location: ".BASE_URL."/home");
            exit();
        }
    }

    public function showLoginForm()
    {
        $data['csrf_token'] = Helpers::csrf_token();
        $this->render('login.html', $data);
    }

    public function login()
    {
        $userModel = $this->model("User");

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
        $token = $token_input = $_POST['csrf_token'];

        if($token != $_SESSION['token'])
        {
            header("Location: ".BASE_URL."/usuarios/login");
            exit();
        }

        if($email)
        {
            $user = $userModel->find($email);

            if(!$user)
            {
                return null;
            }

            if(!password_verify($password, $user->password))
            {
                return null;
            }

            $user_infos = [$user->id,$user->name];

            $_SESSION['user_auth'] = $user_infos;
            header("Location: ".BASE_URL."/home");
            exit();
        }
    }
}