<?php
namespace app\core\database;
use \app\core\Utility;

class DatabasePDO_old {
    private $dsn = "";
    private $connection;
    public function __construct($databaseInfo = ['database'=> 'mysql', 'host' => 'localhost', 'port' => '3306', 'dbname' => '', 'user' => 'root', 'password' => '']) {
        try {
            $this->dsn = "{$databaseInfo['database']}:host={$databaseInfo['host']};port={$databaseInfo['port']};dbname={$databaseInfo['dbname']};" . (!empty($databaseInfo['user']) ? "user={$databaseInfo['user']};" : '') . (!empty($databaseInfo['password']) ? "password={$databaseInfo['password']};" : '');
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