<?php

namespace app\models;

use app\core\DbModel;

class ContactModel extends DbModel {
    public int $id = 0;
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $subject = '';
    public string $body = '';

    function rules () : array {
        return  [
            "firstName" => [self::RULE_REQUIRED],
            "lastName" => [self::RULE_REQUIRED],
            "body" => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 1000]],
            "subject" => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 255]],
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL]
        ];
    }

    static function primaryKey() : string {
        return 'id';
    }

    static function tableName() : string {
        return "contact_messages";
    }

    static function attributes() : array {
        return [
            'firstName',
            'lastName',
            'email',
            'subject',
            'body',
        ];
    }
    function labels() : array {
        return [
            "firstName" => 'First Name',
            "lastName" => 'Last Name',
            "email" => 'Email Address',
            'subject' => "Subject",
            'body' => "Body",
        ];
    }
}