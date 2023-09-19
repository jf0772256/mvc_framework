<?php
use app\core\form\Form;
/**
 * @var $model \app\models\ContactModel
 */
/**
 * @var $this \app\core\View
 */
$this->title = "OurPage: Contact Us";

echo "<h1>Contact Us</h1>";
$form = Form::begin('', 'post', 'row g-3');

$form->field($model, 'firstName')->generateTextField('col-md-6')->renderField();
$form->field($model, 'lastName')->generateTextField('col-md-6')->renderField();
$form->field($model, 'email')->emailField()->generateTextField('col-md-12')->renderField();
$form->field($model, 'subject')->generateTextField('col-md-12')->renderField();
$form->field($model, 'body')->generateTextArea('col-md-12')->renderField();
$form->field($model, '')->generateSubmitAndReset('col-12', 'Submit Contact', 'Reset Form')->renderField();

Form::end();

?>