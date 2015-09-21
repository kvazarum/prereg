<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "requests".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $reserved
 * @property string $created_at
 * @property string $updated_at
* @property Specialists $specialist
 */
class Requests extends \yii\db\ActiveRecord
{
    public $verifyCode;
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
            [['name', 'phone'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 50],
//            ['email', 'email'],
            ['verifyCode', 'captcha']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Фамилия, имя, отчество пациента',
            'phone' => 'Номер телефона',
            'email' => 'Email (электронная почта)',
            'reserved' => 'Зарезервировано',
            'created_at' => 'Создано',
            'updated_at' => 'Updated At',
        ];
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSpecialist()
    {
        return $this->hasOne(Specialists::className(), ['id' => 'specialist_id']);
    }    
}