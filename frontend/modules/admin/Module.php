<?php

namespace frontend\modules\admin;
use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\admin\controllers';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }    

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
