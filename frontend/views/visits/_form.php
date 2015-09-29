<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Insurers;
use kartik\select2\Select2;
use frontend\models\Visits;

/* @var $this yii\web\View */
/* @var $model frontend\models\Visits */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visits-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'record_id')->textInput(['maxlength' => true]) ?>

    <?php
        $list = ArrayHelper::map(Insurers::find()->orderBy('name')->all(), 'id' , 'name');

        echo $form->field($model, 'insurer_id')->widget(Select2::classname(), [
        'data' => $list,
        'language' => 'ru',
        'options' => ['placeholder' => 'Выберите страховщика ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'type')->radioList([
            Visits::CASH => 'Наличные',
            Visits::INSURER => 'Страховая компания',
        ]) ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
