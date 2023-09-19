<?php

namespace app\core\exception;

class Forbidden extends \Exception
{
    protected $code = 403;
    protected $message = "You are not authorized to access this page";
}
