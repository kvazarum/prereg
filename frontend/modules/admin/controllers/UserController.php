<?php

namespace frontend\modules\admin\controllers;

use Yii;
use frontend\modules\admin\models\User;
use frontend\modules\admin\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\PasswordChangeForm;
use yii\filters\AccessControl;
use frontend\models\AuthAssignment;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete',
                    'view', 'index', 'password-change'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin']
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->status = User::STATUS_NOT_ACTIVE;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save())
            {
                $assign = new AuthAssignment();
                $assign->item_name = 'user';
                $assign->user_id = $model->id;
                $assign->created_at = time();
                $assign->save(false);
            }            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $assignments = AuthAssignment::findAll(['user_id' => $id]);
        $roles = [];        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'roles' => $assignments,                
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

/**
 * Получение ФИО пользователя
 * @param integer $id
 * @return mixed
 */
    public function actionGetName($id)
    {
        $model = User::findOne($id);
        if ($model !== null)
        {
            $result = $model->name;
        }
        else
        {
            $result = false;
        }
        return $result;
    }

    public function actionPasswordChange()
    {
//        $user = $this->findModel();
        $id = $_REQUEST['id'];
//        $model = new PasswordChangeForm($user);
        $model = new PasswordChangeForm($id);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('passwordChange', [
                'model' => $model,
            ]);
        }
    }
}
