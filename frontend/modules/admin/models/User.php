<?php

namespace frontend\modules\admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\helpers\Json;
use frontend\modules\log\models\Log;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \common\models\User
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;
    
    const SCENARIO_PROFILE = 'profile';
    
    private $attributes_diff = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['username', 'name', 'email', 'status'],
            self::SCENARIO_PROFILE => ['email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'name' => 'Фамилия имя отчество',
            'auth_key' => 'Auth Key',
            'password' => 'Пароль',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
    * @inheritdoc
    */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
/**
 * Получение массива назначенных ролей пользователя
 * @return array
 */    
    public function getRoles()
    {
        $assignments = AuthAssignment::findAll(['user_id' => $this->id]);
        $result = [];
        foreach ($assignments as $assign)
        {
            $result[] = $assign->item_name;
        }
        return $result;
    }

    public function beforeSave($insert) {
        if ($insert) {
//                $this->addEventLog(EventLog::ACTION_ADD_ORDER);
        } else {
                $old = $this->getOldAttributes();
                $new = $this->getAttributes();
                $this->attributes_diff = array_diff_assoc($new, $old);
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        $params = Json::encode(['ID' => $this->id]);
        if ($insert){
            $action = Log::ACTION_ADD_USER;
        }
        else {
            $params .= Json::encode($this->attributes_diff);
            $action = Log::ACTION_UPDATE_USER;
        }
        Log::addLog($action, $params);
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete() {
        $data = Json::encode(['ID' => $this->id, 'name' => $this->name]);
        Log::addLog(Log::ACTION_DELETE_USER, $data);
        
        parent::afterDelete();
    }    
}
