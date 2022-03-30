<?php

namespace App\Controllers;

class TarefaController extends Controller
{
    /*
     GET
     Show a form to create new resource
    */ 
    public function create()
    {
        $this->render("cadastro-tarefa.html");
    }

    /*
    POST
    Store a newly created resource in database
    */ 
    public function store()
    {
        $taskModel = $this->model("Task");
        
        $task_input = filter_input(INPUT_POST, 'task', FILTER_SANITIZE_SPECIAL_CHARS);

        if(!empty($task_input))
        {
            $user_id = $_SESSION['user_auth'][0];

            $activeTask = $taskModel->bootstrap($user_id, $task_input);

            if($activeTask->save())
            {
                $data['success_task'] = true;
                $this->render("cadastro-tarefa.html", $data);
                return;
            }
        }

        $data['empty_field'] = true;
        $this->render("cadastro-tarefa.html", $data);
    }

    /*
    DELETE
    Delete the specified resource from database
    */ 
    public function destroy($id)
    {
        $taskModel = $this->model("Task");

        $user_id = $_SESSION['user_auth'][0];

        $activeTask = $taskModel->load($id, $user_id)[0];

        if($activeTask->destroy())
        {
            header("Location: ".BASE_URL);
            exit();
        }
    }

    
    public function destroyCompleted($id)
    {
        $taskModel = $this->model("Task");

        $user_id = $_SESSION['user_auth'][0];

        $activeTask = $taskModel->loadCompleted($id, $user_id)[0];

        if($activeTask->destroy())
        {
            header("Location: ".BASE_URL."/tarefa/concluidas");
            exit();
        }
    }

    public function concluir($id)
    {
        $taskModel = $this->model("Task");

        $user_id = $_SESSION['user_auth'][0];

        $activeTask = $taskModel->load($id, $user_id)[0];

        $activeTask->completed = 1;

        if($activeTask->save())
        {
            header("Location: ".BASE_URL);
            exit();
        }
    }
}