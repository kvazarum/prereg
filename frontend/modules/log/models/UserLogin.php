<?php

namespace frontend\modules\log\models;

use Yii;
use frontend\modules\admin\models\User;

/**
 * This is the model class for table "logs".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $action
 * @property integer $login_at
 * @property string $data
 *
 * @property User $user
 */
class UserLogin extends \yii\db\ActiveRecord
{
    const UNKNOWN_ACTION = 0;
    
    const ACTION_ADD_DOCTOR = 10;
    const ACTION_UPDATE_DOCTOR = 11;
    const ACTION_DELETE_DOCTOR = 12;
    
    const ACTION_ADD_OCCUPATION = 20;
    const ACTION_UPDATE_OCCUPATION = 21;
    const ACTION_DELETE_OCCUPATION = 22;
    
    const ACTION_ADD_SPECIALIST = 30;
    const ACTION_UPDATE_SPECIALIST = 31;
    const ACTION_DELETE_SPECIALIST = 32;    
    
    const ACTION_ADD_INSURER = 40;
    const ACTION_UPDATE_INSURER = 41;
    const ACTION_DELETE_INSURER = 42;   
    
    const ACTION_ADD_USER = 50;
    const ACTION_UPDATE_USER = 51;
    const ACTION_DELETE_USER = 52;    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'action'], 'required'],
            [['id', 'login_at',  'action'], 'integer'],
            [['user_id',], 'safe'],
            [['data',], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'login_at' => 'Log At',
            'data' => 'Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public static function getActionName($number)
    {
        switch ($number){
            case 10:
                $result = 'ACTION_ADD_DOCTOR';
                break;
            case 11:
                $result = 'ACTION_UPDATE_DOCTOR';
                break;
            case 12:
                $result = 'ACTION_DELETE_DOCTOR';
                break;
            case 20:
                $result = 'ACTION_ADD_OCCUPATION';
                break;
            case 21:
                $result = 'ACTION_UPDATE_OCCUPATION';
                break;
            case 22:
                $result = 'ACTION_DELETE_OCCUPATION';
                break;          
            case 30:
                $result = 'ACTION_ADD_SPECIALIST';
                break;
            case 31:
                $result = 'ACTION_UPDATE_SPECIALIST';
                break;
            case 32:
                $result = 'ACTION_DELETE_SPECIALIST';
                break;            
            case 40:
                $result = 'ACTION_ADD_INSURER';
                break;
            case 41:
                $result = 'ACTION_UPDATE_INSURER';
                break;
            case 42:
                $result = 'ACTION_DELETE_INSURER';
                break;            
            case 50:
                $result = 'ACTION_ADD_USER';
                break;
            case 51:
                $result = 'ACTION_UPDATE_USER';
                break;
            case 52:
                $result = 'ACTION_DELETE_USER';
                break;                
            default :
                $result = 'UNKNOWN_ACTION';
                break;
        }
        return $result;
    }
    
    public static function addLog($action, $params)
    {
        $log = new self;
        $log->user_id = Yii::$app->user->id;
        $log->login_at = time();
        $log->action = $action;
        $log->data = $params;
        $log->insert(true);
    }
}
