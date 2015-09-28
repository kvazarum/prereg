<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
 * @property string $created_at
 * @property string $updated_at
 */
class Doctors extends \yii\db\ActiveRecord
{
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
            [['number', 'price_initial', 'price_secondary'], 'integer'],
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
    
    public static function timeToString($string)
    {
        $hour = ($string - $string%60)/60;
        $minute = $string%60;
        if ($minute < 10)
        {
            $minute = '0'.$minute;
        }
        return (string)$hour.':'.(string)$minute;
    }    
}
