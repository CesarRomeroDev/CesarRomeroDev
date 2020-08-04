<?php
//front controller
//inicializacion de errores

ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      =>  $_ENV['DB_HOST'],
    'database'  =>  $_ENV['DB_NAME'],
    'username'  =>  $_ENV['DB_USER'],
    'password'  =>  $_ENV['DB_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);


$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index', '/CesarRomeroDev/', [
    'controller' => 'App\Controllers\indexController',
    'action' => 'indexAction'
]);


$map->get('addJob', '/CesarRomeroDev/Job/add', [
    'controller' => 'App\Controllers\jobsController',
    'action' => 'jobsAction',
    'auth' => true
]);
$map->post('saveaddJob', '/CesarRomeroDev/Job/add', [
    'controller' => 'App\Controllers\jobsController',
    'action' => 'jobsAction',
    'auth' => true
]);


$map->get('addProject', '/CesarRomeroDev/Project/add', [
    'controller' => 'App\Controllers\projectController',
    'action' => 'projectAction',
    'auth' => true
]);
$map->post('saveaddProject', '/CesarRomeroDev/Project/add', [
    'controller' => 'App\Controllers\projectController',
    'action' => 'projectAction',
    'auth' => true
]);


$map->get('addUser', '/CesarRomeroDev/User/add', [
    'controller' => 'App\Controllers\usersController',
    'action' => 'usersAction',
    'auth' => true
]);
$map->post('saveaddUser', '/CesarRomeroDev/User/save', [
    'controller' => 'App\Controllers\usersController',
    'action' => 'postSaveUser',
    'auth' => true
]);
$map->get('login', '/CesarRomeroDev/login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin'
]);
$map->get('logout', '/CesarRomeroDev/logout', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogout'
]);
$map->post('auth', '/CesarRomeroDev/auth', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postLogin'
]);
$map->get('admin', '/CesarRomeroDev/admin', [
    'controller' => 'App\Controllers\adminController',
    'action' => 'getIndex',
    'auth' => true
]);
$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);
if (!$route) {
	echo 'No route';
} else {
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? false;

    $sessionuUserId = $_SESSION['userId'] ?? null;
    if ($needsAuth && !$sessionuUserId){
         header('location: /CesarRomeroDev/');
        exit;
    }

    $controller = new $controllerName;
    $response = $controller->$actionName($request);

    foreach($response->getHeaders() as $name => $values)  //vine de authController
    {
        foreach($values as $value){                       //vine de authController
            header(sprintf('%s: %s', $name, $value), false);  //vine de authController
        }
    }
    http_response_code($response->getStatusCode());         //vine de authController
    echo $response->getBody();
}

//$route = $_GET['route'] ?? '/';

//if ($route == '/') {
//	require '../index.php';
//} elseif ($route == 'addJob') {
//	require '../addJob.php';
//} elseif ($route == 'addProject') {
//	require '../addProject.php';
//}