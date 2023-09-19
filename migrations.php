<?php

use app\core\Application;
// use app\core\DotEnv;
use app\core\database\DatabaseInterface;
use app\core\Utility;

require_once __DIR__ . "/vendor/autoload.php";
// get database from application

// DotEnv::load('.env');

$args = getopt('', ["dbType:"]);
if ($args !== false) {
	$database_connector = $args['dbType'];
} else {
	die(Utility::dumpAndContinue("Could not get value of command line option\n"));
}

$app = new Application(__DIR__, \app\models\User::class, $database_connector);
$db = $app->getDatabase();


function buildMigrationsTable($db, $dbConnUsed) {
    $sql = "CREATE TABLE IF NOT EXISTS migrations ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, migration VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
    if ($dbConnUsed === DatabaseInterface::SQLITE_INTERFACE) {
        $sql = "CREATE TABLE IF NOT EXISTS migrations ( id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, migration TEXT, created_at TEXT DEFAULT CURRENT_TIMESTAMP)";
    }
    $db->query($sql);
}

function getAppliedMigrations($db, $dbConnUsed) {
    $sql = "SELECT migration FROM migrations";
    $migrations = [];
    if ($dbConnUsed === DatabaseInterface::MYSQLI_INTERFACE) {
        $result = $db->query($sql)->get_result();
        foreach ($result as ['migration' => $migrations[]]);
    } else {
        $migrations = $db->query($sql)->fetchALL(PDO::FETCH_COLUMN);
    }
    return $migrations;
}

function prepToRun($db, $dbConnUsed) {
    $appliedMigrations = getAppliedMigrations($db, $dbConnUsed);
    $files = scandir(Application::$ROOT_DIR . '/migrations');
    return array_diff($files, $appliedMigrations);
}

function saveMigration($db, $dbConnUsed, $migration) {
    $sql = "INSERT INTO migrations (migration) VALUES (?)";
    $db->query($sql, [$migration]);
}

function logger($message) {
    echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
}


buildMigrationsTable($db, $app->dbc->interface);
$toApplyMigrations = prepToRun($db, $app->dbc->interface);

foreach ($toApplyMigrations as $migration) {
    if ($migration==='.' || $migration==='..') continue;
    require_once Application::$ROOT_DIR . "/migrations/{$migration}";
    $className = pathinfo($migration, PATHINFO_FILENAME);
    $instance = new $className;
    logger("Applying Migration {$migration} ...");
    $instance->up();
    logger("Applied Migration {$migration} ...");
    saveMigration($db, $app->dbc->interface, $migration);
}

logger("All migrations have been run");