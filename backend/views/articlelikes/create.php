<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ArticleLikes $model */

$this->title = 'Create Article Likes';
$this->params['breadcrumbs'][] = ['label' => 'Article Likes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-likes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
