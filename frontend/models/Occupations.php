<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "occupations".
 *
 * @property integer $id
 * @property string $name
 * @property integer $period
 * @property string $created_at
 * @property string $updated_at
 */
class Occupations extends \yii\db\ActiveRecord
{
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
    public function rules()
    {
        return [
            [['name', 'period',  'created_at', 'updated_at'], 'required'],
            [['period'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
}
