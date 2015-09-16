<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Doctors;
use frontend\models\Specialists;
use frontend\models\Occupations;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\Records */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="records-form">

    <?php $form = ActiveForm::begin();
    
    ?>


    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    
    <?php
//        echo Html::activeCheckbox($model, 'reserved');
        
        echo $form->field($model, 'reserved')->widget(SwitchInput::classname(), [
             'type' => SwitchInput::CHECKBOX
            ]);
        
//        echo Html::activeCheckbox($model, 'visited'); 
        echo $form->field($model, 'visited')->widget(SwitchInput::classname(), [
             'type' => SwitchInput::CHECKBOX
            ]);        
    ?>
    
    
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
