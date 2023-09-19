<?php
// use app\core\Utility;
//     Utility::dieAndDumpPretty($data);
    /**
     * @var $this \app\core\View
     */
    $this->title = "OurPage: Users";
?>

<ul style="list-style: none;">

    <?php foreach ($data as $user) : ?>

        <li>
            <div class="row">
                <div class="col">
                    <a href="/user/<?= $user['id'] ?>/profile"><?= $user['name'] ?></a>
                </div>
                <div class="col"><?= $user['created_at'] ?></div>
            </div>
        </li>

    <?php endforeach ?>

</ul>