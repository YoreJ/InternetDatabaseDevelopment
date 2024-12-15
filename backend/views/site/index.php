<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">后台管理员界面</h1>

        <p class="lead">数据库</p>
        <a href="<?= \Yii::$app->urlManager->createUrl(['users/index']) ?>" class="btn btn-primary">users</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['article-comments/index']) ?>" class="btn btn-primary">articlecomments</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['article-likes/index']) ?>" class="btn btn-primary">articlelikes</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['articles/index']) ?>" class="btn btn-primary">articles</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['conversations/index']) ?>" class="btn btn-primary">conversations</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['messages/index']) ?>" class="btn btn-primary">messages</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['video-comments/index']) ?>" class="btn btn-primary">videocomments</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['video-likes/index']) ?>" class="btn btn-primary">videolikes</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['videos/index']) ?>" class="btn btn-primary">videos</a>
        <a href="<?= \Yii::$app->urlManager->createUrl(['website-visits/index']) ?>" class="btn btn-primary">websitevisits</a>
    </div>
</div>
