<?php

namespace App\Controllers;

use App\Helpers\Helpers;

class HomeController extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['user_auth']))
        {
            header("Location: ".BASE_URL."/usuarios/login");
            exit();
        }
    }

    public function home()
    {
        $taskModel = $this->model("Task");

        $user_id = $_SESSION['user_auth'][0];

        $tasks = $taskModel->find($user_id);

        $data['user_data'] = $_SESSION['user_auth'];
        $data['tasks'] = $tasks;

        $this->render("home.html", $data);
    }

    public function tarefasConcluidas()
    {
        $taskModel = $this->model("Task");

        $user_id = $_SESSION['user_auth'][0];

        $tasks = $taskModel->findCompleted($user_id);

        $data['user_data'] = $_SESSION['user_auth'];
        $data['tasks'] = $tasks;

        $this->render("tarefas-concluidas.html", $data);
    }

    public function loggout()
    {
        session_destroy();

        header("Location: ".BASE_URL);
        exit();
    }
}