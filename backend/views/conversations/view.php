<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Conversations $model */

$this->title = $model->ConversationID;
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="conversations-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'ConversationID' => $model->ConversationID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'ConversationID' => $model->ConversationID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ConversationID',
            'UserID',
            'StartedAt',
            'EndedAt',
            'Status',
        ],
    ]) ?>

</div>
