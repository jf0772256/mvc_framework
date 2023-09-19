<?php
namespace app\core\database;
use \app\core\Utility;

class DatabaseSQLite_old {
    private $dsn = "";
    private $connection;
    public function __construct($databaseInfo = "") {
        try {
            $this->dsn = "sqlite:{$databaseInfo}";
            $this->connection = new \PDO($this->dsn,null,null,[\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC]);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Throwable $th) {
            Utility::dieAndDumpPretty($th);
        }
    }
    public function query($query, ?array $params = null) {
        $statement = $this->connection->prepare($query);
        $statement -> execute($params);
        return $statement;
    }
}

?>