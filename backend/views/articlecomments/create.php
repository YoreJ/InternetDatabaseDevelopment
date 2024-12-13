<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ArticleComments $model */

$this->title = 'Create Article Comments';
$this->params['breadcrumbs'][] = ['label' => 'Article Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-comments-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
