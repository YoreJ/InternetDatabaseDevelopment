<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MessagesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="messages-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'MessageID') ?>

    <?= $form->field($model, 'ConversationID') ?>

    <?= $form->field($model, 'Sender') ?>

    <?= $form->field($model, 'Content') ?>

    <?= $form->field($model, 'Timestamp') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
