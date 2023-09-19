<?php 
    /**
     * @var \Exception $exception
     */
    /**
     * @var $this \app\core\View
     */
    $this->title = "OurPage: {$exception->getCode()} Error";
?>

<h3 class="text-center"><?= $exception->getCode() ?> - <?= $exception->getMessage() ?></h3>