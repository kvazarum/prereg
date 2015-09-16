<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cabinets".
 *
 * @property integer $id
 * @property string $name
 * @property integer $number
 * @property string $created_at
 * @property string $updated_at
 */
class Cabinets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cabinets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'number'], 'required'],
            [['number'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['number'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'number' => '№ кабинета',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }
}
