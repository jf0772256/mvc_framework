<?php

namespace app\core;

class Response {
    private $errorTitles = [
        "403" => "403: You're Not Authorized",
        "404" => "404: Page Not Found"
    ];
    function setStatusCode(int $code) {
        http_response_code($code);
    }
    function getStatusCode() {
        return http_response_code();
    }
    function setErrorTitle(int $error, string $errorTitle) {
        $this->errorTitles[(string)$error] = $errorTitle;
    }
    function getErrorTitle(int $error) {
        if (!array_key_exists((string)$error, $this->errorTitles)) {
            return "Error";
        }
        return $this->errorTitles[(string)$error];
    }
    function redirect(string $path) {
        header("Location: $path");
        exit;
    }
}
