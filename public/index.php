<?php
ini_set("display_errors", "On");
error_reporting(E_ALL);

require_once __DIR__ . "/../vendor/autoload.php";

use app\controllers\APIController;
use app\core\Request;
use app\core\Response;
use \app\core\Utility;
use \app\core\Application;
use app\controllers\SiteController;
use app\controllers\UserController;

// Utility::dieAndDumpPretty($_SERVER);

/**
 * 
 *  VIEW ROUTES
 * 
 */


$app = new Application(dirname(__DIR__), \app\models\User::class, 'mysqli');

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/login', [UserController::class, 'login']);

$app->router->get('/register', [UserController::class, 'register']);

$app->router->get('/contact', [SiteController::class, 'contact']);

$app->router->get('/users', [UserController::class, 'users']);

$app->router->get('/logout', function (Request $request, Response $response) {
    Application::$app->logout();
    $response->redirect('/');
    exit;
});

$app->router->get('/user/{id}', [UserController::class, 'redirect_profile']);
$app->router->get('/user/{id}/profile/', [UserController::class, 'profile']);
$app->router->get('/user/{id}/profile/edit', [UserController::class, 'edit']);
$app->router->get('/contact/read', [UserController::class, 'contactMessage']);
$app->router->get('/contact/read/{message}', [UserController::class, 'contactThisMessage']);


$app->router->post('/login', [UserController::class, 'login']);
$app->router->post('/register', [UserController::class, 'register']);
$app->router->post('/contact', [SiteController::class, 'contact']);



$app->router->put('/user/{id}/profile/edit', [UserController::class, 'updateProfile']);
$app->router->put('/contact/read/{message}',[UserController::class, 'setContactMessageAsUnread']);


$app->router->delete('/contact/read/{message}',[UserController::class, 'deleteContactMessage']);

/**
 * 
 *  API ROUTES
 * 
 */

$app->router->get('/api/user/{id}', [APIController::class, 'userById']);

$app->run();