<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "requests".
 *
 * @property integer $id
 * @property string $record_id
 * @property integer $name
 * @property integer $phone
 * @property integer $email
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Records $record
 */
class Requests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'name', 'phone', 'email', 'created_at', 'updated_at'], 'required'],
            [['record_id', 'name', 'phone', 'email'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => Records::className(), 'targetAttribute' => ['record_id' => 'id']],
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
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
