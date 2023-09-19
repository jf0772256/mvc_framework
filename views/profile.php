<?php
use app\core\Application;

/**
 * @var \app\models\User $model
 */
/**
 * @var $this \app\core\View
 */
$this->title = "OurPage Profile - " . $model->getDisplayName();

?>

<h2>User Profile</h2>
<h5>/<?= $model->getDisplayName() ?></h5>

<label>Created account on</label> <span><?= $model->created_at ?></span><br>
<?php if (Application::isGuest() == false) : ?>
<label>User email is </label> <span><?= $model->email ?></span>
<?php endif; ?>