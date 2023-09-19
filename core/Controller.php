<?php

namespace app\core;

use app\core\Application;
use app\core\middleware\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    public string $action = "";
    /**
     * @var \app\core\middleware\BaseMiddleware[]
     */
    protected array $middlewares = [];
    function render (string $view, array $params) {
        return Application::$app->view->renderView($view, $params);
    }

    function getBody () {
        return Application::$app->request->getBody();
    }

    function setLayout (string $layout) {
        $this->layout = $layout;
    }

    function registerMiddleware(BaseMiddleware $middleware) {
        $this->middlewares[] = $middleware;
    }

    /**
     * Get the value of middlewares
     *
     * @return  \app\core\middleware\BaseMiddleware[]
     */ 
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
}
