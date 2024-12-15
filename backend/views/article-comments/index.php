<?php

use app\models\ArticleComments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ArticleCommentsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Article Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-comments-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Article Comments', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'CommentID',
            'UserID',
            'ArticleID',
            'Content:ntext',
            'CommentedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ArticleComments $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'CommentID' => $model->CommentID]);
                 }
            ],
        ],
    ]); ?>


</div>
