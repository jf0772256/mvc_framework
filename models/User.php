<?php

namespace app\models;
use app\core\Application;
use app\core\database\DatabaseInterface;
use app\core\UserModel;

class User extends UserModel {

    // const STATUS_INACTIVE = 0;
    // const STATUS_ACTIVE = 1;
    // const STATUS_DELETED = 2;

    // public int $id;
    // public string $firstName = "";
    // public string $lastName = "";
    // public string $email = "";
    // public string $password = "";
    // public string $passwordConfirm = "";
    // public string $created_at = "";
    // public int $status = self::STATUS_INACTIVE; // Active state???

    function save() {
        //create the user in the database
        // then redirect them to new route
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT, ['cost' => 12]);
        return parent::save(); 
    }

    function rules () : array {
        return  [
            "firstName" => [self::RULE_REQUIRED],
            "lastName" => [self::RULE_REQUIRED],
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
            "password" => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 36]],
            "passwordConfirm" => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    static function primaryKey() : string {
        return "id";
    }

    static function tableName() : string {
        return "users";
    }

    static function attributes() : array {
        return  [
            "firstName",
            "lastName",
            "email",
            "password",
            "status"
        ];
    }

    function labels() : array {
        return [
            "firstName" => 'First Name',
            "lastName" => 'Last Name',
            "email" => 'Email Address',
            "password" => 'Password',
            "status" => 'User Status',
            "passwordConfirm" => 'Confirm Password'
        ];
    }

    function getDisplayName() : string {
        return "{$this->firstName} {$this->lastName}";
    }

    static function findOne(array $paramsToFind) {
        $statement = parent::findOne($paramsToFind);
        if (Application::$app->dbc->interface === DatabaseInterface::MYSQLI_INTERFACE) {
            $result = $statement->get_result()->fetch_assoc();
            if ($result && count($result) > 0) {
                //found user, return User model object;
                $user = new User();
                $user->loadData($result);
                $statement->close();
                return $user;
            }
            $statement->close();
        } else {
            $result = $statement->fetch();
            if ($result && count($result) > 0) {
                //found user, return User model object;
                $user = new User();
                $user->loadData($result);
                $statement->closeCursor();
                return $user;
            }
            $statement->closeCursor();
        }
        // failed to find the user...
        return null;
    }
}
