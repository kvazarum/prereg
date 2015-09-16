<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Doctors;
use frontend\models\DoctorsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;

/**
 * DoctorsController implements the CRUD actions for Doctors model.
 */
class DoctorsController extends Controller
{
    public function ebhaviors()
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
     * Lists all Doctors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DoctorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Doctors model.
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
     * Creates a new Doctors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Doctors();

        if ($model->load(Yii::$app->request->post())) {
            if (count(explode(':', $model->start_time)) != 2)
            {
                throw new \yii\base\ErrorException('Неправильный формат времени приёма!');                
            }
            $this->changeTimeFormat($model);

            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Doctors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (count(explode(':', $model->start_time)) != 2)
            {
                throw new \yii\base\ErrorException('Неправильный формат времени приёма!');              
            }            
            $this->changeTimeFormat($model);           
            
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Doctors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Doctors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doctors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doctors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
/**
 * Получение данных врача по его <b>id</b>
 * @param integer $id уникальный идентификатор в базе данных
 * @return mixed набор полей записи о враче
 */    
    public static function actionGetData($id)
    {
        $doctor = Doctors::findOne($id);
        
        return Json::encode($doctor);
    }
    
/**
 * Изменение формата времени у экземпляра класса <b>Doctors</b>
 * старый формат <b>чч</b>:<b>мм</b>
 * выходной формат - количество минут от полночи: <b>часы</b> * 60 + <b>минуты</b>
 * @param Doctors $model экземпляр класса <b>Doctors</b>
 */    
    private function changeTimeFormat(&$model)
    {
        $start_time = explode(':', $model->start_time);
        $start_hour = $start_time[0];
        $start_minute = $start_time[1];
        $model->start_time = $start_hour*60+$start_minute;

        $end_time = explode(':', $model->end_time);
        $end_hour = $end_time[0];
        $end_minute = $end_time[1];           
        $model->end_time = $end_hour*60+$end_minute;        
    }
}
