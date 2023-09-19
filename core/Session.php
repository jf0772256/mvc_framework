<?php

namespace app\core;


class Session {

    protected const FLASH_KEY = "flash_messages";

    function __construct() {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$message) {
            $message['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    function setFlash($key, $message){
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    function getFlash($key) {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    function get($key) {
        return $_SESSION[$key] ?? false;
    }

    function remove($key) {
        unset($_SESSION[$key]);
    }

    function __destruct() {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$message) {
            if($message['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}