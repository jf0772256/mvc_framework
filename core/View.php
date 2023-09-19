<?php

namespace app\core;

use app\core\Application;

class View {
    public string $title = '';

    function renderView($view, array $params = []) {
        // load the page buffer and return the view
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutData = $this->layoutContent($params);
        return str_replace("{{content}}", $viewContent, $layoutData);
    }

    function renderContent($viewContent, $params = []) {
        $layout = $this->layoutContent($params);
        return str_replace('{{content}}', $viewContent, $layout);
    }

    protected function layoutContent(array $params) {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }

        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/{$layout}.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, array $params) {

        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/{$view}.php";
        return ob_get_clean();
    }
}