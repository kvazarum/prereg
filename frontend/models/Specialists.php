<?php

namespace frontend\models;

use Yii;
use frontend\models\Doctors;
use frontend\models\Records;

/**
 * This is the model class for table "specialists".
 *
 * @property integer $id
 * @property integer $doctor_id
 * @property integer $occupation_id
 * @property string $created_at
 * @property string $updated_at
 * @property Doctors $doctor
 * @property Occupations $occupation
 */
class Specialists extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'specialists';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctor_id',  'occupation_id', 'created_at', 'updated_at'], 'required'],
//            [[,], 'integer'],
            [['created_at','doctor_id', 'occupation_id', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctor_id' => 'Врач',
            'occupation_id' => 'Специальность',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getDoctor()
    {
        return $this->hasOne(Doctors::className(), ['id' => 'doctor_id']);
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOccupation()
    {
        return $this->hasOne(Occupations::className(), ['id' => 'occupation_id']);
    }    
    
/**
 * Получение кол-ва приёмов в заданный день $date
 * @param datetime $date день
 * @return int
 */    
    public function getCountRecords($date)
    {
        $count = 0;
        $month = date('m', $date);
        $year = date('Y', $date);
        $day = date('j', $date);        
        $start =  $year.'-'.$month.'-'.$day;
        $sql ='SELECT * FROM `records` WHERE DATE(`start_time`) = "'.$start.'" AND `specialist_id` = "'.$this->id.'" ';
        $sql .= 'AND `reserved` = false';

        $result = Records::findBySql($sql)->all();
        $count = count($result);
        
//        echo $sql.'<br />'.$count.'<br />';
        
        return $count;
    }
    
    public function getRecords($date, $spec)
    {
        $month = date('m', $date);
        $year = date('Y', $date);
        $day = date('j', $date);        
        $start =  $year.'-'.$month.'-'.$day;
        $sql ='SELECT * FROM `records` WHERE DATE(`start_time`) = "'.$start.'" AND `specialist_id` = "'.$this->id.'" ';
        $sql .= 'AND `reserved` = false';

        $result = Records::findBySql($sql)->all();
        
        return $result;
    }   
    
    public static function isDouble($doctor_id, $occupation_id)
    {
        $result = false;
        $model = Specialists::findOne(['doctor_id' => $doctor_id, 'occupation_id' => $occupation_id] );
        if($model)
        {
            $result = true;
        }
        return $result;
    }    
}