<?php

namespace frontend\modules\admin\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
        ];
    }
    
    
    public function actionIndex()
    {
        return $this->render('index');
    }
}
