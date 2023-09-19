<?php

namespace app\core\middleware;

abstract class BaseMiddleware {
    abstract function execute();
}