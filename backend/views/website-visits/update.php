<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WebsiteVisits $model */

$this->title = 'Update Website Visits: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Website Visits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'ID' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="website-visits-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
