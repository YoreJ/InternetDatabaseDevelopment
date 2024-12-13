<?php

use app\models\VideoLikes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\VideoLikesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Video Likes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-likes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Video Likes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'LikeID',
            'UserID',
            'VideoID',
            'LikedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, VideoLikes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'LikeID' => $model->LikeID]);
                 }
            ],
        ],
    ]); ?>


</div>
