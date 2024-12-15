<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\VideoComments $model */

$this->title = 'Create Video Comments';
$this->params['breadcrumbs'][] = ['label' => 'Video Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-comments-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
