<?php

namespace frontend\models;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;
use frontend\modules\log\models\Log;

use Yii;

/**
 * This is the model class for table "occupations".
 *
 * @property integer $id
 * @property string $name
 * @property integer $period
 * @property integer $created_at
 * @property integer $updated_at
 */
class Occupations extends \yii\db\ActiveRecord
{
    private $attributes_diff = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'occupations';
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
            [['name', 'period', ], 'required'],
            [['period', 'created_at', 'updated_at'], 'integer'],
            [[ 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => 'Наименование',
            'period' => 'Длительность приёма',            
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }
    
    public static function getList()
    {
        $result = Occupations::find()->select(['id','name'])->all();
        $result = ArrayHelper::map($result, 'id', 'name');
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
            $action = Log::ACTION_ADD_OCCUPATION;
        }
        else {
            $params = Json::encode($this->attributes_diff);
            $action = Log::ACTION_UPDATE_OCCUPATION;
        }
        Log::addLog($action, $params);
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete() {
        $data = Json::encode(['ID' => $this->id, 'name' => $this->name]);
        Log::addLog(Log::ACTION_DELETE_OCCUPATION, $data);
        
        parent::afterDelete();
    }    
}
