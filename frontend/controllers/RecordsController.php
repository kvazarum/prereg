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
use yii\web\Response;
use yii\data\SqlDataProvider;

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
            if ($model->visited == true)
            {
                $model->reserved = TRUE;
            }
//            if ($model->reserved == FALSE)
//            {
//                $model->visited = FALSE;
//            }
            $model->save();            
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else 
        {          
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
    public function actionGetReportBySpecialist($date_from, $date_to)
    {
//        $query = Records::find();

        // add conditions that should always apply here

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'sort' => [
//                'defaultOrder' => [
//                    'start_time' => SORT_ASC, 
//                ]
//            ]
//        ]);        
        

        $sql = 'SELECT `dc`.`name`, COUNT(if (`rd`.`reserved` = "1",1,null)) AS `res`, COUNT(IF(`rd`.`visited` = "1",1,null)) AS `vis`, `oc`.`name` AS `oc_name`, `sp`.`id`
            FROM `records` AS `rd`, `specialists` AS `sp`, `doctors` AS `dc`, `occupations` AS `oc` 
            WHERE `rd`.`specialist_id` = `sp`.`id`
            AND `oc`.`id`=`sp`.`occupation_id` AND `sp`.`doctor_id` = `dc`.`id`
            AND DATE(`rd`.`start_time`) >= "'.$date_from.'" 
            AND DATE(`rd`.`start_time`) <= "'.$date_to.'"
            GROUP BY `oc`.`name`, `specialist_id`
            ORDER BY `oc`.`name`, `specialist_id`';
        
//        $result = Records::findBySql($sql)->all();
//        return Json::encode($result);
        $provider = new SqlDataProvider([
            'sql' => $sql
        ]);
        $models = $provider->getModels();
        return Json::encode($models);
    }    
    
    
    public function actionSpecialistReport()
    {
        return $this->render('specialist-report');
    }    
}