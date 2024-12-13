<?php

use app\models\ArticleLikes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ArticleLikesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Article Likes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-likes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Article Likes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'LikeID',
            'UserID',
            'ArticleID',
            'LikedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ArticleLikes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'LikeID' => $model->LikeID]);
                 }
            ],
        ],
    ]); ?>


</div>
