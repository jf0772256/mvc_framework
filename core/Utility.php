<?php

namespace app\core;
class Utility {
    static function dieAndDump($value) {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
        die();
    }

    static function dieAndDumpPretty($value) {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
        die();
    }

    static function dumpPrettyAndContinue($value) {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }

    static function dumpAndContinue($value) {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }

    static function parseURI($value) {
        return \parse_url($value);
    }
}
?>