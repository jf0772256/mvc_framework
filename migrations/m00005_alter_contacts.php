<?php

	use app\core\Application;
	use app\core\database\DatabaseInterface;
	use app\core\Migration;

	class m00005_alter_contacts extends Migration {
		function up() {
			Application::$app->dbc->query("ALTER TABLE `contact_messages` ADD `firstName` VARCHAR(50) NOT NULL AFTER `id`");
            Application::$app->dbc->query("ALTER TABLE `contact_messages` ADD `lastName` VARCHAR(50) NOT NULL AFTER `firstName`");
		}
		function down() {
			// do down migration
            Application::$app->dbc->query("ALTER TABLE `contact_messages` DROP `firstName`");
            Application::$app->dbc->query("ALTER TABLE `contact_messages` DROP `lastName`");
		}
	}
?>