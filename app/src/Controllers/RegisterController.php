<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\Helpers;

class RegisterController extends Controller
{
    public function __construct()
    {
        if(isset($_SESSION['user_auth']))
        {
            header("Location: ".BASE_URL."/home");
            exit();
        }
    }

    // bootstrap / start a user
    public function create()
    {
        $userModel = $this->model("User");

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $token_input = $_POST['csrf_token'];

        if(empty($name) || empty($email) || empty($password) || empty($confirm_password))
        {
            $data['empty_field'] = true; 
            $this->render('cadastro.html', $data);
            return;
        }

        if($token_input != $_SESSION['token'])
        {
            header("Location: ".BASE_URL."/usuarios/cadastro");
            exit();
        }

        if($password != $confirm_password)
        {
            $data['error_password'] = true; 
            $this->render('cadastro.html', $data);
            return;
        }

        $userModel->bootstrap($name, $email, password_hash($password, PASSWORD_BCRYPT));
        $this->register($userModel);
    }

    public function showRegisterForm()
    {
        $data['csrf_token'] = Helpers::csrf_token();
        $this->render('cadastro.html', $data);
    }

    public function register(User $user)
    {
        if($user->save())
        {
            $data['success_register'] = true;
            $this->render('cadastro.html', $data);
        }
    }
}