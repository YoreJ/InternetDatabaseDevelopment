<?php

namespace app\controllers;

use app\models\WebsiteVisits;
use app\models\WebsiteVisitsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WebsiteVisitsController implements the CRUD actions for WebsiteVisits model.
 */
class WebsiteVisitsController extends Controller
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
     * Lists all WebsiteVisits models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new WebsiteVisitsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WebsiteVisits model.
     * @param int $ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ID)
    {
        return $this->render('view', [
            'model' => $this->findModel($ID),
        ]);
    }

    /**
     * Creates a new WebsiteVisits model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new WebsiteVisits();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'ID' => $model->ID]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WebsiteVisits model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($ID)
    {
        $model = $this->findModel($ID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ID' => $model->ID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WebsiteVisits model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ID)
    {
        $this->findModel($ID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WebsiteVisits model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $ID
     * @return WebsiteVisits the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ID)
    {
        if (($model = WebsiteVisits::findOne(['ID' => $ID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Increments the visit count.
     *
     * @return \yii\web\Response
     */
    public function actionIncrementVisits()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $webviews = WebsiteVisits::find()->one();

        if ($webviews === null) {
            $webviews = new WebsiteVisits();
            $webviews->Views = 1;
        } else {
            $webviews->Views += 1;
        }

        if ($webviews->save()) {
            return ['status' => 1, 'message' => '浏览量增加成功'];
        } else {
            return ['status' => -1, 'message' => '浏览量增加失败'];
        }
    }
}
