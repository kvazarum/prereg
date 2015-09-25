<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Requests;
use frontend\models\RequestsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequestsController implements the CRUD actions for Requests model.
 */
class RequestsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Lists all Requests models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $searchModel = new RequestsSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
    }

    /**
     * Displays a single Requests model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Requests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        //$model = new Requests();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            if (!$model->reserved)
            {
                $model->updated_at = date('Y-m-d H:i:s');
                $model->reserved = 1;
                $model->visited = 0;
                if (!Yii::$app->user->isGuest)
                {
                    $model->user_id = Yii::$app->user->id;
                }
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Запись на указанное время уже осуществлена. Выберите другое время.');
                return $this->redirect(['/specialists/viewss', 'id' => $model->specialist->occupation_id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Requests model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
            return $this->render('/site/index');
    }

    /**
     * Deletes an existing Requests model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {  
//        $model = $this->findModel($id);
//        $model->reserved = 0;
//        $model->visited = 0;
//        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Requests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Requests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requests::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}