<?php

namespace app\core\middleware;
use app\core\Application;
use app\core\exception\Forbidden;

class SystemUserMiddleware extends BaseMiddleware {
	protected array $actions = [];
	function __construct(array $actions = []) {
		$this->actions = $actions;
	}

	function execute() {
		// Enter your execute code that you want the middleware to run when its called on
		if(!empty(Application::$app->user->id) && Application::$app->user->id !== 1) {
			if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
				throw new Forbidden();
			}
		}
	}
}

?>