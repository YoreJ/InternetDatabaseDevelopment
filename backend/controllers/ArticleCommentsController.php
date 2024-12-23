<?php

/**
 * Coding by JiangYu 2210705
 * 使用 Gii 生成了 ArticleComments 的 CRUD
 */

namespace app\controllers;

use app\models\ArticleComments;
use app\models\ArticleCommentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleCommentsController implements the CRUD actions for ArticleComments model.
 */
class ArticleCommentsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ArticleComments models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ArticleCommentsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArticleComments model.
     * @param int $CommentID Comment ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($CommentID)
    {
        return $this->render('view', [
            'model' => $this->findModel($CommentID),
        ]);
    }

    /**
     * Creates a new ArticleComments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ArticleComments();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'CommentID' => $model->CommentID]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ArticleComments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $CommentID Comment ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($CommentID)
    {
        $model = $this->findModel($CommentID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CommentID' => $model->CommentID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ArticleComments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $CommentID Comment ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($CommentID)
    {
        $this->findModel($CommentID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ArticleComments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $CommentID Comment ID
     * @return ArticleComments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($CommentID)
    {
        if (($model = ArticleComments::findOne(['CommentID' => $CommentID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
