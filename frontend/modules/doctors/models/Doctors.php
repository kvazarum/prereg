<?php

namespace frontend\modules\doctors\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use frontend\modules\log\models\Log;

/**
 * This is the model class for table "doctors".
 *
 * @property integer $id
 * @property string $name
 * @property integer $number
 * @property string $description
 * @property string $phone
 * @property integer $price_initial
 * @property integer $price_secondary
 * @property string $start_time
 * @property string $end_time
 * @property integer $created_at
 * @property integer $updated_at
 */
class Doctors extends \yii\db\ActiveRecord
{
    private $attributes_diff = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doctors';
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
            [['name', 'number', 'description', 'phone', 'price_initial', 'price_secondary', 'start_time', 'end_time'], 'required'],
            [['number', 'price_initial', 'price_secondary', 'created_at', 'updated_at'], 'integer'],
            ['number', 'unique', 'targetClass' => self::className(),'message' => 'Этот табельный номер уже используется.'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Фамилия Имя Отчество врача',
            'number' => 'Таб. №',
            'description' => 'Примечание',
            'phone' => '№ телефона',
            'price_initial' => 'Цена перв. приёма',
            'price_secondary' => 'Цена втор. приёма',
            'start_time' => 'Начало рабочего дня',
            'end_time' => 'Конец рабочего дня',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }

/**
 * Преобразование числа минут во время в формате чч:мм
 * @param string $string
 * @return string время в формате чч:мм
 */
    public static function timeToString($string)
    {
        $hour = ($string - $string%60)/60;
        $minute = $string%60;
        if ($minute < 10)
        {
            $minute = '0'.$minute;
        }
        if ($hour < 10)
        {
            $hour = '0'.$hour;
        }
        return (string)$hour.':'.(string)$minute;
    }

/**
 * Преобразование времени в формате чч:мм в число минут
 * @param string $string
 * @return int количество минут
 */
    public static function timeToInt($string)
    {
        $string = explode(':', $string);
        $hour = $string[0];
        $minute = $string[1];
        return $hour*60+$minute;
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
            $action = Log::ACTION_ADD_DOCTOR;
        }
        else {
            $params = Json::encode($this->attributes_diff);
            $action = Log::ACTION_UPDATE_DOCTOR;
        }
        Log::addLog($action, $params);
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete() {
        $data = Json::encode(['ID' => $this->id, 'name' => $this->name]);
        Log::addLog(Log::ACTION_DELETE_DOCTOR, $data);
        
        parent::afterDelete();
    }
}
