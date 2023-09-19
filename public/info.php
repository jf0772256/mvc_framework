<?php
	require_once __DIR__ . "/../vendor/autoload.php";
	
	use \app\core\DotEnv as Env;
	
	Env::load(dirname(__DIR__) . '/.env');
	$test = $_ENV;
	//xdebug_break();
	phpinfo();