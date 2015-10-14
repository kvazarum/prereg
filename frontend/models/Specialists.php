<?php

namespace frontend\models;

use frontend\modules\occupations\models\Occupations;
use Yii;
use frontend\modules\doctors\models\Doctors;
use frontend\models\Records;
use yii\helpers\Json;
use frontend\modules\log\models\Log;

/**
 * This is the model class for table "specialists".
 *
 * @property integer $id
 * @property integer $doctor_id
 * @property integer $occupation_id
 * @property string $created_at
 * @property string $updated_at
 * @property \frontend\modules\doctors\models\Doctors $doctor
 * @property Occupations $occupation
 */
class Specialists extends \yii\db\ActiveRecord
{
    private $attributes_diff = [];    
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
        return $this->hasOne(\frontend\modules\doctors\models\Doctors::className(), ['id' => 'doctor_id']);
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
            $action = Log::ACTION_ADD_SPECIALIST;
        }
        else {
            $params = Json::encode($this->attributes_diff);
            $action = Log::ACTION_UPDATE_SPECIALIST;
        }
        Log::addLog($action, $params);
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete() {
        $data = Json::encode(['ID' => $this->id, 'name' => $this->doctor->name, 'occupation' => $this->occupation->name]);
        Log::addLog(Log::ACTION_DELETE_SPECIALIST, $data);
        
        parent::afterDelete();
    }    
}