<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\VideosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="videos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'VideoID') ?>

    <?= $form->field($model, 'Title') ?>

    <?= $form->field($model, 'URL') ?>

    <?= $form->field($model, 'UserID') ?>

    <?= $form->field($model, 'UploadedAt') ?>

    <?php // echo $form->field($model, 'UpdatedAt') ?>

    <?php // echo $form->field($model, 'ViewCount') ?>

    <?php // echo $form->field($model, 'LikeCount') ?>

    <?php // echo $form->field($model, 'PictureURL') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
