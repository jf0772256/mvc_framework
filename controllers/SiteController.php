<?php

namespace app\controllers;
use app\core\Request;
use app\core\Response;
use app\core\Utility;
use app\core\Controller;
use app\core\Application;
use app\models\ContactModel;

class SiteController extends Controller {
    function home () {
        if (Application::$app->user) {
            $usersName = Application::$app->user->getDisplayName();
        } else {
            $usersName = "Guest";
        }
        $params = ["name" =>  $usersName];
        return $this->render('home', $params);
    }
    function contact (Request $request, Response $response) {
        $contactModel = new ContactModel();
        if(!Application::isGuest() && !$request->isPost()){
            $contactModel->firstName = Application::$app->user->firstName;
            $contactModel->lastName = Application::$app->user->lastName;
            $contactModel->email = Application::$app->user->email;
        }
        if ($request->isPost()) {
            $contactModel->loadData($request->getBody());
            if ($contactModel->validate() && $contactModel->save()) {
                Application::$app->session->setFlash('success', 'Thank you for contacting us');
                $response->redirect('/');
                exit;
            }
            return $this->render('contact', ['model' => $contactModel]);
        }
        return $this->render('contact', ['model' => $contactModel]);
    }
}
