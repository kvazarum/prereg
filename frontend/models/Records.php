<?php

namespace frontend\models;

use Yii;
use frontend\models\Records;

/**
 * This is the model class for table "records".
 *
 * @property string $id
 * @property integer $specialist_id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $cabinet_id
 * @property string $start_time
 * @property integer $reserved
 * @property integer $user_id
 * @property integer $visited
 * @property integer $sum
 * @property string $created_at
 * @property string $updated_at
 * @property Specialists $specialist
 * @property Occupation $occupation
 */
class Records extends \yii\db\ActiveRecord
{
    
    public static $days = array(
        'Вс',
        'Пн',
        'Вт',
        'Ср',
        'Чт',
        'Пт',
        'Сб'
    );  
    
    public static $months = array(
        'Янв',
        'Фев',
        'Мар',
        'Апр',
        'Май',
        'Июн',
        'Июл',
        'Авг',
        'Сен',
        'Окт',
        'Ноя',
        'Дек'
    );    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'records';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['specialist_id', 'start_time', 'reserved', 'visited', 'created_at', 'updated_at'], 'required'],
            ['name', 'filter', 'filter' => 'trim'],
            [['specialist_id', 'reserved', 'visited'], 'integer'],
            [['start_time', 'created_at', 'updated_at', 'name', 'phone', 'email', 'user_id'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'specialist_id' => 'Специалист',
            'occupationName' => 'Специальность',
            'user_id' => 'Регистратор',
            'start_time' => 'Время начала приёма',
            'reserved' => 'Заказ',
            'visited' => 'Посещение',
            'sum' => 'Сумма',
            'name' => 'Фамилия Имя Отчество пациента',
            'phone' => '№ телефона',
            'email' => 'Email (электронная почта)',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }
    
    public static function getRecords($date, $specialist_id)
    {
        $month = date('m', $date);
        $year = date('Y', $date);
        $day = date('j', $date);        
        $start =  $year.'-'.$month.'-'.$day;
        
        $sql ='SELECT * FROM `records` WHERE DATE(`start_time`) = "'.$start.'" AND `specialist_id` = "'.$specialist_id.'" ';
        $sql .= 'AND `reserved` = false ORDER BY `start_time`';
        $result = Records::findBySql($sql)->all();
        return $result;
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSpecialist()
    {
        return $this->hasOne(Specialists::className(), ['id' => 'specialist_id']);
    }

/**
 * Получение название специальности текущего врача
 * @return type
 */
    public function getOccupationName()
    {
        return $this->specialist->occupation->name;
    }

    public function getRegistratorName()
    {
        
    }
}
