<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Messages $model */

$this->title = $model->MessageID;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="messages-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'MessageID' => $model->MessageID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'MessageID' => $model->MessageID], [
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
            'MessageID',
            'ConversationID',
            'Sender',
            'Content:ntext',
            'Timestamp',
        ],
    ]) ?>

</div>
