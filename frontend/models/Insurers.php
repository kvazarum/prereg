<?php

namespace frontend\models;

use Yii;
use frontend\modules\log\models\UserLogin;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "insurers".
 *
 * @property string $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Payments[] $payments
 */
class Insurers extends \yii\db\ActiveRecord
{
    private $attributes_diff = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'insurers';
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payments::className(), ['insurer_id' => 'id']);
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
        if ($insert){
            $params = Json::encode(['ID' => $this->id]);
            $action = UserLogin::ACTION_ADD_INSURER;
        }
        else {
            $params = Json::encode($this->attributes_diff);
            $action = UserLogin::ACTION_UPDATE_INSURER;
        }
        UserLogin::addLog($action, $params);
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete() {
        $data = Json::encode(['ID' => $this->id, 'name' => $this->name]);
        UserLogin::addLog(UserLogin::ACTION_DELETE_INSURER, $data);
        
        parent::afterDelete();
    }    
}
