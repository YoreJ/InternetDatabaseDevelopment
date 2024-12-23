<?php

/**
 * Coding by JiangYu 2210705
 * 使用 Gii 生成了 Conversations 的 CRUD
 */

namespace app\controllers;

use app\models\Conversations;
use app\models\ConversationsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConversationsController implements the CRUD actions for Conversations model.
 */
class ConversationsController extends Controller
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
     * Lists all Conversations models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ConversationsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Conversations model.
     * @param int $ConversationID Conversation ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ConversationID)
    {
        return $this->render('view', [
            'model' => $this->findModel($ConversationID),
        ]);
    }

    /**
     * Creates a new Conversations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Conversations();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'ConversationID' => $model->ConversationID]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Conversations model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $ConversationID Conversation ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($ConversationID)
    {
        $model = $this->findModel($ConversationID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ConversationID' => $model->ConversationID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Conversations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $ConversationID Conversation ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ConversationID)
    {
        $this->findModel($ConversationID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Conversations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $ConversationID Conversation ID
     * @return Conversations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ConversationID)
    {
        if (($model = Conversations::findOne(['ConversationID' => $ConversationID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
