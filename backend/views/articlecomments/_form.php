<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ArticleComments $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="article-comments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'UserID')->textInput() ?>

    <?= $form->field($model, 'ArticleID')->textInput() ?>

    <?= $form->field($model, 'Content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'CommentedAt')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
