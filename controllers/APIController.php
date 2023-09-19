<?php

namespace app\controllers;

use app\core\database\DatabaseInterface;
use app\Models\User;
use app\core\Request;
use app\core\Utility;
use app\core\Response;
use app\core\Controller;
use app\core\Application;
use app\models\LoginForm;
use app\core\middleware\AuthMiddleware;

class APIController extends Controller {

    function __construct() {
        $this->registerMiddleware(new AuthMiddleware());
    }

    function userById (Request $request) {
        $data = Application::$app->getDatabase()->query("SELECT id, email, firstName, lastName, status, created_at FROM users WHERE id=?", [$request->params['id']], 'i');
        if (Application::$app->dbc->interface === DatabaseInterface::MYSQLI_INTERFACE) {
            echo json_encode($data->get_result()->fetch_assoc());
        } else {
            echo json_encode($data->fetch());
        }
    }
}
