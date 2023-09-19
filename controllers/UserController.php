<?php

namespace app\controllers;

use app\core\database\DatabaseInterface;
use app\models\ContactModel;
use app\Models\User;
use app\core\Request;
use app\core\Utility;
use app\core\Response;
use app\core\Controller;
use app\core\Application;
use app\models\LoginForm;
use app\core\middleware\AuthMiddleware;
use app\core\middleware\SystemUserMiddleware;

class UserController extends Controller {

    function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['edit', 'updateProfile', 'users', 'contactMessage', 'contactThisMessage']));
        $this->registerMiddleware(new SystemUserMiddleware(['contactMessage', 'contactThisMessage']));
    }

    function login(Request $request, Response $response) {
        $loginForm = new LoginForm();
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
                return;
            }
        }
        $this->setLayout('auth');
        return $this->render('login', ["model" => $loginForm]);
    }

    function register(Request $request) {
        $user = new User();
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            if ($user->validate() && $user -> save()) {
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
                exit;
            }
            $this->setLayout('auth');
            return $this->render('register', ['model' => $user]);
        }
        $this->setLayout('auth');
        return $this->render('register', ['model' => $user]);
    }
    
    function users(Request $request) {
        $db = Application::$app->getDatabase();
        $dbType = Application::$app->dbc->interface;
        $statement = $db->query("SELECT id, CONCAT(firstName, ' ', lastName) AS `name`, created_at FROM users");
        $data = [];
        if ($dbType === DatabaseInterface::MYSQLI_INTERFACE) {
            $data = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
            $statement->close();
        } else {
            $data = $statement->fetch_all();
            $statement->closeCursor();
        }
        $this->setLayout('main');
        return $this->render('users', ['data' => $data]);
    }

    function redirect_profile(Request $request) {
        $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "/profile/";
        header("Location: {$url}", true);
    }

    function profile(Request $request, Response $response) {
        $db = Application::$app->getDatabase();
        $dbType = Application::$app->dbc->interface;
        $statement = $db->query("SELECT id, firstName, lastName, email, created_at FROM users WHERE id = ?", [$request->params['id']]);
        // Utility::dieAndDumpPretty($statement->get_result()->fetch_all(MYSQLI_ASSOC));
        $data = [];
        if ($dbType === DatabaseInterface::MYSQLI_INTERFACE) {
            $data = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
            $statement->close();
        } else {
            $data = $statement->fetch_all();
            $statement->closeCursor();
        }

        $user = new User();
        if (count($data) === 0) {
            Application::$app->response->redirect("/");
        }
        $user->loadData($data[0]);
        $this->setLayout('main');
        return $this->render('profile', ['model' => $user]);
    }

    function edit(Request $request) {
        $this->setLayout('main');
        return $this->render('profile_edit', ['data' => "Editing User {$request->params['id']} Profile"]);
    }

    function updateProfile(Request $request) {
        $this->setLayout('main');
        return $this->render('profile_edit', ['data' => "Updated User {$request->params['id']} Profile"]);
    }

    function contactMessage(Request $request, Response $response) {
        // this is a much more protected value

        // set up contact modal
        $contactModal = new ContactModel();
        $contacts = $contactModal->fetchAll(["`read` ASC"])->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($contacts as $k=>$message) {
            $contacts[$k] = (object) $message;
        }
        $this->setLayout('admin');
        return $this->render('readMessages', ["data" => $contacts, "modal" => $contactModal]);
    }

    
    function contactThisMessage(Request $request, Response $response) {
        // this is a much more protected value

        // set up contact modal
        $contactModal = new ContactModel();
        if (Application::$app->getDatabase()->query("SELECT * FROM {$contactModal::tableName()} WHERE id=?", [$request->params['message']], 'i')->get_result()->num_rows !== 0) 
        {
            Application::$app->getDatabase()->query("UPDATE {$contactModal::tableName()} SET `read` = 1 WHERE id=?", [$request->params['message']], 'i');
            $messageSelected = Application::$app->getDatabase()->query("SELECT * FROM {$contactModal::tableName()} WHERE id=?", [$request->params['message']], 'i')->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        }
        else
        {
            $messageSelected = ['id' => null, 'subject' => 'Sorry, Couldn\'t find that one', 'email' => null, 'body' => null, 'created_at' => null];
        }
        $contacts = $contactModal->fetchAll()->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($contacts as $k=>$message) {
            $contacts[$k] = (object) $message;
        }
        $this->setLayout('admin');
        return $this->render('readMessages', ["data" => $contacts, "modal" => $contactModal, "messageObj" => (object)$messageSelected]);
    }
	function setContactMessageAsUnread(Request $request, Response $response) {
		$contactModal = new ContactModel();
		Application::$app->dbc->query("UPDATE {$contactModal::tableName()} SET `read` = 0 WHERE id=?", [$request->params['message']], 'i');
		$response->redirect('/contact/read');
	}
	function deleteContactMessage(Request $request, Response $response) {
		$contactModal = new ContactModel();
		Application::$app->dbc->query("DELETE FROM {$contactModal::tableName()} WHERE id=?", [$request->params['message']], 'i');
		$response->redirect('/contact/read');
	}
}
