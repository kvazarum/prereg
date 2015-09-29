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
 *
 * @property Records $record
 * @property Insurers $insurer
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
            [['record_id', 'insurer_id', 'type'], 'integer'],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => Records::className(), 'targetAttribute' => ['record_id' => 'id']],
            [['insurer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Insurers::className(), 'targetAttribute' => ['insurer_id' => 'id']],
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
            'insurer_id' => 'Insurer ID',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(Records::className(), ['id' => 'record_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInsurer()
    {
        return $this->hasOne(Insurers::className(), ['id' => 'insurer_id']);
    }
}
