<?php

use app\models\Conversations;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ConversationsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Conversations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversations-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Conversations', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ConversationID',
            'UserID',
            'StartedAt',
            'EndedAt',
            'Status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Conversations $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'ConversationID' => $model->ConversationID]);
                 }
            ],
        ],
    ]); ?>


</div>
