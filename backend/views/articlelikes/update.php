<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ArticleLikes $model */

$this->title = 'Update Article Likes: ' . $model->LikeID;
$this->params['breadcrumbs'][] = ['label' => 'Article Likes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->LikeID, 'url' => ['view', 'LikeID' => $model->LikeID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="article-likes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
