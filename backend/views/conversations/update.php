<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Conversations $model */

$this->title = 'Update Conversations: ' . $model->ConversationID;
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ConversationID, 'url' => ['view', 'ConversationID' => $model->ConversationID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="conversations-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
