<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WebsiteVisits $model */

$this->title = 'Create Website Visits';
$this->params['breadcrumbs'][] = ['label' => 'Website Visits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-visits-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
