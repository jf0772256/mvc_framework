<?php

use app\core\Application;
use app\core\database\DatabaseInterface;
use app\core\Migration;

class m00002_add_password_to_users extends Migration {
    function up() {
        // do up migration
        $db = Application::$app->getDatabase();
        $dbct = Application::$app->dbc->interface;
        $sql = "ALTER TABLE users ADD COLUMN `password` VARCHAR(512) NOT NULL";
        if ($dbct === DatabaseInterface::SQLITE_INTERFACE) {
            $sql = "ALTER TABLE users ADD COLUMN `password` TEXT";
        }
        $db->query($sql);
        echo "[" . date('Y-m-d H:i:s') . "] - Completed task" . PHP_EOL;
    }
    function down() {
        // do down migration
        $db = Application::$app->getDatabase();
        $dbct = Application::$app->dbc->interface;
        $sql = "ALTER TABLE users DROP COLUMN `password`";
        $db->query($sql);
        echo "[" . date('Y-m-d H:i:s') . "] - Completed task" . PHP_EOL;
    }
}
