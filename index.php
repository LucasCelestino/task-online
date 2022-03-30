<?php
session_start();

require("vendor/autoload.php");
require("app/configuration.php");

use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use Laminas\Diactoros\Response\HtmlResponse;

use App\Controllers\UserController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\HomeController;
use App\Controllers\TarefaController;


$router = Router::create();

// Home
$router->get('/', [HomeController::class, 'home']);
$router->get('/home', [HomeController::class, 'home']);
$router->get('/loggout', [HomeController::class, 'loggout']);

// Login usuário
$router->get('/usuarios/login', [LoginController::class, 'showLoginForm']);
$router->post('/usuarios/login', [LoginController::class, 'login']);

// Registrando usuário
$router->get('/usuarios/cadastro', [RegisterController::class, 'showRegisterForm']);
$router->post('/usuarios/cadastro', [RegisterController::class, 'create']);

// Registrando tarefa
$router->get('/tarefa/cadastro', [TarefaController::class, 'create']);
$router->post('/tarefa/store', [TarefaController::class, 'store']);

// Concluindo tarefa
$router->get('/tarefa/concluir/{id}', [TarefaController::class, 'concluir']);

// Deletando tarefa
$router->get('/tarefa/excluir/{id}', [TarefaController::class, 'destroy']);
$router->get('/tarefa/excluir_completada/{id}', [TarefaController::class, 'destroyCompleted']);

// Listando tarefas concluidas
$router->get('/tarefa/concluidas', [HomeController::class, 'tarefasConcluidas']);

try {
    $router->dispatch();
} catch (RouteNotFoundException $e) {
    // It's 404!
    $router->getPublisher()->publish(new HtmlResponse('Not found.', 404));
} catch (Throwable $e) {
    // Log and report...
    $router->getPublisher()->publish(new HtmlResponse('Internal error.', 500));
}