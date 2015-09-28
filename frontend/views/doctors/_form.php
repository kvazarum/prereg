<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\Doctors */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_initial')->textInput()->hint('Сумма в рублях') ?>

    <?= $form->field($model, 'price_secondary')->textInput()->hint('Сумма в рублях') ?>

    <?= $form->field($model, 'start_time')->widget(MaskedInput::className(),[
            'mask' => '99:99',
        ])->hint('время в формате чч:мм'); ?>

    <?= $form->field($model, 'end_time')->widget(MaskedInput::className(),[
            'mask' => '99:99',
        ])->hint('время в формате чч:мм'); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
