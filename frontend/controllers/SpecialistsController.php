<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Specialists;
use frontend\models\SpecialistsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Doctors;
use frontend\models\Occupations;
use yii\helpers\Json;
use frontend\models\Records;

/**
 * SpecialistsController implements the CRUD actions for Specialists model.
 */
class SpecialistsController extends Controller
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
     * Lists all Specialists models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpecialistsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Specialists model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    
    public function actionViewss($id)
    {
        return $this->render('viewss', [
            'id' => $id
        ]);        
    }   

    /**
     * Creates a new Specialists model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Specialists();

        if ($model->load(Yii::$app->request->post())) {
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
     * Updates an existing Specialists model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
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
     * Deletes an existing Specialists model.
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
     * Finds the Specialists model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Specialists the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Specialists::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
/**
 * Получение набора <option> с данными врачей по заданной специальности <b>id</b>
 * @param type $id
 */    
    public function actionLists($id)
    {
        $count = Specialists::find()
                ->where(['occupation_id' => $id])
                ->count();
        if ($count > 0)
        {
            $specs = Specialists::find()
                ->where(['occupation_id' => $id])
                ->all();            
            foreach ($specs as $spec)
            {
                $doctor = Doctors::findOne($id);        
                echo '<option value="'.$spec->id.'">'.$doctor->name.'</option>';
            }
        }  else {
            echo '<option>Нет данных</option>';
        }
    }
    
    public function actionGetData($id)
    {
        $result = Specialists::findOne($id);
        return Json::encode($result);
    }
    
    public function actionGetD($id)
    {
        $sql ='SELECT `dc`.`start_time`, `dc`.`end_time` FROM `specialists` as `sp`, `doctors` as `dc` WHERE `sp`.`id` = "'.$id.'" AND `sp`.`doctor_id` =  `dc`.`id`';
        $result = Records::findBySql($sql)->one();
        echo Json::encode($result);
    }
    
    public function actionGetByOccup($id)
    {
        $result = Specialists::find()->where(['occupation_id' => $id])->all();
        return Json::encode($result);
    }
    
    public function actionGetName($id)
    {
        $result = Specialists::findOne($id);
        
        $result = Doctors::findOne($result->doctor_id);
        return $result->name;
    }
}
