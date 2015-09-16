<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "requests".
 *
 * @property integer $id
 * @property string $record_id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $visited
 * @property string $created_at
 * @property string $updated_at
 * @property Records $record
 */
class Requests extends \yii\db\ActiveRecord
{
    public $verifyCode;
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
            [['record_id', 'name', 'phone', 'email'], 'required'],
            [['record_id', 'visited'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 50],
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
            'name' => 'Фамилия, имя, отчество пациента',
            'phone' => 'Номер телефона',
            'email' => 'Email (электронная почта)',
            'visited' => 'Visited',
            'created_at' => 'Создано',
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
