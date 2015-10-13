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
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
/**
 * DoctorsController implements the CRUD actions for Doctors model.
 */
class DoctorsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'view', 'index'],
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
        $model = $this->findModel($id);
        $this->changeTimeFormat($model); // форматируем время начала и конца работы
        
        return $this->render('view', [
            'model' => $model,
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

        if (Yii::$app->user->can('moder') && $model->load(Yii::$app->request->post()) && !$this->actionIsDouble($model->name)) {
            if (count(explode(':', $model->start_time)) != 2)
            {
                throw new \yii\base\ErrorException('Неправильный формат времени приёма!');                
            }
            $this->changeTimeFormat($model);
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

        if (Yii::$app->user->can('moder') && $model->load(Yii::$app->request->post())) {
            if (count(explode(':', $model->start_time)) != 2)
            {
                throw new \yii\base\ErrorException('Неправильный формат времени приёма! ');
            }            
            $this->changeTimeFormat($model);
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
        if (Yii::$app->user->can('moder'))
        {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }
        else
        {
            throw new ForbiddenHttpException();
        }
        
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
        if (count($start_time) == 2)
        {
            $model->start_time = Doctors::timeToInt($model->start_time);
           
            $model->end_time = Doctors::timeToInt($model->end_time);
        }
        else
        {
            $model->start_time = Doctors::timeToString($model->start_time);
            $model->end_time = Doctors::timeToString($model->end_time);
        }
    }
    
/**
 * Преобразование числа минут во время в формате чч:мм
 * @param string $string
 * @return string время в формате чч:мм
 */    
//    private function intToTime($string)
//    {
//        $minute = $string%60;
//        $hour = ($string - $minute)/60;
//        if ($minute < 10)
//        {
//            $minute = '0'.$minute;
//        }
//        if ($hour < 10)
//        {
//            $hour = '0'.$hour;
//        }
//        return $hour.':'.$minute;
//    }

/**
 * Преобразование времени в формате чч:мм в число минут
 * @param string $string
 * @return int количество минут
 */        
//    private function timeToInt($string)
//    {
//        $string = explode(':', $string);
//        $hour = $string[0];
//        $minute = $string[1];
//        return $hour*60+$minute;
//    }

/**
 * Проверка на наличие совпадающих записей
 * @param type $name ФИО доктора
 * @return boolean
 */
    public function actionIsDouble($name)
    {
        $result = false;
        $model = Doctors::findOne(['name' => $name] );
        if($model)
        {
            $result = true;
        }
        return $result;
    }
}
