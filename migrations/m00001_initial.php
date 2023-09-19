<?php

use app\core\Application;
use app\core\database\DatabaseInterface;
use app\core\Migration;

class m00001_initial extends Migration {
    function up() {
        // do up migration
        $db = Application::$app->getDatabase();
        $dbct = Application::$app->dbc->interface;
        $sql = "CREATE TABLE users (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR (255) NOT NULL,
            firstName VARCHAR (255) NOT NULL,
            lastName VARCHAR (255) NOT NULL,
            status INT(1) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
        if ($dbct === DatabaseInterface::SQLITE_INTERFACE) {
            $sql = "CREATE TABLE users (
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL,
                firstName TEXT NOT NULL,
                lastName TEXT NOT NULL,
                status INTEGER NOT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP)";
        }
        $db->query($sql);
        echo "[" . date('Y-m-d H:i:s') . "] - Completed task" . PHP_EOL;
    }
    function down() {
        // do down migration
        $db = Application::$app->getDatabase();
        $sql = "DROP TABLE users";
        $db->query($sql);
        echo "[" . date('Y-m-d H:i:s') . "] - Completed task" . PHP_EOL;
    }
}
