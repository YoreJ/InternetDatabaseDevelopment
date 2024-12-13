<?php

use app\models\Messages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MessagesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Messages', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'MessageID',
            'ConversationID',
            'Sender',
            'Content:ntext',
            'Timestamp',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Messages $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'MessageID' => $model->MessageID]);
                 }
            ],
        ],
    ]); ?>


</div>
