<?php
namespace app\core;
ini_set("display_errors", "On");
error_reporting(E_ALL);

use app\core\exception\NotFound;
use app\core\Utility;

class Router {
    protected $routes = ["get"=>[], "post"=>[], "delete"=>[], "put"=>[]];
    public Request $request;
    public Response $response;

    public string $title = '';
    

    function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    function get( $route, $callback ) {
        $this->routes['get'][$route] = $callback;
    }

    function put( $route, $callback ) {
        $this->routes['put'][$route] = $callback;
    }

    function post( $route, $callback ) {
        $this->routes['post'][$route] = $callback;
    }

    function delete( $route, $callback ) {
        $this->routes['delete'][$route] = $callback;
    }

    function resolve() {
        $data = $this->request->getPath();
        $path = $data['path'];
        $query = $data['query'] ?? [];
        $method = $this->request->method();
        // If youre going to do param checking this is where youll have to do it from 
        // this will be done in the request object
        $path = $this->request->parameterSearch($this->routes[$method], $path);
        
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            $this->response->setStatusCode(404);
            Application::$app->controller = new Controller;
            throw new NotFound();
        }

        if (is_string($callback)) {
            // render the view page
            return Application::$app->view->renderView($callback, ["pageTitle" => $callback, "params" => $this->request->params]);
        }

        if (is_array($callback)) {
            /**
             * @var \app\core\Controller $controller
             */
            $controller = new $callback[0];
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            foreach ( $controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }

        return  call_user_func($callback, $this->request, $this->response);
    }
}