<?php 

namespace app\core;

abstract class UserModel extends DbModel {

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public int $id;
    public string $firstName = "";
    public string $lastName = "";
    public string $email = "";
    public string $password = "";
    public string $passwordConfirm = "";
    public string $created_at = "";
    public int $status = self::STATUS_INACTIVE; // Active state???
    abstract function getDisplayName() : string;
}