<?php

namespace app\core;
use app\core\database\DatabaseInterface;

abstract class Model {

    public const RULE_REQUIRED = "required";
    public const RULE_EMAIL = "email";
    public const RULE_MIN = "min";
    public const RULE_MAX = "max";
    public const RULE_UNIQUE = "unique";
    public const RULE_MATCH = "match";

    public array $errors = [];

    function loadData ($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules() : array ;

    function validate () : bool {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (is_array($ruleName)) {
                    $ruleName = $rule[0];
                }

                // validation part
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) { 
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->getDatabase()->query("SELECT * FROM {$tableName} WHERE {$uniqueAttribute} = ?", [$value]);
                    if (Application::$app->dbc->interface === DatabaseInterface::MYSQLI_INTERFACE) {
                        if(count($statement->get_result()->fetch_all()) > 0) $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $uniqueAttribute]);
                        $statement->close();
                    } else {
                        if ($statement->fetchObject()) $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $uniqueAttribute]);
                        $statement->closeCursor();
                    }
                }
            }
        }
        return empty($this->errors);
    }

    private function addErrorForRule($attribute, $rule, $params = []) {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        $this->errors[$attribute][] = $message;
    }

    function addError($attribute, $message) {
        $this->errors[$attribute][] = $message;
    }

    function errorMessages () : array {
        return [
            self::RULE_REQUIRED => "This field is required",
            self::RULE_EMAIL => "This field must be a valid email address",
            self::RULE_MIN => "Min length of this field must be {min}",
            self::RULE_MAX => "Max length of this field must be {max}",
            self::RULE_MATCH => "This field must be the same as {match}",
            self::RULE_UNIQUE => "Record with this {field} already exists"
        ];
    }

    function hasError($attribute) {
        return $this->errors[$attribute] ?? false;
    }

    function getFirstError($attribute) {
        return $this->errors[$attribute][0] ?? false;
    }
}