<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Doctors;
use frontend\models\Occupations;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model frontend\models\Specialists */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="specialists-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php
    $list = ArrayHelper::map(Doctors::find()->orderBy('name')->all(), 'id' , 'name');
    echo $form->field($model, 'doctor_id')->widget(Select2::classname(), [
        'data' => $list,
        'language' => 'ru',
        'options' => ['placeholder' => 'Выберите врача ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);  
    ?>

    <?php
        $list = ArrayHelper::map(Occupations::find()->orderBy('name')->all(), 'id' , 'name');

        echo $form->field($model, 'occupation_id')->widget(Select2::classname(), [
            'data' => $list,
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите специальность ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);        
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Изменить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
