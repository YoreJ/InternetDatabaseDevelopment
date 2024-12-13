<?php

namespace app\controllers;

use app\models\VideoLikes;
use app\models\VideoLikesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VideoLikesController implements the CRUD actions for VideoLikes model.
 */
class VideoLikesController extends Controller
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
     * Lists all VideoLikes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new VideoLikesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VideoLikes model.
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
     * Creates a new VideoLikes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new VideoLikes();

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
     * Updates an existing VideoLikes model.
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
     * Deletes an existing VideoLikes model.
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
     * Finds the VideoLikes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $LikeID Like ID
     * @return VideoLikes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($LikeID)
    {
        if (($model = VideoLikes::findOne(['LikeID' => $LikeID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
