<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use kartik\switchinput\SwitchInput;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\Requests */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requests-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'phone')->
            widget(MaskedInput::className(), ['mask' => '8 (999) 999-99-99'])
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    
    <?php 
//        if ( !Yii::$app->user->isGuest ) {
//            echo $form->field($model, 'visited')->widget(SwitchInput::classname(), [
//             'type' => SwitchInput::CHECKBOX
//            ]);  
//        }
    ?>

    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
