<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\WebsiteVisits $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="website-visits-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'VisitCount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
