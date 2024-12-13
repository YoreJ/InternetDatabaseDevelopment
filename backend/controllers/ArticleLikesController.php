<?php

namespace app\controllers;

use app\models\ArticleLikes;
use app\models\ArticleLikesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleLikesController implements the CRUD actions for ArticleLikes model.
 */
class ArticleLikesController extends Controller
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
     * Lists all ArticleLikes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ArticleLikesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArticleLikes model.
     * @param int $LikeID Like ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($LikeID)
    {
        return $this->render('view', [
            'model' => $this->findModel($LikeID),
        ]);
    }

    /**
     * Creates a new ArticleLikes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ArticleLikes();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'LikeID' => $model->LikeID]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ArticleLikes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $LikeID Like ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($LikeID)
    {
        $model = $this->findModel($LikeID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'LikeID' => $model->LikeID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ArticleLikes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $LikeID Like ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($LikeID)
    {
        $this->findModel($LikeID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ArticleLikes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $LikeID Like ID
     * @return ArticleLikes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($LikeID)
    {
        if (($model = ArticleLikes::findOne(['LikeID' => $LikeID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
