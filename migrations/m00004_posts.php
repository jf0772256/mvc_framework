<?php

	use app\core\Application;
	use app\core\database\DatabaseInterface;
	use app\core\Migration;

	class m00004_posts extends Migration {
		function up() {
			// do up migration
			$db = Application::$app->getDatabase();
			$dbct = Application::$app->dbc->interface;
			$sql = "CREATE TABLE posts (
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				postTitle VARCHAR (255) NOT NULL,
				author INT NOT NULL,
				postBody LONGTEXT NOT NULL,
				plug VARCHAR(7) NOT NULL UNIQUE,
				editedBy INT DEFAULT NULL,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				edited_at TIMESTAMP DEFAULT NULL)";
			if ($dbct === DatabaseInterface::SQLITE_INTERFACE) {
				$sql = "CREATE TABLE posts (
					id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
					postTitle TEXT NOT NULL,
					author INTEGER NOT NULL,
					postBody TEXT NOT NULL,
					plug TEXT NOT NULL UNIQUE,
					editedBy INTEGER DEFAULT NULL,
					created_at TEXT DEFAULT CURRENT_TIMESTAMP,
					edited_at TEXT DEFAULT NULL)";
			}
			$db->query($sql);
			echo "[" . date('Y-m-d H:i:s') . "] - Completed task" . PHP_EOL;
		}
		function down() {
			// do down migration
			$db = Application::$app->getDatabase();
			$sql = "DROP TABLE posts";
			$db->query($sql);
			echo "[" . date('Y-m-d H:i:s') . "] - Completed task" . PHP_EOL;
		}
	}
?>