<?php

namespace app\core\middleware;

use app\core\Application;
use app\core\exception\Forbidden;

class AuthMiddleware extends BaseMiddleware {
    protected array $actions = [];
    function __construct(array $actions = []) {
        $this->actions = $actions;
    }

    function execute() {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new Forbidden();
            }
        }
    }
}