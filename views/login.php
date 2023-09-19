<?php

    /**
     * @var \app\models\LoginForm $model
     */
    /**
     * @var $this \app\core\View
     */
    $this->title = "OurPage: Login";

    use app\core\form\Form;
?>

<h1>Log In</h1>

<?php
    $form = Form::begin('', 'post', 'row g-3');
    $form->field($model, 'email')->emailField()->generateTextField('col-12')->renderField();
    $form->field($model, 'password')->passwordField()->generateTextField('col-12')->renderField();
    $form->field($model, '')->generateSubmit('d-grid gap-2 col-4 mx-auto', 'Login')->renderField();
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
        <a href="/register">Dont have a account? Create one here!</a>
    </div>
    <div class="col"></div>
</div>