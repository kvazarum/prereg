<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Occupations;

/* @var $this yii\web\View */
/* @var $model frontend\models\records */
/* @var $form ActiveForm */
?>
<div class="dayedit">

    <?php $form = ActiveForm::begin(); ?>

        <?php
            $models = Occupations::find()->all();  //массив
            echo Html::dropDownList('occ-list', '', ArrayHelper::map($models, 'id', 'name'),
                    [
                        'prompt' => 'Выберите специальность',
                            'onchange' => '$.post("index.php?r=specialists/list&id=" + $(this).val(),function(data){
                        $("select#specs-list").html(data) 
                        })'
                    ]);
            echo Html::dropDownList('specs-list');            
        ?>
    
        <?= $form->field($model, 'specialist_id') ?>
        <?= $form->field($model, 'cabinet_id') ?>
        <?= $form->field($model, 'start_time') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- dayedit -->
