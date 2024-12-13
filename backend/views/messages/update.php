<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Messages $model */

$this->title = 'Update Messages: ' . $model->MessageID;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->MessageID, 'url' => ['view', 'MessageID' => $model->MessageID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="messages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
