<?php

use app\core\Application;
use app\core\database\DatabaseInterface;
use app\core\Migration;

class m00003_contact extends Migration {
    function up() {
        // do up migration
        $db = Application::$app->getDatabase();
        $dbct = Application::$app->dbc->interface;
        $sql = "CREATE TABLE contact_messages (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR (255) NOT NULL,
            `subject` VARCHAR (255) NOT NULL,
            `body` LONGTEXT NOT NULL,
            `read` INT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
        if ($dbct === DatabaseInterface::SQLITE_INTERFACE) {
            $sql = "CREATE TABLE contact_messages (
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL,
                `subject` TEXT NOT NULL,
                `body` TEXT NOT NULL,
                `read` INTEGER NOT NULL DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP)";
        }
        $db->query($sql);
        echo "[" . date('Y-m-d H:i:s') . "] - Completed task" . PHP_EOL;
    }
    function down() {
        // do down migration
        $db = Application::$app->getDatabase();
        $sql = "DROP TABLE contact_messages";
        $db->query($sql);
        echo "[" . date('Y-m-d H:i:s') . "] - Completed task" . PHP_EOL;
    }
}

?>