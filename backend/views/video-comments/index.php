<?php

use app\models\VideoComments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\VideoCommentsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Video Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-comments-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Video Comments', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'CommentID',
            'UserID',
            'VideoID',
            'Content:ntext',
            'CommentedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, VideoComments $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'CommentID' => $model->CommentID]);
                 }
            ],
        ],
    ]); ?>


</div>
