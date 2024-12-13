<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Conversations $model */

$this->title = 'Create Conversations';
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
