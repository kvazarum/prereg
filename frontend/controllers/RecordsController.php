<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Records;
use frontend\models\RecordsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Doctors;
use frontend\models\Occupations;
use yii\helpers\Json;
use frontend\models\Requests;

/**
 * RecordsController implements the CRUD actions for Records model.
 */
class RecordsController extends Controller
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
     * Lists all Records models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RecordsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Список свободных приёмов на указанный день
     * @param string $id
     * @param string $date
     * @return mixed
     */
    public function actionList($id, $date)
    {
//        $searchModel = new RecordsSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',[
            'id' => $id,
            'date' => $date
        ]);
    }    

    /**
     * Displays a single Records model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Records model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $date=$_REQUEST['date'];
        $spec=$_REQUEST['spec'];
        $model = new Records();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->specialist_id = $spec;
            $model->start_time = $date;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Records model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
            
            $model2 = Requests::find()->where(['record_id' => $id])->one();
//            $model2->visited = $model->visited;
            $model2->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else 
        {
            if ($model->name == 'не указано')
            {
                $model->name = '';
            }
            if ($model->phone == 'не указано')
            {
                $model->phone = '';
            }            
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Records model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Records model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Records the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Records::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionDayedit()
    {
//        $model = new frontend\models\records();
        $model = new Records();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }

        return $this->render('dayedit', [
            'model' => $model,
        ]);
    } 
    
/**
 * Выбор времени бронирования
 * @return mixed
 */    
    public function actionRegister($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {    
            $model->updated_at = date('Y-m-d H:i:s');
            $model->reserved = TRUE;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
            return $this->render('register', [
                'model' => $model,
                'id' => $id,
            ]);
        }      
    } 
    
    public function actionAdminOccupations()
    {
        return $this->render('admin-occupations');
    } 
    
    public function actionAdminSpecialists($id)
    {
        return $this->render('admin-specialists', ['occupation_id' => $id]);
    }    
    
/**
 * Сохранение записи о свободном приёме в дату <b>$date</b> на время <b>$time</b>
 * к специалисту <b>$spec</b>
 * @param string $date дата записи
 * @param integer $spec номер специалиста
 * @param integer $time время приёма
 */    
    public function actionSaveRecord($date, $spec, $time)
    {
        $isDouble = self::hasRecord($date, $spec, $time);
        if (!$isDouble)
        {
            $record = new Records();
            $record->specialist_id = $spec;
            $record->reserved = 0;
            $record->visited = 0;
            $record->updated_at = date('Y-m-d H:i:s');
            $record->created_at = date('Y-m-d H:i:s');
            $record->name = 'не указано';
            $record->phone = 'не указано';

            $minute = $time%60;
            $hour = ($time - $minute)/60;

            $strTime = explode('-', $date);
            $year = $strTime[0];
            $month = $strTime[1];
            $day = $strTime[2];
            $strTime = mktime($hour, $minute, 0, $month, $day,  $year);
            $strTime = date('Y-m-d H:i:s', $strTime);
            $record->start_time = $strTime;

            if($record->validate())
            {
                $record->save();
            }
        }
    }
    
/**
 * Проверяет наличие записи в дату <b>$date</b> на время <b>$time</b>
 * и к специалисту <b>$spec</b>
 * @param string $date
 * @param integer $spec
 * @param type $time
 * @return boolean
 */    
    public static function hasRecord($date, $spec, $time)
    {   
        $minute = $time%60;
        $hour = ($time - $minute)/60;
        
        $strTime = explode('-', $date);
        $strTime = mktime($hour, $minute, 0, $strTime[1], $strTime[0],  $strTime[2]);
        $strTime = date('Y-m-d H:i:s', $strTime);
        
        $result = Records::find()->where(['specialist_id'=> $spec, 'start_time' => $strTime])->one();
        if ($result)
        {
            $result = true;
        }
        
        return $result;
    }
    
    public function actionRequest() {
        return $this->render('request', [
        ]);        
    }
    
    public static function actionGetData($date, $specialist_id)
    {
        $strTime = explode('-', $date);
        $year = $strTime[2];
        $month = $strTime[1];
        $day = $strTime[0];
        //$strTime = mktime(0, 0, 0, $strTime[1], $strTime[0],  $strTime[2]);
        //$strTime = date('Y-m-d H:i:s', $strTime);  
        
        $result = Records::find()->where(['specialist_id' => $specialist_id, 'DAY(start_time)' => $day,
                'MONTH(start_time)' => $month, 'YEAR(start_time)' => $year])->all();
        
        return Json::encode($result);
    }   
    
    public static function actionGetRequest($date_from, $date_to, $specialist_id)
    {
//        $strTime = explode('-', $date);
//        $year = $strTime[2];
//        $month = $strTime[1];
//        $day = $strTime[0];
        //$strTime = mktime(0, 0, 0, $strTime[1], $strTime[0],  $strTime[2]);
        //$strTime = date('Y-m-d H:i:s', $strTime);  
        
        $result = Records::find()->where(['specialist_id' => $specialist_id, 'DAY(start_time)' => $day,
                'MONTH(start_time)' => $month, 'YEAR(start_time)' => $year])->all();
        
        return Json::encode($result);
    }   
    
/**
 * Получение отчёта о заказах у выбранного врача за период
 * с <b>date_from</b> по <b>date_to</b>
 * @param integer $specialist_id номер специалиста в базе 
 * @param date $date_from дата начала отчёта
 * @param date $date_to дата конца отчёта
 * @return mixed набор данных
 */    
    public function getReportBySpecialist($specialist_id, $date_from, $date_to)
    {
        
//        SELECT `rd`.`start_time`, `rd`.`specialist_id`, `dc`.`name` FROM `records` as `rd`, 
//        `specialists` as `sp`, `doctors` as `dc` WHERE `sp`.`id` = "21" AND `sp`.`doctor_id` = `dc`.`id` 
//        AND `rd`.`specialist_id` = `sp`.`id` AND DATE(`rd`.`start_time`) >= "2015-09-17"
//         AND DATE(`rd`.`start_time`) <= "2015-09-18"
        $sql ='SELECT `rd`.`start_time`, `rd`.`end_time`, `rd`.`specialist_id` FROM `records` as `rd`, `specialists` as `sp`, `doctors` as `dc` '
                . 'WHERE `sp`.`id` = "'.$specialist_id.'" AND `sp`.`doctor_id` =  `dc`.`id`'
                . 'AND `rd`.`specialist_id` = `sp`.`id` '
                . 'AND `rd`.`start_time` > '.$date_from;
        $result = Records::findBySql($sql)->one();
        echo Json::encode($result);
    }    
}
