<?php

namespace app\core\form;

use app\core\Model;

class Form {
    
    static function begin (string $action, string $method, string $classes = '') {
        echo "<form action='{$action}' method='{$method}' class='{$classes}'>";
        return new Form();
    }

    static function end () {
        echo "</form>";
    }

    function field(Model $model, $attribute){
        return new Field($model, $attribute);
    }
}