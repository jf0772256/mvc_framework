<?php

namespace app\core;
use app\core\database\DatabaseInterface;

abstract class DbModel extends Model {
    abstract static public function tableName() : string;
    abstract static public function attributes() : array;
    abstract static public function primaryKey() : string;

    function labels () : array {
        return [];
    }

    function save() {
        $tableName = static::tableName();
        $attributes = static::attributes();
        $params = [];
        foreach($attributes as $attribute) {
            $params[] = $this->{$attribute};
        }

        $stmnt = $this->prepare("INSERT INTO {$tableName} (" . implode(',', $attributes) . ") VALUES (" . implode(',', array_fill(0, count($attributes), '?')) . ")", $params);
        $rows = -1;

        if (Application::$app->dbc->interface === DatabaseInterface::MYSQLI_INTERFACE) {
            $rows = $stmnt->affected_rows;
            $stmnt->close();
        } else {
            $rows = $stmnt->rowCount();
            $stmnt->closeCursor();
        }

        return $rows;
    }

    function prepare($sql, $params) {
        return Application::$app->getDatabase()->query($sql, $params);
    }

    static function findOne (array $where) {
        // do query, find user and return user object to caller
        $table = static::tableName();
        $sql = "SELECT * FROM {$table} WHERE ";
        $values = [];
        foreach (array_keys($where) as $index => $searchString) {
            if ($index == 0) {
                $sql .= "{$searchString} = ?";
            } else {
                $sql .= " AND {$searchString} = ?";
            }
            $values[] = $where[$searchString];
        }
        return Application::$app->getDatabase()->query($sql, $values);
    }

    function fetchAll(?array $orderBy = []) {
        $table = static::tableName();
        $sql = "SELECT * FROM {$table}";
        foreach ($orderBy as $key => $value) {
            $sql .= $key === 0 ? " ORDER BY {$value}" : ", {$value}";
        }
        return Application::$app->getDatabase()->query($sql);
    }
}
