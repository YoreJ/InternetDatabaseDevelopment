<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">后台管理员界面</h1>

        <p class="lead">数据库</p>
        <a href="<?= \Yii::$app->urlManager->createUrl(['users/index']) ?>" class="btn btn-primary">users</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['articlecomments/index']) ?>" class="btn btn-primary">articlecomments</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['articlelikes/index']) ?>" class="btn btn-primary">articlelikes</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['articles/index']) ?>" class="btn btn-primary">articles</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['conversations/index']) ?>" class="btn btn-primary">conversations</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['messages/index']) ?>" class="btn btn-primary">messages</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['videocomments/index']) ?>" class="btn btn-primary">videocomments</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['videolikes/index']) ?>" class="btn btn-primary">videolikes</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['videos/index']) ?>" class="btn btn-primary">videos</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['websitevisits/index']) ?>" class="btn btn-primary">websitevisits</a>
    </div>
</div>
