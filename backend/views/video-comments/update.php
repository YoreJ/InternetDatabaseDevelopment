<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\VideoComments $model */

$this->title = 'Update Video Comments: ' . $model->CommentID;
$this->params['breadcrumbs'][] = ['label' => 'Video Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CommentID, 'url' => ['view', 'CommentID' => $model->CommentID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="video-comments-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
