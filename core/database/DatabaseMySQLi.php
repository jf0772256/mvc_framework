<?php
namespace app\core\database;
use \app\core\Utility;

class DatabaseMySQLi_old {
    private $connection;
    public function __construct($databaseInfo = ['database'=> 'mysql', 'host' => 'localhost', 'port' => '3306', 'dbname' => '', 'user' => 'root', 'password' => '']) {
        $this->connection = new \mysqli($databaseInfo['host'], $databaseInfo['user'], $databaseInfo['password'], $databaseInfo['dbname'], $databaseInfo['port']);
        if ($this->connection->connect_error) Utility::dieAndDumpPretty("Error connecting to MySQL: {$this->connection->connect_error}");
    }
    public function query(string $sql, ?array $params = [], ?string $types = "") {
        $types = $types ?: str_repeat("s", count($params));
        $statement = $this->connection->prepare($sql);
        if (count($params) > 0) $statement->bind_param($types, ...$params);
        $statement->execute();
        return $statement;
    }
}

?>