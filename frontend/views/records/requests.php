<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Records */
/* @var $form ActiveForm */
?>
<div class="requests">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'specialist_id') ?>
        <?= $form->field($model, 'start_time') ?>
        <?= $form->field($model, 'reserved') ?>
        <?= $form->field($model, 'visited') ?>
        <?= $form->field($model, 'created_at') ?>
        <?= $form->field($model, 'updated_at') ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'phone') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- requests -->
