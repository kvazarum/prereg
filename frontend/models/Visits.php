<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "visits".
 *
 * @property string $id
 * @property string $record_id
 * @property string $insurer_id
 * @property integer $type
 * @property string $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property Records $record
 */
class Visits extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'type'], 'required'],
            [['record_id', 'insurer_id', 'type', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'record_id' => 'Record ID',
            'insurer_id' => 'Страховая компания',
            'type' => 'Тип посещения',
            'user_id' => 'Пользователь',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getRecord()
    {
        return $this->hasOne(Records::className(), ['id' => 'record_id']);
    }
}
