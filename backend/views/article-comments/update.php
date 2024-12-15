<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ArticleComments $model */

$this->title = 'Update Article Comments: ' . $model->CommentID;
$this->params['breadcrumbs'][] = ['label' => 'Article Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CommentID, 'url' => ['view', 'CommentID' => $model->CommentID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="article-comments-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
