<?php

namespace app\core\form;

use app\core\Model;

class Field {

    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_EMAIL = 'email';
    public const TYPE_PHONE = 'phone';
    public const TYPE_RANGE = 'range';
    public const TYPE_NUMBER = 'number';
    public const TYPE_DATE = 'date';
    public const TYPE_HIDDEN = 'hidden';

    public Model $model;
    public string $attribute;
    private string $fieldHTML;
    public  string $type;

    function __construct(Model $model, string $attribute) {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->type = self::TYPE_TEXT;
    }

    function generateTextField($columnClass) : self {
        $errorClass = $this->model->hasError($this->attribute) ? ' is-invalid': '';
        $importValue = $this->model->{$this->attribute};
        $errorString = $this->model->getFirstError($this->attribute);
        $labelText = $this->model->labels()[$this->attribute] ?? $this->attribute;

        $this->fieldHTML = "<div class='{$columnClass}'>
                <label for='{$this->attribute}' class='form-label'>{$labelText}</label>
                <input type='{$this->type}' name='{$this->attribute}' id='{$this->attribute}' class='form-control{$errorClass}' value='{$importValue}'>
                <div class='invalid-feedback'>
                    {$errorString}
                </div>
            </div>";
        return $this;
    }
    function generateHiddenField($importValue) : self {
        $this->fieldHTML = "<input type='{$this->type}' name='{$this->attribute}' value='{$importValue}'>";
        return $this;
    }

    function generateTextArea($columnClass) : self {
        $errorClass = $this->model->hasError($this->attribute) ? ' is-invalid': '';
        $importValue = $this->model->{$this->attribute};
        $errorString = $this->model->getFirstError($this->attribute);
        $labelText = $this->model->labels()[$this->attribute] ?? $this->attribute;
        

        $this->fieldHTML = "<div class='{$columnClass}'>
                <label for='{$this->attribute}' class='form-label'>{$labelText}</label>
                <textarea name='{$this->attribute}' id='{$this->attribute}' class='form-control{$errorClass}'>{$importValue}</textarea>
                <div class='invalid-feedback'>
                    {$errorString}
                </div>
            </div>";
        return $this;
    }

    function generateSubmit($columnClass, $buttonText) : self {
        $this->fieldHTML = " <div class='{$columnClass}'>
            <button type='submit' class='btn btn-primary'>{$buttonText}</button>
        </div>";
        return $this;
    }

    function generateReset($columnClass, $buttonText) : self {
        $this->fieldHTML = " <div class='{$columnClass}'>
            <button type='reset' class='btn btn-danger'>{$buttonText}</button>
        </div>";
        return $this;
    }

    function generateSubmitAndReset($columnClass, $submitButtonText, $resetButtonText) : self {
        $this->fieldHTML = " <div class='{$columnClass}'>
            <button type='submit' class='btn btn-primary'>{$submitButtonText}</button>
            <button type='reset' class='btn btn-danger'>{$resetButtonText}</button>
        </div>";
        return $this;
    }

    function passwordField() : self {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    function emailField() : self {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    function rangeField() : self {
        $this->type = self::TYPE_RANGE;
        return $this;
    }
    
    function numberField() : self {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }
    
    function dateField() : self {
        $this->type = self::TYPE_DATE;
        return $this;
    }
    
    function hiddenField() : self {
        $this->type = self::TYPE_HIDDEN;
        return $this;
    }
    
    function phoneField() : self {
        $this->type = self::TYPE_PHONE;
        return $this;
    }

    function __toString() {
        return $this->fieldHTML;
    }

    function renderField() {
        echo $this->fieldHTML;
    }
}