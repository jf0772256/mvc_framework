<?php

namespace app\models;
use app\core\Model;
use app\models\User;
use app\core\Application;

class LoginForm extends Model{
    public string $email = "";
    public string $password = "";
    function rules() : array {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }
    function labels () : array {
        return [
            "email" => "Email Address",
            "password" => "Password"
        ];
    }
    function login() {
        $user = User::findOne(['email' => $this->email]);
        if (!$user) {
            $this->addError('email', "User Email and or Password is incorrect");
            $this->addError('password', 'User Email and or Password is incorrect');
            return false;
        }
        if (!password_verify($this->password, $user->password)) {
            $this->addError('email', "User Email and or Password is incorrect");
            $this->addError('password', "User Email and or Password is incorrect");
            return false;
        }
        return Application::$app->login($user);
    }
}