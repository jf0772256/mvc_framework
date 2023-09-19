<?php

/**
 * @var \app\models\User $model
 */
/**
 * @var $this \app\core\View
 */
$this->title = "OurPage: Register";

?>

<h1>Register Yourself</h1>

<?php 
    use app\core\form\Form;
    $form = Form::begin('', 'post', 'row g-3');
    // do fieldy stuff in here?
    $form->field($model, 'firstName')->generateTextField('col-md-6')->renderField();
    $form->field($model, 'lastName')->generateTextField('col-md-6')->renderField();
    $form->field($model, 'email')->emailField()->generateTextField('col-md-12')->renderField();
    $form->field($model, 'password')->passwordField()->generateTextField('col-md-12')->renderField();
    $form->field($model, 'passwordConfirm')->passwordField()->generateTextField('col-md-12')->renderField();
    $form->field($model, '')->generateSubmitAndReset('col-12', 'Register', 'Reset')->renderField();
    Form::end();
?>

<div class="row g-3">
    <div class="col">
        <hr>
    </div>
</div>

<div class="row g3">
    <div class="col"></div>
    <div class="col text-center">
        <a href="/login">Already have a account? Login Here!</a>
    </div>
    <div class="col"></div>
</div>