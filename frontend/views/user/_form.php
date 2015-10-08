<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\User;
use frontend\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php
        $items = AuthItem::findAll(['type' => 1]);
        $names = [];
        foreach ($items as $item)
        {
            $names[] = [$item->name => $item->name];
        }
        echo $form->field($model, 'role')->dropDownList($names);
        ?>

    <?= $form->field($model, 'status')->dropDownList([User::STATUS_DELETED => 'DELETED', 
                                                    User::STATUS_NOT_ACTIVE => 'NOT_ACTIVE',
                                                    User::STATUS_ACTIVE => 'ACTIVE',
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
