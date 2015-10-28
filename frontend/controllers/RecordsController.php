<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Records;
use frontend\models\RecordsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\modules\doctors\models\Doctors;
use frontend\modules\occupations\models\Occupations;
use yii\helpers\Json;
use yii\web\Response;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;

/**
 * RecordsController implements the CRUD actions for Records model.
 */
class RecordsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'view', 'get-data', 'get-day-report-detail', 
                    'get-day-report-main', 'get-report-by-specialist', 'get-request', 'save-record',
                    'specialist-record', 'request', 'register', 'index', 'day-report', 'specialist-report', 'add-visit'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['moder']
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]                    
                ]
            ],
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
            if (!Yii::$app->user->isGuest)
            {
                Yii::$app->user->id;
            }
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
            if (!Yii::$app->user->isGuest)
            {
                $model->user_id = Yii::$app->user->id;
            }
            if ($model->reserved == false)
            {
                $model->name = '';
                $model->email = null;
                $model->phone = null;
                $model->visited = 0;
                $model->visit_type = null;
                $model->insurer_id = null;
            }
            if ($model->visited == false){
                $model->insurer_id = null;
                $model->visit_type = null;
            }
            $result = $model->save();
            if (!$result)
            {
                Yii::info('Model not updated due to validation error.', __METHOD__);
            }
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
            $record->user_id = Yii::$app->user->id;

            $minute = $time%60;
            $hour = ($time - $minute)/60;

            $strTime = explode('-', $date);
            $year = $strTime[0];
            $month = $strTime[1];
            $day = $strTime[2];
            $strTime = mktime($hour, $minute, 0, $month, $day,  $year);
            $strTime = date('Y-m-d H:i:s', $strTime);
            $record->start_time = $strTime;
            $record->insurer_id = null;
            $record->visit_type = null;
            $result;
            if($record->validate())
            {
                $result = $record->save();
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Произошла ошибка при записи данных');
                $result = false;
            }            
        }
        else
        {
            $result = false;
        }
        return $result;
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
        $year = $strTime[0];
        $month = $strTime[1];
        $day = $strTime[2];
        //$strTime = mktime($hour, $minute, 0, $strTime[1], $strTime[0],  $strTime[2]);
        $strTime = mktime($hour, $minute, 0, $month, $day,  $year);
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
        $sql = 'SELECT `dc`.`name`, COUNT(if (`rd`.`reserved` = "1",1,null)) AS `res`, COUNT(IF(`rd`.`visited` = "1",1,null)) AS `vis`, `oc`.`name` AS `oc_name`, `sp`.`id`
            FROM `records` AS `rd`, `specialists` AS `sp`, `doctors` AS `dc`, `occupations` AS `oc` 
            WHERE `rd`.`specialist_id` = `sp`.`id`
            AND `oc`.`id`=`sp`.`occupation_id` AND `sp`.`doctor_id` = `dc`.`id`
            AND DATE(`rd`.`start_time`) >= "'.$date_from.'" 
            AND DATE(`rd`.`start_time`) <= "'.$date_to.'"
            GROUP BY `oc`.`name`, `specialist_id`
            ORDER BY `oc`.`name`, `specialist_id`';
        
        $provider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        $models = $provider->getModels();
        return Json::encode($models);
    }    
    
    
    public function actionSpecialistReport()
    {
        return $this->render('specialist-report');
    }

/**
 *
 * @return type
 */
    public function actionDayReport()
    {
        $model = new Records();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }

        return $this->render('day-report', [
            'model' => $model,
        ]);
    }

/**
 * Получение данных о заявках на приём к заданному специалисту <b>$specialist_id</b>
 * в заданный день <b>$date_</b>
 * @param date $date_ дата отчёта
 * @param integer $specialist_id <b>id</b> специалиста
 * @return mixed
 */
    public function actionGetDayReportDetail($date_, $specialist_id)
    {
        $sql = 'SELECT `records`.`name`, `records`.`phone`, `records`.`start_time`, `records`.`id`, `user`.`name` AS `uname`
            FROM `records`, `user`
            WHERE DATE(`start_time`) = "'.$date_.'"
            AND `reserved` = "1"
            AND `visited` = "0"
            AND `specialist_id` = "'.$specialist_id.'"
            AND `records`.`user_id` = `user`.`id`
            ORDER BY (`start_time`)';

        $provider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        $models = $provider->getModels();
        return Json::encode($models);
    }

/**
 * Получение id специалистов, у который есть заявки на приём на заданный день $date_
 * @param date $date_ дата приёма
 * @return mixed
 */
    public function actionGetDayReportMain($date_)
    {
        $sql = 'SELECT `rd`.`specialist_id`, `oc`.`name` AS `oname`, `dr`.`name` AS `dname`, `rd`.`start_time`
            FROM `records` AS `rd`, `occupations` AS `oc`, `specialists` AS `sp`, `doctors` AS `dr`
            WHERE DATE(`rd`.`start_time`) = "'.$date_.'"
            AND `rd`.`reserved` = "1"
            AND `rd`.`visited` = "0"
            AND `rd`.`specialist_id` = `sp`.`id` AND `sp`.`occupation_id` = `oc`.`id`
            AND `sp`.`doctor_id` = `dr`.`id`
            GROUP BY `specialist_id`
            ORDER BY `dname`';
        $provider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        $models = $provider->getModels();
        return Json::encode($models);
    }

    public function actionAddVisit($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = date('Y-m-d H:i:s');
            if (!Yii::$app->user->isGuest)
            {
                $model->user_id = Yii::$app->user->id;
            }
            if (!is_numeric($model->insurer_id))
            {
                $model->insurer_id = null;
            }
            $model->reserved = true;
            $model->visited = true;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            $model->visit_type = 0;
            return $this->render('add-visit', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionDeleteRecord($items)
    {
        $items = explode(",", $items);
        Yii::$app->session->setFlash('success', $items);
        foreach ($items as $item) {
            $model = Records::findOne($item)->delete();
        }
        $searchModel = new RecordsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);        
    }
}